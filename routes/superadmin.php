<?php

use App\Livewire\Superadmin\Periode\Index as PeriodeIndex;
use App\Livewire\Superadmin\Pejabat\Index as PejabatIndex;
use App\Livewire\Superadmin\User\Index as UserIndex;

Route::group([
    'prefix' => 'superadmin',
    'as' => 'superadmin.',
    'middleware' => 'role:superadmin'
], function () {
    Route::get('/user/index', UserIndex::class)->name('user.index');
    Route::get('/periode/index', PeriodeIndex::class)->name('periode.index');
    Route::get('/pejabat/index', PejabatIndex::class)->name('pejabat.index');
});