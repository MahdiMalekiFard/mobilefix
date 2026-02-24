<?php

namespace App\Livewire\Admin\Pages\Dashboard;

use App\Livewire\Admin\BaseAdminComponent;
use App\Models\ContactUs;
use App\Models\Order;

class DashboardIndex extends BaseAdminComponent
{
    public function render()
    {
        $contactStats = [
            'total'  => ContactUs::count(),
            'unread' => ContactUs::unread()->count(),
            'today'  => ContactUs::whereDate('created_at', today())->count(),
        ];

        $latestOrders = Order::query()
            ->with('user')
            ->latest('updated_at')
            ->limit(10)
            ->get([
                'id',
                'order_number',
                'status',
                'total',
                'user_id',
                'updated_at',
            ]);

        return view('livewire.admin.pages.dashboard.dashboard-index', compact('contactStats', 'latestOrders'));
    }
}
