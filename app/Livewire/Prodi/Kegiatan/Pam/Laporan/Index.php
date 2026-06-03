<?php

namespace App\Livewire\Prodi\Kegiatan\Pam\Laporan;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')] // Livewire akan bungkus ini
class Index extends Component
{
    
    public function render()
    {
        return view('livewire.prodi.kegiatan.pam.laporan.index');
    }
}