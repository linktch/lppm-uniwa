<?php

namespace App\Livewire\Screening;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.screening.index');
    }
}
