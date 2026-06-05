<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Kegiatan\Index as KegiatanIndex;
use App\Livewire\Kelompok\{Index as KelompokIndex, Detail as KelompokDetail, Add as KelompokAdd};
use App\Livewire\Laporanharian\{Index as LaporanharianIndex, Create as LaporanharianCreate, View as LaporanharianView, Update as LaporanharianUpdate};
use App\Livewire\Screening\Hafalan\{Index as HafalanIndex, Penilaian as HafalanPenilaian, Detail as HafalanDetail};
use App\Livewire\Screening\{Index as ScreeningIndex};
use App\Livewire\Timeline\Index as TimelineIndex;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | GUEST
 * |--------------------------------------------------------------------------
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/', fn() => redirect()->route('dashboard'));

    Route::get('/captcha', function () {
        $builder = new CaptchaBuilder;
        $builder->build();
        session(['captcha_phrase' => $builder->getPhrase()]);
        return response($builder->output())->header('Content-Type', 'image/jpeg');
    });
});

/*
 * |--------------------------------------------------------------------------
 * | AUTH
 * |--------------------------------------------------------------------------
 */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // ==================== SUPERADMIN ROUTES ====================
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/user', function () {
            return view('welcome');
        })->name('user.index');

        Route::get('/periode', function () {
            return view('welcome');
        })->name('periode.index');

        Route::get('/pejabat', function () {
            return view('welcome');
        })->name('pejabat.index');
    });

    // ==================== KKN ROUTES ====================
    $kknRoles = 'superadmin|prodi|mitra|mahasiswa|kemahasiswaan|dosen';
    $kknAdminRoles = 'superadmin|prodi|mitra|kemahasiswaan|dosen';

    // KEGIATAN INDEX
    Route::get('/{role}/kegiatan/{jenisKegiatan}/index', KegiatanIndex::class)
        ->where('role', $kknRoles)
        ->where('jenisKegiatan', 'KKN|PKL|PMM')
        ->name('kegiatan.index');

    // TIMELINE KEGIATAN
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/timeline',
        'as' => 'kegiatan.timeline.',
        'where' => ['role' => $kknRoles, 'jenisKegiatan' => 'KKN|PKL|PMM']
    ], function () {
        Route::get('/', TimelineIndex::class)->name('index');
    });

    // Kelompok Routes
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/kelompok',
        'as' => 'kegiatan.kelompok.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PMM']
    ], function () {
        Route::get('/', KelompokIndex::class)->name('index');
        Route::get('/{kelompokID}', KelompokDetail::class)->name('detail');
        Route::get('/{kelompokID}/tambah-anggota', KelompokAdd::class)->name('tambah-anggota');
    });
    // Kelompok Routes
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/screening',
        'as' => 'kegiatan.kelompok.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PMM']
    ], function () {
        Route::get('/', ScreeningIndex::class)->name('index');
    });

    /*
     * |--------------------------------------------------------------------------
     * | SCREENING HAFALAN KKN
     * |--------------------------------------------------------------------------
     */
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/screening/hafalan',
        'as' => 'kegiatan.screening.hafalan.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PMM']
    ], function () {
        Route::get('/', HafalanIndex::class)->name('index');
        Route::get('/penilaian', HafalanPenilaian::class)->name('penilaian');
        Route::get('/detail/{mahasiswaId}', HafalanDetail::class)->name('detail');  // TAMBAHKAN ROUTE DETAIL
    });

    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/laporan-harian',
        'as' => 'kegiatan.laporanharian.',
        'where' => ['role' => $kknRoles, 'jenisKegiatan' => 'KKN|PKL|PMM']
    ], function () {
        Route::get('/', LaporanharianIndex::class)->name('index');
        Route::get('/create', LaporanharianCreate::class)->name('create');
        Route::get('/{id}', LaporanharianView::class)->name('view');  // ROUTE INI
            Route::get('/{id}/update', LaporanharianUpdate::class)->name('update'); // ROUTE INI

    });
});

require __DIR__ . '/kkn.php';
