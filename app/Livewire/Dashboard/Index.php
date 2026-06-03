<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')] // Livewire akan bungkus ini
class Index extends Component
{
    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
