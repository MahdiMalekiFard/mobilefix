<?php

use App\Livewire\User\Auth\UserMagicLinkPage;
use App\Livewire\User\Pages\Order\UserOrderPay;
use App\Livewire\User\Pages\Order\UserOrderShow;
use Illuminate\Support\Facades\Route;

// Magic link authentication route
Route::get('/magic-link/{token}', UserMagicLinkPage::class)->name('magic-link');

Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
    Route::view('/', 'livewire.user.pages.order.user-order-list')->name('index');
    Route::get('/{order}', UserOrderShow::class)->name('show');
    Route::get('/{order}/pay', UserOrderPay::class)->name('pay');
});
