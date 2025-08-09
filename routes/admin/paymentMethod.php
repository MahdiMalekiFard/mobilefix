<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\PaymentMethod\PaymentMethodUpdateOrCreate;
use App\Livewire\Admin\Pages\PaymentMethod\PaymentMethodTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/payment-method', 'as' => 'admin.payment-method.'], function () {
    Route::get('/', PaymentMethodTable::class)->name('index');
    Route::get('create', PaymentMethodUpdateOrCreate::class)->name('create')->can('create,App\Models\PaymentMethod');
    Route::get('{paymentMethod}/edit', PaymentMethodUpdateOrCreate::class)->name('edit')->can('update,paymentMethod');
});
