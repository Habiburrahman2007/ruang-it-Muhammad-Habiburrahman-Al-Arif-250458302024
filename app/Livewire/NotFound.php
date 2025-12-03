<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class NotFound extends Component
{
    #[Layout('layouts.auth')]
    #[Title('Not Found')]
    public function render()
    {
        return view('livewire.not-found');
    }
}
