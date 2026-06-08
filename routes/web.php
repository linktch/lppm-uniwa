<?php

use App\Http\Controllers\SertifikatController;
use App\Livewire\Auth\Login;
use App\Livewire\Berkas\{Index as BerkasIndex};
use App\Livewire\Berkassaya\{Index as BerkasSaya};
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Kegiatan\Index as KegiatanIndex;
use App\Livewire\Kelompok\{Index as KelompokIndex, Detail as KelompokDetail, Add as KelompokAdd};
use App\Livewire\Laporanharian\{Index as LaporanharianIndex, Create as LaporanharianCreate, View as LaporanharianView, Update as LaporanharianUpdate};
use App\Livewire\Laporanrekap\{Index as LaporanRekapIndex};
use App\Livewire\Pendaftaran\{Index as PendaftaranIndex};
use App\Livewire\Profile\{Index as ProfileIndex};
use App\Livewire\Screening\Hafalan\{Index as HafalanIndex, Penilaian as HafalanPenilaian, Detail as HafalanDetail};
use App\Livewire\Screening\{Index as ScreeningIndex};
use App\Livewire\Superadmin\Pejabat\Index as PejabatIndex;
use App\Livewire\Superadmin\Periode\Index as PeriodeIndex;
use App\Livewire\Superadmin\User\Index as UserIndex;
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
    Route::get('/profile', ProfileIndex::class)->name('profile');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    Route::group([
        'prefix' => 'superadmin',
        'as' => 'superadmin.',
        'middleware' => 'role:superadmin'
    ], function () {
        Route::get('/user/index', UserIndex::class)->name('user.index');
        Route::get('/periode/index', PeriodeIndex::class)->name('periode.index');
        Route::get('/pejabat/index', PejabatIndex::class)->name('pejabat.index');
    });

    // ==================== KKN ROUTES ====================
    $kknRoles = 'superadmin|prodi|mitra|mahasiswa|kemahasiswaan|dosen';
    $kknAdminRoles = 'superadmin|prodi|mitra|kemahasiswaan|dosen';

    // KEGIATAN INDEX
    Route::get('/{role}/kegiatan/{jenisKegiatan}/index', KegiatanIndex::class)
        ->where('role', $kknRoles)
        ->where('jenisKegiatan', 'KKN|PKL|PKM|PAM')
        ->name('kegiatan.index');

    // TIMELINE KEGIATAN
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/timeline',
        'as' => 'kegiatan.timeline.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
    ], function () {
        Route::get('/', TimelineIndex::class)->name('index');
    });

    // Kelompok Routes
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/kelompok',
        'as' => 'kegiatan.kelompok.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
    ], function () {
        Route::get('/', KelompokIndex::class)->name('index');
        Route::get('/{kelompokID}', KelompokDetail::class)->name('detail');
        Route::get('/{kelompokID}/tambah-anggota', KelompokAdd::class)->name('tambah-anggota');
    });
    // Kelompok Routes
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/screening',
        'as' => 'kegiatan.kelompok.',
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
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
        'where' => ['role' => $kknAdminRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
    ], function () {
        Route::get('/', HafalanIndex::class)->name('index');
        Route::get('/penilaian', HafalanPenilaian::class)->name('penilaian');
        Route::get('/detail/{mahasiswaId}', HafalanDetail::class)->name('detail');  // TAMBAHKAN ROUTE DETAIL
    });

    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/laporan-harian',
        'as' => 'kegiatan.laporanharian.',
        'where' => ['role' => $kknRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
    ], function () {
        Route::get('/', LaporanharianIndex::class)->name('index');
        Route::get('/create', LaporanharianCreate::class)->name('create');
        Route::get('/{id}', LaporanharianView::class)->name('view');  // ROUTE INI
        Route::get('/{id}/update', LaporanharianUpdate::class)->name('update');  // ROUTE INI
    });

    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/berkas',
        'as' => 'kegiatan.berkas.',
        'where' => ['role' => $kknRoles, 'jenisKegiatan' => 'KKN|PKL|PKM']
    ], function () {
        Route::get('/', BerkasIndex::class)->name('index');
    });

    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/rekap-laporan',
        'as' => 'kegiatan.rekap-laporan.',
        'where' => ['role' => 'superadmin|dosen|prodi|mahasiswa|kemahasiswaan', 'jenisKegiatan' => 'KKN|PKL|PKM'],
    ], function () {
        Route::get('/', LaporanRekapIndex::class)->name('index');
    });

    // ==================== ROUTE PENDAFTARAN ====================
    // Route untuk pendaftaran kegiatan (KKN, PKM, PAM)
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/pendaftaran',
        'as' => 'kegiatan.pendaftaran.',
        'where' => ['role' => 'superadmin|dosen|prodi|mahasiswa|kemahasiswaan', 'jenisKegiatan' => 'KKN|PKM|PAM'],
        'middleware' => ['auth']
    ], function () {
        Route::get('/', PendaftaranIndex::class)->name('index');
    });

    /*
     * |--------------------------------------------------------------------------
     * | Route untuk Berkas Saya
     * |--------------------------------------------------------------------------
     */
    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/berkas-saya',
        'as' => 'kegiatan.berkassaya.',
        'where' => [
            'role' => 'superadmin|dosen|prodi|mahasiswa|kemahasiswaan',
            'jenisKegiatan' => 'KKN|PKM|PAM'
        ],
        'middleware' => ['auth']
    ], function () {
        Route::get('/', BerkasSaya::class)->name('index');
    });

    Route::group([
        'prefix' => '{role}/kegiatan/{jenisKegiatan}/sertifikat',
        'as' => 'kegiatan.sertifikat.',
        'where' => [
            'role' => 'superadmin|dosen|prodi|mahasiswa|kemahasiswaan',
            'jenisKegiatan' => 'KKN|PKM|PAM'
        ],
        'middleware' => ['auth']
    ], function () {
        // Preview sertifikat (tampil di browser)
        Route::get('/{mahasiswaId}', [SertifikatController::class, 'preview'])->name('preview');

        // Download sertifikat PDF
        Route::get('/{mahasiswaId}/download', [SertifikatController::class, 'download'])->name('download');
    });
});

require __DIR__ . '/kkn.php';
