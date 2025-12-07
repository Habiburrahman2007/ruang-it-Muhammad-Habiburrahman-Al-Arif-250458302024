<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    #[Layout('layouts.auth')]
    #[Title('Register Page')]

    public $name;
    public $email;
    public $password;
    public $profession;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'profession' => 'required|max:50'
    ];

    public function register()
    {
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'profession' => $this->profession,
        ]);
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
