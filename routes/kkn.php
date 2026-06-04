<?php

use App\Http\Controllers\ScreeningPdfController;
use App\Livewire\Kegiatan\Kkn\Index as KKNIndex;
use App\Livewire\Kegiatan\Kkn\Kelompok\Index as KelompokKKNIndex;
use App\Livewire\Kegiatan\Kkn\Kelompok\Detail as DetailKelompokKKNIndex;
use App\Livewire\Kegiatan\Kkn\Kelompok\Add as AddKelompokKKNIndex;
use App\Livewire\Kegiatan\Kkn\Screening\Index as ScreeningKKNIndex;
use App\Livewire\Kegiatan\Kkn\Screening\Hafalan\Index as ScreeningHafalanKKNIndex;
use App\Livewire\Kegiatan\Kkn\Screening\Hafalan\Penilaian as ScreeningHafalanKKNPenilaian;
use App\Livewire\Kegiatan\Kkn\Screening\Hafalan\Detail as ScreeningHafalanKKNDetail;
use App\Livewire\Kegiatan\Kkn\Screening\Kesehatan\Index as ScreeningKesehatanKKNIndex; // TAMBAHKAN
use App\Livewire\Kegiatan\Kkn\Screening\Dokumen\Index as ScreeningDokumenKKNIndex; // TAMBAHKAN
use App\Livewire\Kegiatan\Kkn\Screening\Validasidoc as KKNValidasidoc;
use App\Livewire\Kegiatan\Kkn\Kknreg\Index as RegKKNIndex;
use App\Livewire\Kegiatan\Kkn\Timeline\Index as TimelineKKNIndex;
use App\Livewire\Kegiatan\Kkn\Laporanharian\Index as LaporanHarianKKNIndex;
use App\Livewire\Kegiatan\Kkn\Laporanharian\Create as LaporanHarianKKNCreate;
use App\Livewire\Kegiatan\Kkn\Laporanharian\View as LaporanHarianKKNView;
use App\Livewire\Kegiatan\Kkn\Laporanharian\Update as LaporanHarianKKNUpdate;

use App\Livewire\Kegiatan\Kkn\Dokumen\Index as DokumenKKNIndex;

// Define role pattern untuk KKN
$kknRoles = 'superadmin|prodi|mitra|mahasiswa|kemahasiswaan|dosen';
$kknAdminRoles = 'superadmin|prodi|mitra|kemahasiswaan|dosen';

/*
 * |--------------------------------------------------------------------------
 * | KKN INDEX
 * |--------------------------------------------------------------------------
 */
Route::get('/{role}/kegiatan/KKN/index', KKNIndex::class)
    ->where('role', $kknRoles)
    ->name('kkn.index');

/*
 * |--------------------------------------------------------------------------
 * | KELOMPOK KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/kelompok',
    'as' => 'kegiatan.kkn.kelompok.',
    'where' => ['role' => $kknAdminRoles]
], function () {
    Route::get('/', KelompokKKNIndex::class)->name('index');
    Route::get('/{kelompokID}', DetailKelompokKKNIndex::class)->name('detail');
    Route::get('/{kelompokID}/tambah-anggota', AddKelompokKKNIndex::class)->name('tambah-anggota');
});

/*
 * |--------------------------------------------------------------------------
 * | SCREENING KKN (MAIN)
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/screening',
    'as' => 'kegiatan.kkn.screening.',
    'where' => ['role' => $kknAdminRoles]
], function () {
    Route::get('/', ScreeningKKNIndex::class)->name('index');
    Route::get('/validasi-dokumen', KKNValidasidoc::class)->name('validasidoc');
});

/*
 * |--------------------------------------------------------------------------
 * | SCREENING KESEHATAN KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/screening/kesehatan',
    'as' => 'kegiatan.kkn.screening.kesehatan.',
    'where' => ['role' => $kknAdminRoles]
], function () {
    Route::get('/', ScreeningKesehatanKKNIndex::class)->name('index');
});

/*
 * |--------------------------------------------------------------------------
 * | SCREENING DOKUMEN KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/screening/dokumen',
    'as' => 'kegiatan.kkn.screening.dokumen.',
    'where' => ['role' => $kknAdminRoles]
], function () {
    Route::get('/', ScreeningDokumenKKNIndex::class)->name('index');
});

/*
 * |--------------------------------------------------------------------------
 * | SCREENING HAFALAN KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/screening/hafalan',
    'as' => 'kegiatan.kkn.screening.hafalan.',
    'where' => ['role' => $kknAdminRoles]  // PERBAIKAN: ganti dari $kknRoles ke $kknAdminRoles
], function () {
    Route::get('/', ScreeningHafalanKKNIndex::class)->name('index');
    Route::get('/penilaian', ScreeningHafalanKKNPenilaian::class)->name('penilaian');
    Route::get('/detail/{mahasiswaId}', ScreeningHafalanKKNDetail::class)->name('penilaian.detail');
});

/*
 * |--------------------------------------------------------------------------
 * | PENDAFTARAN KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/pendaftaran-KKN',
    'as' => 'kegiatan.kkn.registration.',
    'where' => ['role' => $kknRoles]
], function () {
    Route::get('/', RegKKNIndex::class)->name('index');
});

/*
 * |--------------------------------------------------------------------------
 * | TIMELINE KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/timeline',
    'as' => 'kegiatan.kkn.timeline.',
    'where' => ['role' => $kknRoles]
], function () {
    Route::get('/', TimelineKKNIndex::class)->name('index');
});


/*
 * |--------------------------------------------------------------------------
 * | DOKUMEN SAYA KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/dokumen',
    'as' => 'kegiatan.kkn.dokumen.',
    'where' => ['role' => $kknRoles]
], function () {
    Route::get('/', DokumenKKNIndex::class)->name('index');
});

/*
 * |--------------------------------------------------------------------------
 * | LAPORAN HARIAN KKN
 * |--------------------------------------------------------------------------
 */
Route::group([
    'prefix' => '{role}/kegiatan/KKN/laporan-harian',
    'as' => 'kegiatan.kkn.laporanharian.',
    'where' => ['role' => $kknRoles]
], function () {
    Route::get('/', LaporanHarianKKNIndex::class)->name('index');
    Route::get('/create', LaporanHarianKKNCreate::class)->name('create');
    Route::get('/{id}', LaporanHarianKKNView::class)
            ->name('view');
    Route::get('/{id}/update', LaporanHarianKKNUpdate::class)
            ->name('update');
});

/*
 * |--------------------------------------------------------------------------
 * | SCREENING PDF
 * |--------------------------------------------------------------------------
 */
Route::get('/kkn/screening/pdf', [ScreeningPdfController::class, 'index'])
    ->name('screening.pdf');