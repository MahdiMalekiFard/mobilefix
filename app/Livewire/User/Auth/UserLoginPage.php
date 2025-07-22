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
            'password' => 'required|string|min:8',
        ];
    }

    public function login()
    {
        $this->validate();
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            return $this->redirect(route('user.dashboard'), true);
        }
        // add error validation for field email
        $this->addError('email', 'ایمیل یا رمز عبور اشتباه است');
    }


    public function render()
    {
        return view('livewire.user.auth.user-login-page')
            ->layout('components.layouts.user_auth');
    }
}
