<?php

namespace App\Livewire\User\Shared;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class PasswordSetupComponent extends Component
{
    use Toast;

    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPasswordSetup = false;

    public function mount(): void
    {
        // Always show password setup if user hasn't set one - this is obligatory
        $this->showPasswordSetup = !auth()->user()->hasPasswordSet();
        
        // Prevent body scrolling when modal is open
        if ($this->showPasswordSetup) {
            $this->preventBodyScroll();
        }
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    public function updatedPassword(): void
    {
        $this->resetErrorBag('password');
        
        if (!empty($this->password)) {
            $this->validateOnly('password');
        }
    }

    public function updatedPasswordConfirmation(): void
    {
        $this->resetErrorBag('password_confirmation');
        
        if (!empty($this->password_confirmation)) {
            $this->validateOnly('password_confirmation');
        }
    }

    public function setupPassword(): void
    {
        $this->validate();

        $user = auth()->user();
        
        $user->update([
            'password' => Hash::make($this->password),
            'password_set_at' => now(),
        ]);

        $this->showPasswordSetup = false;
        $this->reset(['password', 'password_confirmation']);

        // Re-enable body scrolling when modal is closed
        $this->enableBodyScroll();

        $this->success(
            title: trans('auth.password_registered_successfully'),
            description: trans('auth.password_save_successfully')
        );
    }

    private function preventBodyScroll(): void
    {
        $this->dispatch('body-scroll', action: 'disable');
    }

    private function enableBodyScroll(): void
    {
        $this->dispatch('body-scroll', action: 'enable');
    }

    /**
     * Check if user can proceed - this ensures the modal cannot be bypassed
     */
    public function canProceed(): bool
    {
        return auth()->user()->hasPasswordSet();
    }

    public function render(): View
    {
        // Double-check that password setup is required
        if (!auth()->user()->hasPasswordSet()) {
            $this->showPasswordSetup = true;
            if (!$this->showPasswordSetup) {
                $this->preventBodyScroll();
            }
        }

        return view('livewire.user.shared.password-setup-component')
            ->layout('components.layouts.user_panel');
    }
}
