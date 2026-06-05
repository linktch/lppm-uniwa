<?php

use App\Http\Controllers\ScreeningPdfController;


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

use App\Livewire\Kegiatan\Kkn\Laporanharian\Index as LaporanHarianKKNIndex;
use App\Livewire\Kegiatan\Kkn\Laporanharian\Create as LaporanHarianKKNCreate;
use App\Livewire\Kegiatan\Kkn\Laporanharian\View as LaporanHarianKKNView;
use App\Livewire\Kegiatan\Kkn\Laporanharian\Update as LaporanHarianKKNUpdate;

use App\Livewire\Kegiatan\Kkn\Dokumen\Index as DokumenKKNIndex;

use App\Http\Controllers\SertifikatController;


// Define role pattern untuk KKN
$kknRoles = 'superadmin|prodi|mitra|mahasiswa|kemahasiswaan|dosen';
$kknAdminRoles = 'superadmin|prodi|mitra|kemahasiswaan|dosen';




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
    // Route::get('/', ScreeningKKNIndex::class)->name('index');
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

    Route::get('/sertifikat-preview/{mahasiswaId}', [SertifikatController::class, 'preview'])
        ->name('sertifikat.preview');

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
// Route::group([
//     'prefix' => '{role}/kegiatan/KKN/laporan-harian',
//     'as' => 'kegiatan.kkn.laporanharian.',
//     'where' => ['role' => $kknRoles]
// ], function () {
//     Route::get('/', LaporanHarianKKNIndex::class)->name('index');
//     Route::get('/create', LaporanHarianKKNCreate::class)->name('create');
//     Route::get('/{id}', LaporanHarianKKNView::class)
//             ->name('view');
//     Route::get('/{id}/update', LaporanHarianKKNUpdate::class)
//             ->name('update');
// });

/*
 * |--------------------------------------------------------------------------
 * | SCREENING PDF
 * |--------------------------------------------------------------------------
 */
Route::get('/kkn/screening/pdf', [ScreeningPdfController::class, 'index'])
    ->name('screening.pdf');