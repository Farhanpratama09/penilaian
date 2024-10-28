<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anchor;
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

class KepsekController extends Controller
{
    public function dashboard_kepsek(Request $request)
    {
        $akun_guru = User::where('role', '3')->count();
        $guru = User::where('role', '3')->get();
        $penilaian_guru_pending = PenilaianGuru::where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('status', 'SELESAI')->count();
        $kriteria_pen = Kriteria::count();
        $kriteria = Kriteria::all();

        $tahun = TahunPenilaian::all();
        $periode = PeriodePenilaian::all();

        $tahun_sekarang = TahunPenilaian::where('aktif', true)->latest()->first();
        if ($request->has('year')) {
            $tahun_sekarang = TahunPenilaian::where('tahun_penilaian', $request->year)->first();
        }

        $periode_sekarang = PeriodePenilaian::where('id', $request->triwulan)->first() ?: PeriodePenilaian::first();

        // Initialize data arrays
        $data = [];
        $guru_names = [];

        // Loop through all periods and gurus
        foreach ($periode as $p) {
            foreach (User::where('role', 3)->get() as $g) {
                $penilaian_guru = PenilaianGuru::where('user_id', $g->id)
                    ->where('periode_penilaian_id', $p->id)
                    ->where('tahun_penilaian_id', $tahun_sekarang->id)
                    ->where('status', 'SELESAI')
                    ->first();

                $nilai = 0;
                if ($penilaian_guru) {
                    foreach (Kriteria::all() as $l) {
                        $nilai_kriteria = 0;
                        foreach ($l->subKriteria as $sub_kriteria) {
                            $hasil_penilaian = HasilPenilaianGuru::where('penilaian_guru_id', $penilaian_guru->id)
                                ->whereRelation('anchor', 'sub_kriteria_id', $sub_kriteria->id)
                                ->first();
                            $nilai_sub_kriteria = $hasil_penilaian?->anchor->bobot;
                            $nilai_kriteria += $nilai_sub_kriteria ?? 0;
                        }
                        $nilai_kriteria = $nilai_kriteria / $l->subKriteria->count();
                        $nilai_kriteria = $nilai_kriteria * $l->bobot;
                        $nilai += $nilai_kriteria;
                    }
                }

                $data[$p->periode_penilaian][$g->nama] = $nilai;
                $guru_names[$g->nama] = $g->nama; // Store unique names only
            }
        }

        // Convert $guru_names array to a simple indexed array
        $guru_names = array_values($guru_names);

        // Prepare chart data
        $chartData = [];
        foreach ($data as $periode => $nilai) {
            $chartData[] = [
                'name' => $periode,
                'data' => array_values($nilai),
            ];
        }

        return view('kepsek.pages.dashboard', compact('akun_guru', 'guru', 'penilaian_guru_pending', 'penilaian_guru_selesai', 'kriteria_pen', 'kriteria', 'tahun', 'periode', 'tahun_sekarang', 'periode_sekarang', 'chartData', 'guru_names'));
    }

