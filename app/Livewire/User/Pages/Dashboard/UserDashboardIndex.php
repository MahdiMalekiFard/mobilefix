<?php

namespace App\Livewire\User\Pages\Dashboard;

use Livewire\Component;

class UserDashboardIndex extends Component
{
    public function render()
    {
        return view('livewire.user.pages.dashboard.user-dashboard-index')
            ->layout('components.layouts.user_panel');
    }
}
