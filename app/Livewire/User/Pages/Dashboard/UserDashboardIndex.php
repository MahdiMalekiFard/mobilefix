<?php

namespace App\Livewire\User\Pages\Dashboard;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserDashboardIndex extends Component
{
    #[Computed]
    public function stats(): array
    {
        $userId = auth()->id();

        $baseQuery = Order::query()->where('user_id', $userId);

        return [
            'total'      => (clone $baseQuery)->count(),
            'pending'    => (clone $baseQuery)->where('status', OrderStatusEnum::PENDING->value)->count(),
            'processing' => (clone $baseQuery)->where('status', OrderStatusEnum::PROCESSING->value)->count(),
            'completed'  => (clone $baseQuery)->where('status', OrderStatusEnum::COMPLETED->value)->count(),
            'paid'       => (clone $baseQuery)->where('status', OrderStatusEnum::PAID->value)->count(),
            'delivered'  => (clone $baseQuery)->where('status', OrderStatusEnum::DELIVERED->value)->count(),
        ];
    }

    #[Computed]
    public function recentOrders(): Collection
    {
        return Order::query()
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->limit(5)
            ->get(['id', 'order_number', 'status', 'total', 'updated_at']);
    }

    #[Computed]
    public function pendingPayments(): Collection
    {
        return Order::query()
            ->where('user_id', auth()->id())
            ->where('status', OrderStatusEnum::COMPLETED->value)
            ->latest('updated_at')
            ->limit(3)
            ->get(['id', 'order_number', 'status', 'total']);
    }

    public function render()
    {
        return view('livewire.user.pages.dashboard.user-dashboard-index')
            ->layout('components.layouts.user_panel');
    }
}
