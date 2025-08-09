<?php

use App\Livewire\User\Pages\Address\UserAddressUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'address', 'as' => 'address.'], function () {
    Route::view('/', 'livewire.user.pages.address.user-address-list')->name('index');
    Route::get('create', UserAddressUpdateOrCreate::class)->name('create');
    Route::get('{address}/edit', UserAddressUpdateOrCreate::class)->name('edit');
});