<?php

namespace App\Http\Controllers;

use App\Models\Anchor;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\PenilaianGuru;
use App\Models\TahunPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\HasilPenilaianGuru;
use App\Models\Tinjauan;
use App\Services\BarsCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function dashboard_guru()
    {
        $penilaian_guru_pending = PenilaianGuru::where('user_id', auth()->user()->id)->where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('user_id', auth()->user()->id)->where('status', 'SELESAI')->count();
        $tahun = TahunPenilaian::all();
        $label = Kriteria::with('subKriteria')->get();
        $periode = PeriodePenilaian::all();

        $tahun_sekarang = null;
        if (request('year')) {
            $tahun_sekarang = TahunPenilaian::where('tahun_penilaian', request('year'))->first();
        }
        if (!$tahun_sekarang) {
            $tahun_sekarang = TahunPenilaian::where('aktif', true)->latest()->first() ?: TahunPenilaian::latest()->first();
        }

        $data = [];
        if ($tahun_sekarang) {
            foreach ($periode as $p) {
                $penilaian_guru = PenilaianGuru::with('hasil_penilaian_guru.anchor')
                    ->where('user_id', auth()->user()->id)
                    ->where('periode_penilaian_id', $p->id)
                    ->where('tahun_penilaian_id', $tahun_sekarang->id)
                    ->where('status', 'SELESAI')
                    ->first();

                $nama = $p->periode_penilaian;
                $nilai = [];
                if ($penilaian_guru) {
                    foreach ($label as $l) {
                        $subCount = $l->subKriteria->count();
                        $sumBobot = 0.0;
                        if ($subCount > 0) {
                            foreach ($l->subKriteria as $sub_kriteria) {
                                foreach ($penilaian_guru->hasil_penilaian_guru as $h) {
                                    if ($h->anchor && $h->anchor->sub_kriteria_id == $sub_kriteria->id) {
                                        $sumBobot += (float) $h->anchor->bobot;
                                        break;
                                    }
                                }
                            }
                            $nilaiKriteria = round($sumBobot / $subCount, 2);
                        } else {
                            $nilaiKriteria = 0.0;
                        }
                        $nilai[] = $nilaiKriteria;
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
        $kriteria = Kriteria::with(['subKriteria.anchor'])->get();

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

        return view('guru.pages.formulir_tahun', compact('tahun', 'periode', 'kriteria'));
    }

    public function hasil_formulir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|exists:tahun_penilaians,id',
            'periode' => 'required|exists:periode_penilaians,id',
            'penilaian' => 'required|array|min:1',
            'nama_dokumen.*.*' => 'nullable|string|max:255',
            'dokumen.*.*' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:2048',
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

        $totalSubKriteria = SubKriteria::count();
        $isComplete = $request->collect('penilaian')->count() >= $totalSubKriteria;

        $formulir = PenilaianGuru::create([
            'user_id' => auth()->user()->id,
            'tahun_penilaian_id' => $request->tahun,
            'periode_penilaian_id' => $request->periode,
            'status' => $isComplete ? 'PENDING' : 'DRAFT',
        ]);

        foreach ($request->penilaian as $key => $value) {
            $anchor = Anchor::find($value);
            if (!$anchor) continue;
            $json = [];
            if (isset($request->dokumen[$key])) {
                foreach ($request->dokumen[$key] as $keyDokumen => $valueDokumen) {
                    $docName = $request->nama_dokumen[$key][$keyDokumen] ?? $valueDokumen->getClientOriginalName();
                    $json[] = [
                        'nama' => $docName,
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

        return redirect()->route('riwayat_penilaian')->with('success', 'Penilaian berhasil disimpan');
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
        $penilaianQuery = PenilaianGuru::with(['hasil_penilaian_guru.anchor', 'tahun_penilaian', 'periode_penilaian'])
            ->where('user_id', auth()->user()->id);

        // Filter status penilaian
        if (request('status') == 'pending') {
            $penilaianQuery->where('status', 'PENDING');
        } else if (request('status') == 'selesai') {
            $penilaianQuery->where('status', 'SELESAI');
        }

        // Mengambil data penilaian dan melakukan pemetaan
        $penilaian = $penilaianQuery->latest()->get()->map(function ($item) {
            if ($item->status === 'SELESAI') {
                $calc = BarsCalculator::calculate($item);
                $item->nilai_akhir = $calc['nilai_akhir'];
                $item->predikat = $calc['predikat'];
                $item->predikat_class = $calc['predikat_class'];
                $item->rating_perilaku_kerja = $calc['rating_perilaku_kerja'];
            } else {
                $item->nilai_akhir = null;
                $item->predikat = 'Belum Dinilai';
                $item->predikat_class = 'secondary';
                $item->rating_perilaku_kerja = 'Belum Dinilai';
            }

            // Menambahkan informasi jumlah penilaian yang telah diisi
            $item->diisi = $item->hasil_penilaian_guru->count();

            return $item;
        });

        return view('guru.pages.riwayat_penilaian', compact('penilaian'));
    }

    public function detail_riwayat_penilaian($id)
    {
        $penilaian = PenilaianGuru::with(['hasil_penilaian_guru.anchor', 'tinjauan', 'tahun_penilaian', 'periode_penilaian'])->find($id);

        if (!$penilaian) {
            return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian tidak ditemukan');
        }

        // Validasi kepemilikan penilaian (mencegah IDOR)
        if ($penilaian->user_id !== auth()->id() && auth()->user()->role != 2 && auth()->user()->role != 1) {
            abort(403, 'Akses tidak diizinkan');
        }

        $kriteria = Kriteria::with(['subKriteria.anchor'])->get();
        $anchor_hasil = [];
        $catatan = [];
        $berkas = [];
        $ekspektasi = [];
        $umpan_balik = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            if (!$value->anchor) continue;
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
        $penilaian = PenilaianGuru::with(['hasil_penilaian_guru.anchor', 'tinjauan', 'tahun_penilaian', 'periode_penilaian'])->find($id);

        if (!$penilaian) {
            return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian tidak ditemukan');
        }

        // Validasi kepemilikan penilaian (mencegah IDOR)
        if ($penilaian->user_id !== auth()->id() && auth()->user()->role != 2 && auth()->user()->role != 1) {
            abort(403, 'Akses tidak diizinkan');
        }

        $kriteria = Kriteria::with(['subKriteria.anchor'])->get();
        $anchor_hasil = [];
        $catatan = [];
        $ekspektasi = [];
        $umpan_balik = [];
        $berkas = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            if (!$value->anchor) continue;
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
            'nama_dokumen.*.*' => 'nullable|string|max:255',
            'dokumen.*.*' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:2048',
        ]);

        $formulir = PenilaianGuru::find($id);

        if (!$formulir) {
            return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian tidak ditemukan');
        }

        // Validasi kepemilikan (mencegah IDOR)
        if ($formulir->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan');
        }

        // Kunci jika status sudah SELESAI
        if ($formulir->status === 'SELESAI') {
            return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian yang telah selesai tidak dapat diubah kembali.');
        }

        $hasil_penilaian = HasilPenilaianGuru::where('penilaian_guru_id', $id)->get();

        foreach ($request->penilaian as $key => $value) {
            $anchor = Anchor::find($value);
            if (!$anchor) continue;

            $hasil_penilaian_old = HasilPenilaianGuru::where('penilaian_guru_id', $id)
                ->whereRelation('anchor', 'sub_kriteria_id', $anchor->sub_kriteria_id)
                ->first();

            $json_old = $hasil_penilaian_old?->dokumen ? json_decode($hasil_penilaian_old->dokumen) : [];
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
                        Storage::disk('public')->delete($valueJson->path);
                    }
                }
            } else {
                foreach ($json_old as $valueJson) {
                    Storage::disk('public')->delete($valueJson->path);
                }
            }

            if (isset($request->dokumen[$key])) {
                foreach ($request->dokumen[$key] as $keyDokumen => $valueDokumen) {
                    $docName = $request->nama_dokumen[$key][$keyDokumen] ?? $valueDokumen->getClientOriginalName();
                    $json[] = [
                        'nama' => $docName,
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
        }

        foreach ($hasil_penilaian as $hp) {
            $hp->delete();
        }

        $totalSubKriteria = SubKriteria::count();
        $formulir->status = $request->collect('penilaian')->count() >= $totalSubKriteria ? 'PENDING' : 'DRAFT';
        $formulir->update();

        return redirect()->route('riwayat_penilaian')->with('success', 'Penilaian berhasil disimpan');
    }

    public function download_dokumen($id)
    {
        $penilaian_guru = PenilaianGuru::with([
            'user',
            'penilai',
            'tahun_penilaian',
            'periode_penilaian',
            'hasil_penilaian_guru.anchor',
            'tinjauan'
        ])->find($id);

        if (!$penilaian_guru) {
            return redirect()->route('riwayat_penilaian')->with('error', 'Penilaian tidak ditemukan');
        }

        // Cek otorisasi untuk mencegah IDOR
        if ($penilaian_guru->user_id !== auth()->id() && auth()->user()->role != 2 && auth()->user()->role != 1) {
            abort(403, 'Akses tidak diizinkan');
        }

        // Hitung skor BARS menggunakan service kalkulator terstandar
        $calc = BarsCalculator::calculate($penilaian_guru);
        $penilaian_guru->nilai_akhir = $calc['nilai_akhir'];
        $penilaian_guru->predikat = $calc['predikat'];
        $penilaian_guru->rating_perilaku_kerja = $calc['rating_perilaku_kerja'];

        // Siapkan data per kriteria untuk template PDF
        $kriteriaList = Kriteria::all();
        $kriteria_data = [];

        foreach ($kriteriaList as $k) {
            $tinjauan = $penilaian_guru->tinjauan->where('kriteria_id', $k->id)->first();
            $skorMurni = $calc['kriteria_scores'][$k->id] ?? 0.0;

            $kriteria_data[] = [
                'kriteria' => $k->kriteria,
                'bobot' => (float) $k->bobot,
                'skor' => $skorMurni,
                'ekspektasi' => $tinjauan?->ekspektasi_pimpinan ?? 'Belum ada ekspektasi pimpinan',
                'umpan_balik' => $tinjauan?->umpan_balik ?? 'Belum ada umpan balik',
            ];
        }

        $fileName = 'Laporan-Penilaian-' . ($penilaian_guru->user->nama ?? 'Guru') . '-' .
            ($penilaian_guru->periode_penilaian->periode_penilaian ?? 'Periode') . '-' .
            ($penilaian_guru->tahun_penilaian->tahun_penilaian ?? 'Tahun') . '.pdf';

        $pdf = Pdf::loadView('pdf.laporan_penilaian', [
            'penilaian' => $penilaian_guru,
            'kriteria_data' => $kriteria_data,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }
}