    public function profil()
    {
        return view('kepsek.pages.profil');
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

        if ($validator->fails()) return redirect()->route('kepsek_profil')->withErrors($validator->errors()->all());

        $user = User::find(auth()->user()->id);

        if ($request->password_lama && $request->password_baru) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->route('kepsek_profil')->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }
            $user->password = bcrypt($request->password_baru);
        }

        $user->nama = $request->nama;
        $user->pangkat = $request->pangkat;
        $user->jabatan = $request->jabatan;
        $user->unit_kerja = $request->unit_kerja;
        $user->update();

        return redirect()->route('kepsek_profil')->with('success', 'Profil berhasil diubah');
    }

    public function formulir_pending()
    {
        $formulir = PenilaianGuru::where('status', 'PENDING');
        if (request('user_id')) {
            $formulir = $formulir->where('user_id', request('user_id'));
        }
        $formulir = $formulir->get();
        return view('kepsek.pages.formulir_pending', compact('formulir'));
    }

    public function formulir_selesai()
    {
        $formulir = PenilaianGuru::with('penilai')->where('status', 'SELESAI')
            ->whereHas('penilai', function ($query) {
                $query->where('role', 2); // Memastikan penilai adalah Kepala Sekolah
            });
        if (request('user_id')) {
            $formulir = $formulir->where('user_id', request('user_id'));
        }

        $formulir = $formulir->orderBy('id', 'desc')->get();

        $formulir = $formulir->map(function ($item) {
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
                $nv = $nv / $k->subKriteria->count();
                $nv = $nv * $k->bobot;
                $nak += $nv;
            }
            $item->nilai_akhir = round($nak, 2);
            $nilai_akhir = NilaiAkhir::all();


            $predikat_terpilih = 'Tidak Diketahui';
            foreach ($nilai_akhir as $key => $na) {
                // Cek apakah nilai akhir berada dalam batas bawah dan batas atas
                if ($nak >= $na['batas_bawah'] && $nak <= $na['batas_atas']) {
                    $predikat_terpilih = $na['nilai_akhir'];
                    break;
                }

                // Jika nilai akhir lebih rendah dari batas bawah dan tidak ada predikat sebelumnya, ambil predikat ini
                if ($key > 0 && $nak < $na['batas_bawah'] && $nak > $nilai_akhir[$key - 1]['batas_atas']) {
                    $predikat_terpilih = $nilai_akhir[$key - 1]['nilai_akhir'];
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
            // Tentukan rating perilaku kerja berdasarkan predikat dan pedoman
            if ($item->predikat == 'Sangat Baik') {
                // Hasil kerja di atas ekspektasi & perilaku di atas ekspektasi
                $item->rating_perilaku_kerja = 'di atas ekspektasi';
            } elseif ($item->predikat == 'Baik') {
                // Kombinasi hasil kerja dan perilaku sesuai/di atas ekspektasi
                $item->rating_perilaku_kerja = $nak >= 4.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
            } elseif ($item->predikat == 'Cukup') {
                // Hasil kerja di bawah ekspektasi & perilaku sesuai/di atas ekspektasi
                $item->rating_perilaku_kerja = $nak >= 3.0 ? 'di atas ekspektasi' : 'sesuai ekspektasi';
            } elseif ($item->predikat == 'Kurang') {
                // Misconduct, perilaku di bawah ekspektasi
                $item->rating_perilaku_kerja = 'di bawah ekspektasi';
            } elseif ($item->predikat == 'Sangat Kurang') {
                // Hasil kerja di bawah ekspektasi & perilaku di bawah ekspektasi
                $item->rating_perilaku_kerja = 'di bawah ekspektasi';
            }

            return $item;
        });
        return view('kepsek.pages.formulir_selesai', compact('formulir'));
    }

    public function review_formulir_penilaian($id)
    {
        $penilaian = PenilaianGuru::where('id', $id)->first();
        $kriteria = Kriteria::all();
        $berkas = [];
        $anchor_hasil = [];
        $catatan = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            $data = [
                'anchor' => $value->anchor->id,
                'sub_kriteria_id' => $value->anchor->sub_kriteria_id,
                'dokumen' => json_decode($value->dokumen, true) ?? []
            ];
            $berkas[] = (object)$data;
            $anchor_hasil[] = $value->anchor->id;
            $catatan[] = $value->catatan;
        };

        return view('kepsek.pages.review_formulir_penilaian', compact('penilaian', 'kriteria', 'anchor_hasil', 'berkas', 'catatan'));
    }

    public function accept_formulir(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|array|min:1',
            'nama_dokumen.*.*' => 'required',
            'dokumen.*.*' => 'required|file|max:2048',
            'catatan' => 'array',
            'ekspektasi_pimpinan' => 'array', // Ekspektasi untuk setiap kriteria
            'umpan_balik' => 'array',         // Umpan balik untuk setiap kriteria
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

            HasilPenilaianGuru::create([
                'penilaian_guru_id' => $formulir->id,
                'anchor_id' => $anchor->id,
                'dokumen' => $hasil_penilaian_old?->dokumen,
                'catatan' => $hasil_penilaian_old?->catatan,
            ]);
            $index++;
        }

        foreach ($hasil_penilaian as $hp) {
            $hp->delete();
        }

        $formulir->status = "SELESAI";
        $formulir->penilai_id = auth()->user()->id;
        $formulir->update();

        $formulirfb = PenilaianGuru::find($id);

        // Simpan ekspektasi dan umpan balik ke tabel Tinjauan
        foreach ($request->ekspektasi_pimpinan as $kriteria_id => $ekspektasi) {
            $umpanBalik = $request->umpan_balik[$kriteria_id] ?? null;

            // Cek apakah umpan balik adalah "Lainnya" dan ambil nilainya dari textarea
            if ($umpanBalik === 'Lainnya' && isset($request->umpan_balik_lainnya[$kriteria_id])) {
                $umpanBalik = $request->umpan_balik_lainnya[$kriteria_id];
            }

            Tinjauan::updateOrCreate(
                [
                    'penilaian_guru_id' => $formulirfb->id,
                    'kriteria_id' => $kriteria_id,
                ],
                [
                    'ekspektasi_pimpinan' => $ekspektasi,
                    'umpan_balik' => $umpanBalik,
                ]
            );
        }

        $penilaian_guru = PenilaianGuru::find($id);

        foreach ($request->catatan as $key => $value) {
            $penilaian_guru->hasil_penilaian_guru()->whereRelation('anchor', 'sub_kriteria_id', Anchor::find($key)->sub_kriteria_id)->update(['catatan' => $value]);
        }


        return redirect()->route('formulir_selesai')->with('success', 'Formulir berhasil diterima');
    }

    public function detail_riwayat_penilaian_selesai($id)
    {
        $penilaian = PenilaianGuru::with('hasil_penilaian_guru', 'tinjauan')->where('id', $id)->first();
        $kriteria = Kriteria::all();
        $berkas = [];
        $anchor_hasil = [];
        $catatan = [];

        // Inisialisasi ekspektasi dan umpan balik
        $ekspektasi = [];
        $umpan_balik = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            $data = [
                'anchor' => $value->anchor->id,
                'sub_kriteria_id' => $value->anchor->sub_kriteria_id,
                'dokumen' => json_decode($value->dokumen, true) ?? []
            ];
            $berkas[] = (object)$data;
            $anchor_hasil[] = $value->anchor->id;
            $catatan[] = $value->catatan;
        }

        // Mengambil ekspektasi dan umpan balik dari tinjauan
        foreach ($penilaian->tinjauan as $tinjauan) {
            $ekspektasi[$tinjauan->kriteria_id] = $tinjauan->ekspektasi_pimpinan;
            $umpan_balik[$tinjauan->kriteria_id] = $tinjauan->umpan_balik;
        }

        return view('kepsek.pages.detail_riwayat_penilaian_selesai', compact('penilaian', 'kriteria', 'anchor_hasil', 'catatan', 'berkas', 'ekspektasi', 'umpan_balik'));
    }


    public function add_catatan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'array',
        ]);

        $penilaian_guru = PenilaianGuru::find($id);


        if (!$penilaian_guru) return redirect()->route('formulir_pending')->withErrors(['Penilaian tidak ditemukan']);

        foreach ($request->catatan as $key => $value) {
            $penilaian_guru->hasil_penilaian_guru()->where('anchor_id', $key)->update(['catatan' => $value]);
        }

        return redirect()->route('formulir_pending')->with('success', 'Catatan berhasil ditambahkan');
    }

    public function guru()
    {
        $guru = User::where('role', '3')->get();
        return view('kepsek.pages.guru', compact('guru'));
    }

    public function detail_guru($id)
    {
        $guru = User::where('id', $id)->first();
        $penilaian_guru_pending = PenilaianGuru::where('user_id', $guru->id)->where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('user_id', $guru->id)->where('status', 'SELESAI')->count();
        $tahun = TahunPenilaian::all();
        $label = Kriteria::all();
        $periode = PeriodePenilaian::all();
        $tahun_sekarang = TahunPenilaian::where('aktif', true)->latest()->first();
        if (request('year')) {
            $tahun_sekarang = TahunPenilaian::where('tahun_penilaian', request('year'))->first();
        }
        $data = [];
        foreach ($periode as $p) {
            $penilaian_guru = PenilaianGuru::where('user_id', $guru->id)->where('periode_penilaian_id', $p->id)->where('tahun_penilaian_id', $tahun_sekarang->id)->where('status', 'SELESAI')->first();
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
        return view('kepsek.pages.detail_guru', compact('penilaian_guru_pending', 'penilaian_guru_selesai', 'tahun', 'data', 'label', 'tahun_sekarang', 'guru'));
    }
}
