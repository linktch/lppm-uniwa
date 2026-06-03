<?php

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard as TestDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Index as Dashboard;

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

    // Include route modules
    require __DIR__ . '/superadmin.php';
    require __DIR__ . '/pam.php';
    require __DIR__ . '/kkn.php';
});