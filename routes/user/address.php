<?php

use App\Livewire\Admin\Pages\Address\AddressUpdateOrCreate; 
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'address', 'as' => 'address.'], function () {
    Route::view('/', 'livewire.user.pages.address.user-address-list')->name('index');
    Route::get('create', AddressUpdateOrCreate::class)->name('create')->can('create,App\Models\Address');
    Route::get('{address}/edit', AddressUpdateOrCreate::class)->name('edit')->can('update,address');
});