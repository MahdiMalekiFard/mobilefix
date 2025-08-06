<?php

namespace App\Livewire\User\Pages\Order;

use App\Models\Order;
use Livewire\Component;

class UserOrderShow extends Component
{
    public Order $model;

    public function mount(Order $order)
    {
        $this->model = $order->load([
            'user',
            'address',
            'paymentMethod.translations',
            'device.translations',
            'brand.translations',
            'problems.translations',
            'media',
        ]);
    }

    public function render()
    {
        return view('livewire.user.pages.order.user-order-show', [
            'breadcrumbs'        => [
                ['link' => route('user.dashboard'), 'icon' => 's-home'],
                ['link' => route('user.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.show.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('user.order.index'), 'icon' => 's-arrow-left'],
            ],
        ])->layout('components.layouts.user_panel');
    }
}
