<?php

namespace App\Livewire\Mitra\Kegiatan;

use Livewire\Component;
use Livewire\Attributes\Layout;

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
        return view('livewire.mitra.kegiatan.index');
    }
}
