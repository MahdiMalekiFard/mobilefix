<?php

namespace App\Livewire\User\Pages\Setting;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UserSettingList extends Component
{
    use Toast;

    public string $name   = '';
    public string $email  = '';
    public string $mobile = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->name   = (string) ($user?->name ?? '');
        $this->email  = (string) ($user?->email ?? '');
        $this->mobile = (string) ($user?->mobile ?? '');
    }

    protected function rules(): array
    {
        $userId = auth()->id();

        return [
            'name'   => ['required', 'string', 'min:2', 'max:120'],
            'email'  => ['nullable', 'email', 'max:190', Rule::unique('users', 'email')->ignore($userId)],
            'mobile' => ['nullable', 'string', 'max:30', Rule::unique('users', 'mobile')->ignore($userId)],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        auth()->user()?->update($payload);

        $this->success(
            title: 'Profil erfolgreich aktualisiert.'
        );
    }

    public function render(): View
    {
        return view('livewire.user.pages.setting.user-setting-list', [
            'breadcrumbs'        => [
                ['link' => route('user.dashboard'), 'icon' => 's-home'],
                ['label' => trans('_menu.profile')],
            ],
            'breadcrumbsActions' => [],
        ])->layout('components.layouts.user_panel');
    }
}
