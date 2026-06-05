<?php

namespace App\Livewire\Kegiatan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class Index extends Component
{
    public $role;
    public $jenisKegiatan;
    
    public function mount($role, $jenisKegiatan)
    {
        $this->role = $role;
        $this->jenisKegiatan = $jenisKegiatan;
    }
    
    public function render()
    {
        // Jika jenis kegiatan KKN, return view menu KKN
        if ($this->jenisKegiatan == 'KKN') {
            return view('livewire.menu.menukkn', [
                'role' => $this->role,
                'jenisKegiatan' => $this->jenisKegiatan,
            ]);
        }
        
        // Default view untuk jenis kegiatan lain (PKL, PMM)
        return view('livewire.kegiatan.index', [
            'role' => $this->role,
            'jenisKegiatan' => $this->jenisKegiatan,
        ]);
    }
}