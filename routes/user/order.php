<?php

use App\Livewire\User\Pages\Order\UserOrderShow;

Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
    Route::view('/', 'livewire.user.pages.order.user-order-list')->name('index');
    Route::get('/{order}', UserOrderShow::class)->name('show');
});
