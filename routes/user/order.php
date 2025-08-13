<?php

use App\Livewire\User\Pages\Order\UserOrderPay;
use App\Livewire\User\Pages\Order\UserOrderShow;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
    Route::view('/', 'livewire.user.pages.order.user-order-list')->name('index');
    Route::get('/{order}', UserOrderShow::class)->name('show');
    Route::get('/{order}/pay', UserOrderPay::class)->name('pay');
});
