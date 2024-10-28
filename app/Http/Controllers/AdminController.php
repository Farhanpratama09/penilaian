<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anchor;
use App\Models\Kriteria; { {
    }
}

use App\Models\NilaiAkhir;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\PenilaianGuru;
use App\Models\TahunPenilaian;
use App\Models\PeriodePenilaian;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard_admin()
    {
        $akun_guru = User::where('role', '3')->count();
        $nama_guru = User::where('role', '3')->get();
        $penilaian_guru_pending = PenilaianGuru::where('status', 'PENDING')->count();
        $penilaian_guru_selesai = PenilaianGuru::where('status', 'SELESAI')->count();
        $kriteria_pen = Kriteria::count();
        $kriteria = Kriteria::all();
        $formulir = PenilaianGuru::where('status', 'PENDING');
        if (request('user_id')) {
            $formulir = $formulir->where('user_id', request('user_id'));
        }
        $formulir = $formulir->orderBy('id', 'desc')->get();

        $formulirsel = PenilaianGuru::where('status', 'SELESAI');
        if (request('user_id')) {
            $formulirsel = $formulirsel->where('user_id', request('user_id'));
        }
        $formulirsel = $formulirsel->orderBy('id', 'desc')->get();
        $formulirsel = $formulirsel->map(function ($item) {
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
            foreach ($nilai_akhir as $na) {
                if ($nak >= $na->batas_bawah && $nak <= $na->batas_atas) {
                    $item->predikat = $na->nilai_akhir;
                    break;
                }
            }
            return $item;
        });

        return view('admin.pages.dashboard', compact('akun_guru', 'nama_guru', 'penilaian_guru_pending',  'penilaian_guru_selesai', 'kriteria_pen', 'kriteria', 'formulir', 'formulirsel'));
    }

    public function kriteria_penilaian()
    {
        $kriteria = Kriteria::all();
        return view('admin.pages.kriteria_penilaian', compact('kriteria'));
    }

    public function update_kriteria(Request $request, $id)
    {
        $request->validate([
            'kriteria' => 'required',
            'bobot' => 'required|numeric|min:0',
        ]);

        $kriteria = Kriteria::find($id);

        if (!$kriteria) return back()->withErrors(['Kriteria tidak ditemukan']);

        $bobotBaru = $request->bobot;

        // Calculate the total bobot excluding the current kriteria
        $totalBobotLain = Kriteria::where('id', '!=', $id)->sum('bobot');

        // Check if the new bobot is valid
        if (($totalBobotLain + $bobotBaru) > 1) {
            return back()->withErrors(['bobot' => 'Total bobot tidak boleh lebih dari 100%']);
        }
        $kriteria->kriteria = $request->kriteria;
        $kriteria->bobot = $bobotBaru;
        $kriteria->update();

        return back()->with('success', 'Kriteria berhasil diubah');
    }


    public function akun_guru()
    {
        $guru = User::where('role', '3')->get();
        return view('admin.pages.akun_guru', compact('guru'));
    }

    public function akun_kepsek()
    {
        $kepsek = User::where('role', '2')->get();
        return view('admin.pages.akun_kepsek', compact('kepsek'));
    }

    public function create_guru(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'pangkat' => $request->pangkat,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => '3',
        ]);

        return redirect()->back()->with('success', 'Akun Guru Berhasil Dibuat');
    }

    public function create_kepsek(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'pangkat' => $request->pangkat,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'role' => '2',
        ]);

        return redirect()->back()->with('success', 'Akun Guru Berhasil Dibuat');
    }

    public function edit_user(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'password' => 'nullable|min:5',
        ]);

        $user = User::find($id);

        if (!$user) return back()->withErrors(['User tidak ditemukan']);

        try {
            $user->nama = $request->nama;
            $user->username = $request->username;
            $user->pangkat = $request->pangkat;
            $user->jabatan = $request->jabatan;
            $user->unit_kerja = $request->unit_kerja;
            if ($request->password) $user->password = bcrypt($request->password);
            $user->update();
        } catch (\Throwable $th) {
            return back()->withErrors(['username' => 'Username sudah digunakan']);
        }

        return back()->with('success', 'Data berhasil diubah');
    }

    public function delete_user($id)
    {
        $user = User::find($id);

        if (!$user) return back()->withErrors(['User tidak ditemukan']);

        $user->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function detail_kriteria($id)
    {
        $kriteria = Kriteria::find($id);

        if (!$kriteria) return back()->withErrors(['Kriteria tidak ditemukan']);

        return view('admin.pages.detail_kriteria', compact('kriteria'));
    }

    public function delete_sub_kriteria($id)
    {
        $sub_kriteria = SubKriteria::find($id);
        $anchor = Anchor::where('sub_kriteria_id', $id)->get();

        if ($sub_kriteria == null  && count($anchor) == 0) return back()->withErrors(['Sub kriteria dan anchor tidak ditemukan']);

        $sub_kriteria->delete();
        $anchor->each->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function edit_anchor(Request $request, $id)
    {
        $sub_kriteria = SubKriteria::find($id);

        $request->validate([
            'anchor' => 'required|array',
            'bobot' => 'required|array',
            'anchor_id' => 'required|array',
        ]);

        if (!$sub_kriteria) return back()->withErrors(['Sub kriteria tidak ditemukan']);

        foreach ($request->anchor as $key => $anchor) {
            Anchor::find($request->anchor_id[$key])->update([
                'anchor' => $anchor,
            ]);
        }

        return back()->with('success', 'Data berhasil diubah');
    }

    public function profil()
    {
        return view('admin.pages.profil');
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

        if ($validator->fails()) return redirect()->route('admin_profil')->withErrors($validator->errors()->all());

        $user = User::find(auth()->user()->id);

        if ($request->password_lama && $request->password_baru) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->route('admin_profil')->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }
            $user->password = bcrypt($request->password_baru);
        }

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->update();

        return redirect()->route('admin_profil')->with('success', 'Profil berhasil diubah');
    }

    public function tahun_penilaian()
    {
        $tahun = TahunPenilaian::all();
        return view('admin.pages.tahun', compact('tahun'));
    }

    public function tambah_tahun(Request $request)
    {
        $request->validate([
            'tahun_penilaian' => 'required|unique:tahun_penilaians,tahun_penilaian',
            'aktif' => 'required|in:0,1',
        ]);

        TahunPenilaian::create([
            'tahun_penilaian' => $request->tahun_penilaian,
            'aktif' => $request->aktif,
        ]);

        return redirect()->back()->with('success', 'Tahun penilaian berhasil ditambahkan');
    }

    public function edit_tahun(Request $request, $id)
    {
        $request->validate([
            'tahun_penilaian' => 'required|unique:tahun_penilaians,tahun_penilaian,' . $id . ',id',
            'aktif' => 'required|in:0,1',
        ]);

        $tahun = TahunPenilaian::find($id);

        if (!$tahun) return back()->withErrors(['Tahun penilaian tidak ditemukan']);

        if ($tahun->aktif == 1 && $request->aktif == 0) {
            $penilaian_guru = PenilaianGuru::where('tahun_penilaian_id', $id)->get();
            foreach ($penilaian_guru as $pg) {
                if ($pg->status !== "SELESAI") {
                    $sub_kriteria_belum_terisi = SubKriteria::all();
                    $pg->hasil_penilaian_guru()->delete();
                    foreach ($sub_kriteria_belum_terisi as $sk) {
                        $pg->hasil_penilaian_guru()->create([
                            'anchor_id' => $sk->anchor()->orderBy('bobot')->first()->id
                        ]);
                    }

                    $pg->update([
                        'status' => 'SELESAI',
                        'penilai_id' => User::where('role', 2)->first()->id
                    ]);
                }
            }
        }

        $tahun->tahun_penilaian = $request->tahun_penilaian;
        $tahun->aktif = $request->aktif;
        $tahun->update();

        return back()->with('success', 'Tahun penilaian berhasil diubah');
    }

    public function delete_tahun($id)
    {
        $tahun = TahunPenilaian::find($id);

        if (!$tahun) return back()->withErrors(['Tahun penilaian tidak ditemukan']);

        $tahun->delete();

        return back()->with('success', 'Tahun penilaian berhasil dihapus');
    }

    public function periode_penilaian()
    {
        $periode = PeriodePenilaian::all();
        return view('admin.pages.periode', compact('periode'));
    }

    public function tambah_periode(Request $request)
    {
        $request->validate([
            'periode_penilaian' => 'required|unique:periode_penilaians,periode_penilaian',
            'aktif' => 'required|in:0,1',
        ]);

        PeriodePenilaian::create([
            'periode_penilaian' => $request->periode_penilaian,
            'aktif' => $request->aktif,
        ]);

        return redirect()->back()->with('success', 'periode penilaian berhasil ditambahkan');
    }

    public function edit_periode(Request $request, $id)
    {
        $request->validate([
            'periode_penilaian' => 'required|unique:periode_penilaians,periode_penilaian,' . $id . ',id',
            'aktif' => 'required|in:0,1',
        ]);

        $periode = PeriodePenilaian::find($id);

        if (!$periode) return back()->withErrors(['periode penilaian tidak ditemukan']);

        if ($periode->aktif == 1 && $request->aktif == 0) {
            $penilaian_guru = PenilaianGuru::where('periode_penilaian_id', $id)->get();
            foreach ($penilaian_guru as $pg) {
                if ($pg->status !== "SELESAI") {
                    $sub_kriteria_belum_terisi = SubKriteria::all();
                    $pg->hasil_penilaian_guru()->delete();
                    foreach ($sub_kriteria_belum_terisi as $sk) {
                        $pg->hasil_penilaian_guru()->create([
                            'anchor_id' => $sk->anchor()->orderBy('bobot')->first()->id
                        ]);
                    }

                    $pg->update([
                        'status' => 'SELESAI',
                        'penilai_id' => User::where('role', 2)->first()->id
                    ]);
                }
            }
        }

        $periode->periode_penilaian = $request->periode_penilaian;
        $periode->aktif = $request->aktif;
        $periode->update();

        return back()->with('success', 'periode penilaian berhasil diubah');
    }

    public function delete_periode($id)
    {
        $periode = PeriodePenilaian::find($id);

        if (!$periode) return back()->withErrors(['periode penilaian tidak ditemukan']);

        $periode->delete();

        return back()->with('success', 'periode penilaian berhasil dihapus');
    }
}
