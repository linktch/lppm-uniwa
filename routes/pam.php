<?php

use App\Livewire\Kegiatan\Pam\Index as PamIndex;
use App\Livewire\Kegiatan\Pam\CapaianPembelajaran\Detailpenilaian as CPPAMPenilaianMahasiswa;
use App\Livewire\Kegiatan\Pam\CapaianPembelajaran\Index as CPPAMIndex;
use App\Livewire\Kegiatan\Pam\CapaianPembelajaran\Penilaian as CPPAMPenilaian;
use App\Livewire\Kegiatan\Pam\Kehadiran\Detail as KehadiranPAMDetail;
use App\Livewire\Kegiatan\Pam\Kehadiran\Index as KehadiranPAMIndex;
use App\Livewire\Kegiatan\Pam\Kehadiran\Rekap as KehadiranPAMRekap;
use App\Livewire\Kegiatan\Pam\Kelompok\Detail as KelompokPAMDetail;
use App\Livewire\Kegiatan\Pam\Kelompok\Index as KelompokPAMIndex;
use App\Livewire\Kegiatan\Pam\Laporanharian\Create as LAPHarPAMCreate;
use App\Livewire\Kegiatan\Pam\Laporanharian\Index as LAPHarPAMIndex;
use App\Livewire\Kegiatan\Pam\Laporanharian\Rekap as LAPHarPAMRekap;
use App\Livewire\Kegiatan\Pam\Laporanharian\Update as LAPHarPAMUpdate;
use App\Livewire\Kegiatan\Pam\Laporanharian\View as LAPHarPAMView;

// Define role pattern untuk PAM
$pamRoles = 'superadmin|prodi|mitra|mahasiswa|kemahasiswaan';

/*
 * |--------------------------------------------------------------------------
 * | PAM INDEX
 * |--------------------------------------------------------------------------
 */
Route::get('/{role}/kegiatan/PAM/index', PamIndex::class)
    ->where('role', $pamRoles)
    ->name('pam.index');

/*
 * |--------------------------------------------------------------------------
 * | KELOMPOK PAM
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/PAM/kelompok',
    'as' => 'kegiatan.pam.kelompok.',
    'where' => ['role' => $pamRoles]
], function () {
    Route::get('/', KelompokPAMIndex::class)->name('index');
    Route::get('/{kelompokID}', KelompokPAMDetail::class)->name('detail');
});

/*
 * |--------------------------------------------------------------------------
 * | CAPAIAN PEMBELAJARAN PAM
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/PAM/Capaian-Pembelajaran',
    'as' => 'kegiatan.pam.kelompok.pamcp.',
    'where' => ['role' => $pamRoles]
], function () {
    Route::get('/', CPPAMIndex::class)->name('index');
    Route::get('/penilaian', CPPAMPenilaian::class)->name('penilaian');
    Route::get('/penilaian/{mahasiswaID}', CPPAMPenilaianMahasiswa::class)->name('detailpenilaian');
});

/*
 * |--------------------------------------------------------------------------
 * | KEHADIRAN PAM
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/PAM/Kehadiran',
    'as' => 'kegiatan.pam.kehadiran.',
    'where' => ['role' => $pamRoles]
], function () {
    Route::get('/', KehadiranPAMIndex::class)->name('index');
    Route::get('/rekap', KehadiranPAMRekap::class)->name('rekap');
});

Route::get('/{role}/kegiatan/PAM/kehadiran-mahasiswa/{kelompokID}', KehadiranPAMDetail::class)
    ->where('role', $pamRoles)
    ->name('kegiatan.pam.kelompok.kehadiran.detail');

/*
 * |--------------------------------------------------------------------------
 * | LAPORAN HARIAN PAM
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/PAM/laporan-harian',
    'as' => 'kegiatan.pam.laporanharian.',
    'where' => ['role' => $pamRoles]
], function () {
    Route::get('/', LAPHarPAMIndex::class)->name('index');
    Route::get('/create', LAPHarPAMCreate::class)->name('create');
    Route::get('/rekap', LAPHarPAMRekap::class)->name('rekap');
    Route::get('/view/{lapharID}', LAPHarPAMView::class)->name('view');
    Route::get('/view/{lapharID}/update', LAPHarPAMUpdate::class)->name('update');
});