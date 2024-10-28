<?php

namespace App\Http\Controllers;

use App\Models\Anchor;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use App\Models\PenilaianGuru;
use App\Models\TahunPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\HasilPenilaianGuru;
use App\Models\Tinjauan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;
use ConvertApi\ConvertApi;


class GuruController extends Controller
{
    public function dashboard_guru()
    {
        $penilaian_guru_pending = PenilaianGuru::where('user_id', auth()->user()->id)->where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('user_id', auth()->user()->id)->where('status', 'SELESAI')->count();
        $tahun = TahunPenilaian::all();
        $label = Kriteria::all();
        $periode = PeriodePenilaian::all();
        $tahun_sekarang = TahunPenilaian::latest()->first();
        if (request('year')) {
            $tahun_sekarang = TahunPenilaian::where('tahun_penilaian', request('year'))->first();
        }
        $data = [];
        foreach ($periode as $p) {
            $penilaian_guru = PenilaianGuru::where('user_id', auth()->user()->id)->where('periode_penilaian_id', $p->id)->where('tahun_penilaian_id', $tahun_sekarang->id)->where('status', 'SELESAI')->first();
            $nama = $p->periode_penilaian;
            $nilai = [];
            if ($penilaian_guru) {
                foreach ($label as $l) {
                    $nilai_kriteria = 0;
                    foreach ($l->subKriteria as $sub_kriteria) {
                        $hasil_penilaian = HasilPenilaianGuru::where('penilaian_guru_id', $penilaian_guru->id)->whereRelation('anchor', 'sub_kriteria_id', $sub_kriteria->id)->first();
                        $nilai_sub_kriteria = $hasil_penilaian?->anchor->bobot;
                        $nilai_kriteria += $nilai_sub_kriteria ?? 0;
                    }
                    $nilai_kriteria = $nilai_kriteria / $l->subKriteria->count();
                    $nilai[] = $nilai_kriteria;
                }
                $data[] = [
                    'name' => $nama,
                    'data' => $nilai,
                ];
            } else {
                $data[] = [
                    'name' => $nama,
                    'data' => array_fill(0, $label->count(), 0),
                ];
            }
        }
        $label = $label->pluck('kriteria');
        return view('guru.pages.dashboard', compact('penilaian_guru_pending', 'penilaian_guru_selesai', 'tahun', 'data', 'label', 'tahun_sekarang'));
    }

    public function formulir()
    {
        $tahun = TahunPenilaian::where('aktif', true)->get();
        $periode = PeriodePenilaian::where('aktif', true)->get();
        return view('guru.pages.formulir', compact('tahun', 'periode'));
    }

