<?php

namespace App\Livewire\Admin\Pages\Dashboard;

use App\Models\ContactUs;
use Livewire\Component;

class DashboardIndex extends Component
{
    public function render()
    {
        $contactStats = [
            'total' => ContactUs::count(),
            'unread' => ContactUs::unread()->count(),
            'today' => ContactUs::whereDate('created_at', today())->count(),
        ];

        return view('livewire.admin.pages.dashboard.dashboard-index', compact('contactStats'));
    }
}
