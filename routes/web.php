<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\NotificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware('guest');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/logout', [AuthController::class, 'get_logout'])->middleware('auth');

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('dashboard-admin', [AdminController::class, 'dashboard_admin'])->name('dashboard_admin');
    Route::get('kriteria-penilaian', [AdminController::class, 'kriteria_penilaian'])->name('kriteria_penilain');
    Route::get('akun-guru', [AdminController::class, 'akun_guru'])->name('akun_guru');
    Route::get('akun-kepsek', [AdminController::class, 'akun_kepsek'])->name('akun_kepsek');
    Route::post('create-guru', [AdminController::class, 'create_guru'])->name('create_guru');
    Route::post('create-kepsek', [AdminController::class, 'create_kepsek'])->name('create_kepsek');
    Route::delete('delete-user/{id}', [AdminController::class, 'delete_user'])->name('delete_user');
    Route::put('edit-user/{id}', [AdminController::class, 'edit_user'])->name('edit_user');
    Route::get('detail-kriteria/{id}', [AdminController::class, 'detail_kriteria'])->name('detail_kriteria');
    Route::put('edit-anchor/{id}', [AdminController::class, 'edit_anchor'])->name('edit_anchor');
    Route::get('profil', [AdminController::class, 'profil'])->name('admin_profil');
    Route::put('edit-profil', [AdminController::class, 'edit_profil'])->name('edit_profil');
    Route::get('tahun-penilaian', [AdminController::class, 'tahun_penilaian'])->name('tahun_penilaian');
    Route::post('tambah-tahun', [AdminController::class, 'tambah_tahun'])->name('tambah_tahun');
    Route::delete('delete-tahun/{id}', [AdminController::class, 'delete_tahun'])->name('delete_tahun');
    Route::put('edit-tahun/{id}', [AdminController::class, 'edit_tahun'])->name('edit_tahun');
    Route::get('tahun-periode', [AdminController::class, 'periode_penilaian'])->name('periode_penilaian');
    Route::post('tambah-periode', [AdminController::class, 'tambah_periode'])->name('tambah_periode');
    Route::delete('delete-periode/{id}', [AdminController::class, 'delete_periode'])->name('delete_periode');
    Route::put('edit-periode/{id}', [AdminController::class, 'edit_periode'])->name('edit_periode');
    Route::put('update-kriteria/{id}', [AdminController::class, 'update_kriteria'])->name('update_kriteria');
});

Route::middleware(['auth', 'isKepsek'])->prefix('kepsek')->group(function () {
    Route::get('dashboard-kepsek', [KepsekController::class, 'dashboard_kepsek'])->name('dashboard_kepsek');
    Route::get('formulir-selesai', [KepsekController::class, 'formulir_selesai'])->name('formulir_selesai');
    Route::get('formulir-pending', [KepsekController::class, 'formulir_pending'])->name('formulir_pending');
    Route::put('terima-formulir/{id}', [KepsekController::class, 'terima_formulir'])->name('terima_formulir');
    Route::get('profil', [KepsekController::class, 'profil'])->name('kepsek_profil');
    Route::put('edit-profil', [KepsekController::class, 'edit_profil'])->name('edit_profil_kepsek');
    Route::get('review-formulir-penilaian/{id}', [KepsekController::class, 'review_formulir_penilaian'])->name('review_formulir_penilaian');
    Route::put('accept-formulir/{id}', [KepsekController::class, 'accept_formulir'])->name('accept_formulir');
    Route::get('detail-riwayat-penilaian-selesai/{id}', [KepsekController::class, 'detail_riwayat_penilaian_selesai'])->name('detail_riwayat_penilaian_selesai');
    // Route::put('catatan/{id}', [KepsekController::class, 'add_catatan'])->name('catatan');
    Route::get('guru', [KepsekController::class, 'guru'])->name('guru');
    Route::get('guru/{id}', [KepsekController::class, 'detail_guru'])->name('detail_guru');
});

Route::middleware(['auth', 'isGuru'])->prefix('guru')->group(function () {
    Route::get('dashboard-guru', [GuruController::class, 'dashboard_guru'])->name('dashboard_guru');
    Route::get('formulir', [GuruController::class, 'formulir'])->name('formulir');
    Route::post('formulir-tahun', [GuruController::class, 'formulir_tahun'])->name('formulir_tahun');
    Route::post('hasil-formulir', [GuruController::class, 'hasil_formulir'])->name('hasil_formulir');
    Route::get('profil', [GuruController::class, 'profil'])->name('guru_profil');
    Route::put('edit-profil', [GuruController::class, 'edit_profil'])->name('edit_profil_guru');
    Route::get('riwayat-penilaian', [GuruController::class, 'riwayat_penilaian'])->name('riwayat_penilaian');
    Route::get('riwayat-penilaian/{id}', [GuruController::class, 'detail_riwayat_penilaian'])->name('detail_riwayat_penilaian');
    Route::get('riwayat-penilaian-unchange/{id}', [GuruController::class, 'detail_riwayat_penilaian_unchange'])->name('detail_riwayat_penilaian_unchange');
    Route::put('update-penilaian/{id}', [GuruController::class, 'update_penilaian'])->name('update_penilaian');
    Route::get('download-dokumen/{id}', [GuruController::class, 'download_dokumen'])->name('download_dokumen');
});