    public function formulir_tahun(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'periode' => 'required',
        ]);

        $tahun = TahunPenilaian::find($request->tahun);
        $periode = PeriodePenilaian::find($request->periode);
        $kriteria = Kriteria::all();

        $formulir_old = PenilaianGuru::where('user_id', auth()->user()->id)
            ->where('tahun_penilaian_id', $request->tahun)
            ->where('periode_penilaian_id', $request->periode)
            ->first();

        if ($formulir_old) {
            return redirect()->route('formulir')->with('error', 'Penilaian sudah dilakukan');
        }

        if (!$tahun || !$periode) {
            return redirect()->route('formulir')->with('error', 'Tahun atau periode penilaian tidak ditemukan');
        }

        return view('guru.pages.formulir_tahun', compact('tahun', 'periode', 'kriteria'));
    }

    public function hasil_formulir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'periode' => 'required',
            'penilaian' => 'required|array|min:1',
            'nama_dokumen.*.*' => 'required',
            'dokumen.*.*' => 'required|file|max:2048',
        ]);

        if ($validator->fails()) return redirect()->route('formulir')->withErrors($validator->errors()->all());

        $tahun = TahunPenilaian::find($request->tahun);
        $periode = PeriodePenilaian::find($request->periode);

        if (!$tahun || !$periode) {
            return redirect()->route('formulir')->with('error', 'Tahun atau periode penilaian tidak ditemukan');
        }

        $formulir_old = PenilaianGuru::where('user_id', auth()->user()->id)
            ->where('tahun_penilaian_id', $request->tahun)
            ->where('periode_penilaian_id', $request->periode)
            ->first();

        if ($formulir_old) {
            return redirect()->route('formulir')->with('error', 'Penilaian sudah dilakukan');
        }

        $formulir = PenilaianGuru::create([
            'user_id' => auth()->user()->id,
            'tahun_penilaian_id' => $request->tahun,
            'periode_penilaian_id' => $request->periode,
            'status' => $request->collect('penilaian')->count() >= 21 ? 'PENDING' : 'DRAFT',
        ]);

        foreach ($request->penilaian as $key => $value) {
            $anchor = Anchor::find($value);
            $json = [];
            if (isset($request->dokumen[$key])) {
                foreach ($request->dokumen[$key] as $keyDokumen => $valueDokumen) {
                    $json[] = [
                        'nama' => $request->nama_dokumen[$key][$keyDokumen],
                        'path' => $valueDokumen->store('dokumen', 'public'),
                    ];
                }
            }

            HasilPenilaianGuru::create([
                'penilaian_guru_id' => $formulir->id,
                'anchor_id' => $anchor->id,
                'dokumen' => json_encode($json),
            ]);
        }


        return redirect()->route('formulir')->with('success', 'Penilaian berhasil disimpan');
    }

    public function profil()
    {
        return view('guru.pages.profil');
    }

    public function edit_profil(Request $request)
    {
        $rules = [
            'nama' => 'required',
        ];

        if ($request->password_lama && $request->password_baru) {
            $rules['password_baru'] = 'min:5';
            $rules['confirm_password'] = 'same:password_baru';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return redirect()->route('guru_profil')->withErrors($validator->errors()->all());

        $user = User::find(auth()->user()->id);

        if ($request->password_lama && $request->password_baru) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->route('guru_profil')->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }
            $user->password = bcrypt($request->password_baru);
        }

        $user->nama = $request->nama;
        $user->pangkat = $request->pangkat;
        $user->jabatan = $request->jabatan;
        $user->unit_kerja = $request->unit_kerja;
        $user->update();

        return redirect()->route('guru_profil')->with('success', 'Profil berhasil diubah');
    }

    public function riwayat_penilaian()
    {
        $penilaian = PenilaianGuru::where('user_id', auth()->user()->id);

        // Filter status penilaian
        if (request('status') == 'pending') {
            $penilaian = $penilaian->where('status', 'PENDING');
        } else if (request('status') == 'selesai') {
            $penilaian = $penilaian->where('status', 'SELESAI');
        }

        // Mengambil data penilaian dan melakukan pemetaan
        $penilaian = $penilaian->latest()->get()->map(function ($item) {
            if ($item->status === 'SELESAI') {
                // Mengambil semua kriteria dan inisialisasi nilai akhir
                $kriteria = Kriteria::all();
                $nak = 0;
                foreach ($kriteria as $k) {
                    $nv = 0;
                    foreach ($k->subKriteria as $sk) {
                        foreach ($item->hasil_penilaian_guru as $h) {
                            if ($h->anchor->sub_kriteria_id == $sk->id) {
                                $nv += $h->anchor->bobot;
                                break;
                            }
                        }
                    }
                    $nv = $nv / $k->subKriteria->count(); // Rata-rata nilai subkriteria
                    $nv = $nv * $k->bobot; // Dikali dengan bobot kriteria
                    $nak += $nv; // Menambahkan ke nilai akhir kumulatif
                }
                $item->nilai_akhir = round($nak, 2);

                // Data batas nilai akhir dan predikat yang digunakan
                $nilai_akhir_data = NilaiAkhir::all();
                // Menentukan predikat sesuai logika yang telah disesuaikan
                $predikat_terpilih = 'Belum Dinilai';
                foreach ($nilai_akhir_data as $key => $na) {
                    if ($nak >= $na['batas_bawah'] && $nak <= $na['batas_atas']) {
                        $predikat_terpilih = $na['nilai_akhir'];
                        break;
                    }

                    if ($key > 0 && $nak < $na['batas_bawah'] && $nak > $nilai_akhir_data[$key - 1]['batas_atas']) {
                        $predikat_terpilih = $nilai_akhir_data[$key - 1]['nilai_akhir'];
                        break;
                    }
                }

                // Mengatur predikat dan kelas berdasarkan nilai akhir yang terpilih
                $item->predikat = $predikat_terpilih;
                $item->predikat_class = match ($predikat_terpilih) {
                    'Sangat Baik' => 'success',
                    'Baik' => 'info',
                    'Cukup' => 'primary',
                    'Kurang' => 'warning',
                    'Sangat Kurang' => 'danger',
                    default => 'secondary',
                };
            } else {
                // Jika belum dinilai, beri nilai akhir dan predikat default
                $item->nilai_akhir = null;
                $item->predikat = 'Belum Dinilai';
                $item->predikat_class = 'secondary';
            }

            // Tentukan rating perilaku kerja berdasarkan predikat dan pedoman
            if ($item->predikat == 'Sangat Baik') {
                $item->rating_perilaku_kerja = 'di atas ekspektasi';
            } elseif ($item->predikat == 'Baik') {
                $item->rating_perilaku_kerja = $nak >= 4.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
            } elseif ($item->predikat == 'Cukup') {
                $item->rating_perilaku_kerja = $nak >= 3.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
            } elseif ($item->predikat == 'Kurang') {
                $item->rating_perilaku_kerja = 'di bawah ekspektasi';
            } elseif ($item->predikat == 'Sangat Kurang') {
                $item->rating_perilaku_kerja = 'di bawah ekspektasi';
            } else {
                $item->rating_perilaku_kerja = 'Belum Dinilai';
            }

            // Menambahkan informasi jumlah penilaian yang telah diisi
            $item->diisi = $item->hasil_penilaian_guru->count();

            return $item;
        });

        // Menampilkan data ke view
        return view('guru.pages.riwayat_penilaian', compact('penilaian'));
    }

    public function detail_riwayat_penilaian($id)
    {
        $penilaian = PenilaianGuru::find($id);
        $kriteria = Kriteria::all();
        $anchor_hasil = [];
        $catatan = [];
        $berkas = [];

        // Inisialisasi ekspektasi dan umpan balik
        $ekspektasi = [];
        $umpan_balik = [];
        foreach ($penilaian->hasil_penilaian_guru as $value) {
            $data = [
                'anchor' => $value->anchor->id,
                'sub_kriteria_id' => $value->anchor->sub_kriteria_id,
                'dokumen' => json_decode($value->dokumen, true) ?? []
            ];
            $anchor_hasil[] = $value->anchor->id;
            $catatan[] = $value->catatan;
            $berkas[] = (object)$data;
        }

        // Mengambil ekspektasi dan umpan balik dari tinjauan
        foreach ($penilaian->tinjauan as $tinjauan) {
            $ekspektasi[$tinjauan->kriteria_id] = $tinjauan->ekspektasi_pimpinan;
            $umpan_balik[$tinjauan->kriteria_id] = $tinjauan->umpan_balik;
        }

        return view('guru.pages.detail_riwayat_penilaian', compact('penilaian', 'kriteria', 'anchor_hasil', 'catatan', 'berkas', 'ekspektasi', 'umpan_balik'));
    }

    public function detail_riwayat_penilaian_unchange($id)
    {
        $penilaian = PenilaianGuru::find($id);
        $kriteria = Kriteria::all();
        $anchor_hasil = [];
        $catatan = [];
        $ekspektasi = [];
        $umpan_balik = [];
        $berkas = [];
        foreach ($penilaian->hasil_penilaian_guru as $value) {
            $data = [
                'anchor' => $value->anchor->id,
                'sub_kriteria_id' => $value->anchor->sub_kriteria_id,
                'dokumen' => json_decode($value->dokumen, true) ?? []
            ];
            $anchor_hasil[] = $value->anchor->id;
            $catatan[] = $value->catatan;
            $berkas[] = (object)$data;
        }

        // Mengambil ekspektasi dan umpan balik dari tinjauan
        foreach ($penilaian->tinjauan as $tinjauan) {
            $ekspektasi[$tinjauan->kriteria_id] = $tinjauan->ekspektasi_pimpinan;
            $umpan_balik[$tinjauan->kriteria_id] = $tinjauan->umpan_balik;
        }
        return view('guru.pages.detail_riwayat_unchange', compact('penilaian', 'kriteria', 'anchor_hasil', 'catatan', 'berkas', 'ekspektasi', 'umpan_balik'));
    }

    public function update_penilaian(Request $request, $id)
    {

        $request->validate([
            'penilaian' => 'required|array|min:1',
            'nama_dokumen.*.*' => 'required',
            'dokumen.*.*' => 'required|file|max:2048',
        ]);

        $formulir = PenilaianGuru::find($id);

        if (!$formulir) {
            return redirect()->route('formulir')->with('error', 'Penilaian tidak ditemukan');
        }

        $hasil_penilaian = HasilPenilaianGuru::where('penilaian_guru_id', $id)->get();

        $index = 0;
        foreach ($request->penilaian as $key => $value) {
            $anchor = Anchor::find($value);
            $hasil_penilaian_old = HasilPenilaianGuru::where('penilaian_guru_id', $id)->whereRelation('anchor', 'sub_kriteria_id', $anchor->sub_kriteria_id)->first();
            $json_old = $hasil_penilaian_old?->dokumen ? json_decode($hasil_penilaian_old?->dokumen) : [];
            $json_old = $json_old ? collect($json_old) : collect();
            $json = [];

            if (isset($request->dokumen_old[$key])) {
                foreach ($json_old as $valueJson) {
                    $isExist = false;
                    foreach ($request->dokumen_old[$key] as $valueDokumenOld) {
                        if ($valueJson->path == $valueDokumenOld) {
                            $isExist = true;
                            break;
                        }
                    }
                    if ($isExist) {
                        $json[] = $valueJson;
                    } else {
                        Storage::delete($valueJson->path);
                    }
                }
            }

            if (isset($request->dokumen[$key])) {
                foreach ($request->dokumen[$key] as $keyDokumen => $valueDokumen) {
                    $json[] = [
                        'nama' => $request->nama_dokumen[$key][$keyDokumen],
                        'path' => $valueDokumen->store('dokumen', 'public'),
                    ];
                }
            }

            HasilPenilaianGuru::create([
                'penilaian_guru_id' => $formulir->id,
                'anchor_id' => $anchor->id,
                'catatan' => $hasil_penilaian_old?->catatan,
                'dokumen' => json_encode($json),
            ]);
            $index++;
        }

        foreach ($hasil_penilaian as $hp) {
            $hp->delete();
        }

        $formulir->status = $request->collect('penilaian')->count() >= 21 ? 'PENDING' : 'DRAFT';
        $formulir->update();

        return redirect()->route('riwayat_penilaian')->with('success', 'Penilaian berhasil disimpan');
    }

    public function download_dokumen($id)
    {
        $penilaian_guru = PenilaianGuru::find($id);
        $penilaian_guru_update = PenilaianGuru::find($id);

        if (!$penilaian_guru) return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian tidak ditemukan');
        if ($penilaian_guru->dokumen == null) {
            $kriteria = Kriteria::all();
            $nak = 0;
            $rating = [];
            $ekspektasi = [];
            $umpan_balik = [];

            foreach ($kriteria as $k) {
                $nv = 0;
                foreach ($k->subKriteria as $sk) {
                    foreach ($penilaian_guru->hasil_penilaian_guru as $h) {
                        if ($h->anchor->sub_kriteria_id == $sk->id) {
                            $nv += $h->anchor->bobot;
                            break;
                        }
                    }
                }
                $nv = $nv / $k->subKriteria->count();
                $nv = $nv * $k->bobot;
                $rating[] = round($nv, 2);
                $nak += $nv;

                $tinjauan = $penilaian_guru->tinjauan->where('kriteria_id', $k->id)->first();
                if ($tinjauan) {
                    $ekspektasi[] = $tinjauan->ekspektasi_pimpinan;
                    $umpan_balik[] = $tinjauan->umpan_balik;
                } else {
                    $ekspektasi[] = 'Tidak ada ekspektasi';
                    $umpan_balik[] = 'Tidak ada umpan balik';
                }
            }

            $penilaian_guru->nilai_akhir = round($nak, 2);
            $nilai_akhir = NilaiAkhir::all();
            foreach ($nilai_akhir as $na) {
                if ($nak >= $na->batas_bawah && $nak <= $na->batas_atas) {
                    $penilaian_guru->predikat = $na->nilai_akhir;
                    // Tentukan rating perilaku kerja berdasarkan predikat dan pedoman
                    if ($penilaian_guru->predikat == 'Sangat Baik') {
                        // Hasil kerja di atas ekspektasi & perilaku di atas ekspektasi
                        $penilaian_guru->rating_perilaku_kerja = 'di atas ekspektasi';
                    } elseif ($penilaian_guru->predikat == 'Baik') {
                        // Kombinasi hasil kerja dan perilaku sesuai/di atas ekspektasi
                        $penilaian_guru->rating_perilaku_kerja = $nak >= 4.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
                    } elseif ($penilaian_guru->predikat == 'Cukup') {
                        // Hasil kerja di bawah ekspektasi & perilaku sesuai/di atas ekspektasi
                        $penilaian_guru->rating_perilaku_kerja = $nak >= 3.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
                    } elseif ($penilaian_guru->predikat == 'Kurang') {
                        // Misconduct, perilaku di bawah ekspektasi
                        $penilaian_guru->rating_perilaku_kerja = 'di bawah ekspektasi';
                    } elseif ($penilaian_guru->predikat == 'Sangat Kurang') {
                        // Hasil kerja di bawah ekspektasi & perilaku di bawah ekspektasi
                        $penilaian_guru->rating_perilaku_kerja = 'di bawah ekspektasi';
                    }

                    break;
                }
            }

            Settings::setOutputEscapingEnabled(true);

            $template_processor = new TemplateProcessor(public_path('dokumen/penilaianpegawai.docx'));
            $template_processor->setValues([
                //detail penilaian
                'periodenilai' => $penilaian_guru->periode_penilaian->periode_penilaian,
                'tahun' => $penilaian_guru->tahun_penilaian->tahun_penilaian,
                'tglmulai' => $penilaian_guru->created_at->format('d-m-Y'),
                'tglakhir' => $penilaian_guru->updated_at->format('d-m-Y'),
                'catatan' => $penilaian_guru->catatan,
                'tglnilai' => $penilaian_guru->updated_at->format('d-m-Y'),

                //guru
                'namaguru' => $penilaian_guru->user->nama,
                'nipguru' => $penilaian_guru->user->username,
                'pangkatguru' => $penilaian_guru->user->pangkat,
                'jabatanguru' => $penilaian_guru->user->jabatan,
                'unitkerjaguru' => $penilaian_guru->user->unit_kerja,

                //kepsek
                'namakepsek' => $penilaian_guru->penilai->nama,
                'nipkepsek' => $penilaian_guru->penilai->username,
                'pangkatkepsek' => $penilaian_guru->penilai->pangkat,
                'jabatankepsek' => $penilaian_guru->penilai->jabatan,
                'unitkerjakepsek' => $penilaian_guru->penilai->unit_kerja,

                //nilai
                'ratingnilai1' => $rating[0],
                'ratingnilai2' => $rating[1],
                'ratingnilai3' => $rating[2],
                'ratingnilai4' => $rating[3],
                'ratingnilai5' => $rating[4],
                'ratingnilai6' => $rating[5],
                'ratingnilai7' => $rating[6],
                'rating_perilaku' => $penilaian_guru->rating_perilaku_kerja,
                'predikat' => $penilaian_guru->predikat . ' (' . $penilaian_guru->nilai_akhir . ')',

                // ekspektasi dan umpan balik
                'ekspektasi1' => $ekspektasi[0],
                'ekspektasi2' => $ekspektasi[1],
                'ekspektasi3' => $ekspektasi[2],
                'ekspektasi4' => $ekspektasi[3],
                'ekspektasi5' => $ekspektasi[4],
                'ekspektasi6' => $ekspektasi[5],
                'ekspektasi7' => $ekspektasi[6],
                'umpanbalik1' => $umpan_balik[0],
                'umpanbalik2' => $umpan_balik[1],
                'umpanbalik3' => $umpan_balik[2],
                'umpanbalik4' => $umpan_balik[3],
                'umpanbalik5' => $umpan_balik[4],
                'umpanbalik6' => $umpan_balik[5],
                'umpanbalik7' => $umpan_balik[6],
            ]);

            $docxPath = storage_path('app/public/hasil-penilaian/' . $penilaian_guru->id . '.docx');
            $template_processor->saveAs($docxPath);
            $penilaian_guru_update->dokumen = 'hasil-penilaian/' . $penilaian_guru->id . '.docx';

            //convert pdf
            ConvertApi::setApiSecret(env('CONVERT_API_SECRET', 'secret_nu4AOtFj2a0g1pUE'));
            $convert = ConvertApi::convert('pdf', ['File' => $docxPath]);
            $pdfPath = storage_path('app/public/hasil-penilaian/' . $penilaian_guru->id . '.pdf');
            $convert->getFile()->save($pdfPath);
            $penilaian_guru_update->dokumen_pdf = 'hasil-penilaian/' . $penilaian_guru->id . '.pdf';

            $penilaian_guru_update->update();

            return response()->download($pdfPath, $penilaian_guru->user->nama . '-' . $penilaian_guru->periode_penilaian->periode_penilaian . '-' . $penilaian_guru->tahun_penilaian->tahun_penilaian . '.pdf');
        }

        return response()->download(storage_path('app/public/' . $penilaian_guru->dokumen_pdf), $penilaian_guru->user->nama . '-' . $penilaian_guru->periode_penilaian->periode_penilaian . '-' . $penilaian_guru->tahun_penilaian->tahun_penilaian . '.pdf');
    }
}
