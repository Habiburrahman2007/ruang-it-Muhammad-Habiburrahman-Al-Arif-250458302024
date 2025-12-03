<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    #[Layout('layouts.auth')]
    #[Title('Login Page')]

    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            $this->addError('email', 'Email atau password salah.');
            $this->dispatch('loginFailed');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
