<?php

namespace App\Livewire\Admin\Pages\Order;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $model;

    public function mount(Order $order): void
    {
        $this->model = $order->load([
            'user',
            'address',
            'paymentMethod.translations',
            'device.translations',
            'brand.translations',
            'problems.translations',
            'media'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pages.order.order-show', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.show.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.edit', $this->model), 'label' => trans('general.page.edit.title', ['model' => trans('order.model')])],
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
