<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Guidelines extends Component
{
    #[Layout('layouts.app')]
    #[Title('Halaman Profil')]
    public function render()
    {
        return view('livewire.guidelines');
    }
}
