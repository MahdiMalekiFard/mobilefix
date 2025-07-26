<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Service\ServiceUpdateOrCreate;
use App\Livewire\Admin\Pages\Service\ServiceTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/service', 'as' => 'admin.service.'], function () {
    Route::get('/', ServiceTable::class)->name('index');
    Route::get('create', ServiceUpdateOrCreate::class)->name('create')->can('create,App\Models\Service');
    Route::get('{service}/edit', ServiceUpdateOrCreate::class)->name('edit')->can('update,service');
});
