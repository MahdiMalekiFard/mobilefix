<?php

use App\Livewire\Admin\Pages\Order\OrderShow;
use App\Livewire\Admin\Pages\Order\OrderTable;
use App\Livewire\Admin\Pages\Order\OrderUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/order', 'as' => 'admin.order.'], function () {
    Route::get('create', OrderUpdateOrCreate::class)->name('create')->can('create,App\Models\Order');
    Route::get('/', OrderTable::class)->name('index');
    Route::get('{order}/edit', OrderUpdateOrCreate::class)->name('edit')->can('update,order');
    Route::get('/{order}', OrderShow::class)->name('show');
});
