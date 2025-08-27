<?php

namespace App\Livewire\User\Auth;

use Livewire\Component;

class UserLoginPage extends Component
{
    public $email;
    public $password;

    protected function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    // Validate email in real-time while typing
    public function updatedEmail()
    {
        $this->resetErrorBag('email');
        
        if ( ! empty($this->email)) {
            $this->validateOnly('email');
        }
    }

    // Validate password in real-time while typing
    public function updatedPassword()
    {
        $this->resetErrorBag('password');
        
        if ( ! empty($this->password)) {
            $this->validateOnly('password');
        }
    }

    public function login()
    {
        $this->validate();
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            return $this->redirect(route('user.dashboard'));
        }
        // add error validation for field email
        $this->addError('email', 'Email or password is incorrect');
    }

    public function render()
    {
        return view('livewire.user.auth.user-login-page')
            ->layout('components.layouts.user_auth');
    }
}
