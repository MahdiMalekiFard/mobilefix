<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Address\AddressUpdateOrCreate;
use App\Livewire\Admin\Pages\Address\AddressTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/address', 'as' => 'admin.address.'], function () {
    Route::get('/', AddressTable::class)->name('index');
    Route::get('create', AddressUpdateOrCreate::class)->name('create')->can('create,App\Models\Address');
    Route::get('{address}/edit', AddressUpdateOrCreate::class)->name('edit')->can('update,address');
});
