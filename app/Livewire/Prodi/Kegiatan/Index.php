<?php

namespace App\Livewire\Prodi\Kegiatan;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')] // Livewire akan bungkus ini
class Index extends Component
{
    public $jenis;

    // Mount menerima parameter dari URL
    public function mount($jenis)
    {
        $this->jenis = $jenis;
    }

    public function render()
    {
        return view('livewire.prodi.kegiatan.index');
    }
}