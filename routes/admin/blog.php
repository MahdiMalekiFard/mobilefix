<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\Blog\BlogUpdateOrCreate;
use App\Livewire\Admin\Pages\Blog\BlogTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/blog', 'as' => 'admin.blog.'], function () {
    Route::get('/', BlogTable::class)->name('index');
    Route::get('create', BlogUpdateOrCreate::class)->name('create')->can('create,App\Models\Blog');
    Route::get('{blog}/edit', BlogUpdateOrCreate::class)->name('edit')->can('update,blog');
});
