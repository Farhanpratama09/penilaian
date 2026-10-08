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
use App\Services\BarsCalculator;
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

        $tahun_sekarang = null;
        if ($request->has('year') && $request->year) {
            $tahun_sekarang = TahunPenilaian::where('tahun_penilaian', $request->year)->first();
        }
        if (!$tahun_sekarang) {
            $tahun_sekarang = TahunPenilaian::where('aktif', true)->latest()->first() ?: TahunPenilaian::latest()->first();
        }

        $periode_sekarang = PeriodePenilaian::where('id', $request->triwulan)->first() ?: PeriodePenilaian::first();

        // Initialize data arrays
        $data = [];
        $guru_names = [];

        if ($tahun_sekarang) {
            // Eager load penilaian dengan relasinya untuk menghindari N+1 query
            $penilaianList = PenilaianGuru::with(['hasil_penilaian_guru.anchor'])
                ->where('tahun_penilaian_id', $tahun_sekarang->id)
                ->where('status', 'SELESAI')
                ->get()
                ->groupBy(function ($item) {
                    return $item->periode_penilaian_id . '_' . $item->user_id;
                });

            foreach ($periode as $p) {
                foreach ($guru as $g) {
                    $key = $p->id . '_' . $g->id;
                    $penilaian_guru = $penilaianList->get($key)?->first();

                    $nilai = 0.0;
                    if ($penilaian_guru) {
                        $calc = BarsCalculator::calculate($penilaian_guru);
                        $nilai = $calc['nilai_akhir'];
                    }

                    $data[$p->periode_penilaian][$g->nama] = $nilai;
                    $guru_names[$g->nama] = $g->nama;
                }
            }
        }

        // Convert $guru_names array to a simple indexed array
        $guru_names = array_values($guru_names);

        // Prepare chart data
        $chartData = [];
        foreach ($data as $pName => $nilaiPerGuru) {
            $chartData[] = [
                'name' => $pName,
                'data' => array_values($nilaiPerGuru),
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
        $formulir = PenilaianGuru::with(['penilai', 'hasil_penilaian_guru.anchor', 'user'])->where('status', 'SELESAI')
            ->whereHas('penilai', function ($query) {
                $query->where('role', 2); // Memastikan penilai adalah Kepala Sekolah
            });
        if (request('user_id')) {
            $formulir = $formulir->where('user_id', request('user_id'));
        }

        $formulir = $formulir->orderBy('id', 'desc')->get();

        $formulir = $formulir->map(function ($item) {
            $calc = BarsCalculator::calculate($item);
            $item->nilai_akhir = $calc['nilai_akhir'];
            $item->predikat = $calc['predikat'];
            $item->predikat_class = $calc['predikat_class'];
            $item->rating_perilaku_kerja = $calc['rating_perilaku_kerja'];
            return $item;
        });
        return view('kepsek.pages.formulir_selesai', compact('formulir'));
    }

    public function review_formulir_penilaian($id)
    {
        $penilaian = PenilaianGuru::with('hasil_penilaian_guru.anchor')->where('id', $id)->first();
        if (!$penilaian) {
            return redirect()->route('formulir_pending')->withErrors(['Penilaian tidak ditemukan']);
        }
        $kriteria = Kriteria::all();
        $berkas = [];
        $anchor_hasil = [];
        $catatan = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            if (!$value->anchor) continue;
            $data = [
                'anchor' => $value->anchor->id,
                'sub_kriteria_id' => $value->anchor->sub_kriteria_id,
                'dokumen' => json_decode($value->dokumen, true) ?? []
            ];
            $berkas[] = (object)$data;
            $anchor_hasil[] = $value->anchor->id;
            $catatan[] = $value->catatan;
        }

        return view('kepsek.pages.review_formulir_penilaian', compact('penilaian', 'kriteria', 'anchor_hasil', 'berkas', 'catatan'));
    }

    public function accept_formulir(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|array|min:1',
            'catatan' => 'nullable|array',
            'ekspektasi_pimpinan' => 'nullable|array',
            'umpan_balik' => 'nullable|array',
        ]);

        $formulir = PenilaianGuru::find($id);

        if (!$formulir) {
            return redirect()->route('formulir_pending')->with('error', 'Penilaian tidak ditemukan');
        }

        $hasil_penilaian = HasilPenilaianGuru::where('penilaian_guru_id', $id)->get();

        foreach ($request->penilaian as $key => $value) {
            $anchor = Anchor::find($value);
            if (!$anchor) continue;
            $hasil_penilaian_old = HasilPenilaianGuru::where('penilaian_guru_id', $id)
                ->whereRelation('anchor', 'sub_kriteria_id', $anchor->sub_kriteria_id)
                ->first();

            HasilPenilaianGuru::create([
                'penilaian_guru_id' => $formulir->id,
                'anchor_id' => $anchor->id,
                'dokumen' => $hasil_penilaian_old?->dokumen,
                'catatan' => $hasil_penilaian_old?->catatan,
            ]);
        }

        foreach ($hasil_penilaian as $hp) {
            $hp->delete();
        }

        $formulir->status = "SELESAI";
        $formulir->penilai_id = auth()->user()->id;
        $formulir->update();

        // Simpan ekspektasi dan umpan balik ke tabel Tinjauan
        if ($request->has('ekspektasi_pimpinan') && is_array($request->ekspektasi_pimpinan)) {
            foreach ($request->ekspektasi_pimpinan as $kriteria_id => $ekspektasi) {
                $umpanBalik = $request->umpan_balik[$kriteria_id] ?? null;

                if ($umpanBalik === 'Lainnya' && isset($request->umpan_balik_lainnya[$kriteria_id])) {
                    $umpanBalik = $request->umpan_balik_lainnya[$kriteria_id];
                }

                Tinjauan::updateOrCreate(
                    [
                        'penilaian_guru_id' => $formulir->id,
                        'kriteria_id' => $kriteria_id,
                    ],
                    [
                        'ekspektasi_pimpinan' => $ekspektasi,
                        'umpan_balik' => $umpanBalik,
                    ]
                );
            }
        }

        if ($request->has('catatan') && is_array($request->catatan)) {
            foreach ($request->catatan as $key => $value) {
                $anchorOld = Anchor::find($key);
                if ($anchorOld) {
                    $formulir->hasil_penilaian_guru()
                        ->whereRelation('anchor', 'sub_kriteria_id', $anchorOld->sub_kriteria_id)
                        ->update(['catatan' => $value]);
                }
            }
        }

        return redirect()->route('formulir_selesai')->with('success', 'Formulir berhasil diterima');
    }

    public function detail_riwayat_penilaian_selesai($id)
    {
        $penilaian = PenilaianGuru::with('hasil_penilaian_guru.anchor', 'tinjauan')->where('id', $id)->first();
        if (!$penilaian) {
            return redirect()->route('formulir_selesai')->withErrors(['Penilaian tidak ditemukan']);
        }
        $kriteria = Kriteria::all();
        $berkas = [];
        $anchor_hasil = [];
        $catatan = [];

        // Inisialisasi ekspektasi dan umpan balik
        $ekspektasi = [];
        $umpan_balik = [];

        foreach ($penilaian->hasil_penilaian_guru as $value) {
            if (!$value->anchor) continue;
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
        if (!$guru) {
            return redirect()->route('guru')->withErrors(['Guru tidak ditemukan']);
        }

        $penilaian_guru_pending = PenilaianGuru::where('user_id', $guru->id)->where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('user_id', $guru->id)->where('status', 'SELESAI')->count();
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
                    ->where('user_id', $guru->id)
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
        return view('kepsek.pages.detail_guru', compact('penilaian_guru_pending', 'penilaian_guru_selesai', 'tahun', 'data', 'label', 'tahun_sekarang', 'guru'));
    }
}
