<?php

namespace App\Livewire\Kegiatan;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
        switch ($this->jenisKegiatan) {
            case 'KKN':
                return view('livewire.menu.menukkn', [
                    'role' => $this->role,
                    'jenisKegiatan' => $this->jenisKegiatan,
                ]);
            case 'PKM':
                return view('livewire.menu.menupkm', [
                    'role' => $this->role,
                    'jenisKegiatan' => $this->jenisKegiatan,
                ]);
            default:
                return view('livewire.kegiatan.index', [
                    'role' => $this->role,
                    'jenisKegiatan' => $this->jenisKegiatan,
                ]);
        }
    }
}
