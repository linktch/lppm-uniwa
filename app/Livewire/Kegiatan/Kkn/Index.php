<?php

namespace App\Livewire\Kegiatan\Kkn;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.kegiatan.kkn.index');
    }
}
