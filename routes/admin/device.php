<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Device\DeviceUpdateOrCreate;
use App\Livewire\Admin\Pages\Device\DeviceTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/device', 'as' => 'admin.device.'], function () {
    Route::get('/', DeviceTable::class)->name('index');
    Route::get('create', DeviceUpdateOrCreate::class)->name('create')->can('create,App\Models\Device');
    Route::get('{device}/edit', DeviceUpdateOrCreate::class)->name('edit')->can('update,device');
});
