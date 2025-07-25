<?php

namespace App\Livewire\Admin\Auth;

use App\Models\User;
use Illuminate\View\View;

use Livewire\Component;

class LoginPage extends Component
{
    public string $email    = '';
    public string $password = '';

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
        if (auth()->attemptWhen(
            ['email' => $this->email, 'password' => $this->password],
            fn (User $user) => count($user->getRoleNames()) > 0,
            true
        )) {
            return $this->redirect(route('admin.dashboard'), true);
        }
        // add error validation for field email
        $this->addError('email', 'You do not have the necessary access to enter the panel');
    }

    public function render(): View
    {
        return view('livewire.admin.auth.login-page')
            ->layout('components.layouts.auth');
    }
}
