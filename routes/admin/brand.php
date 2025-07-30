<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Brand\BrandUpdateOrCreate;
use App\Livewire\Admin\Pages\Brand\BrandTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/brand', 'as' => 'admin.brand.'], function () {
    Route::get('/', BrandTable::class)->name('index');
    Route::get('create', BrandUpdateOrCreate::class)->name('create')->can('create,App\Models\Brand');
    Route::get('{brand}/edit', BrandUpdateOrCreate::class)->name('edit')->can('update,brand');
});
