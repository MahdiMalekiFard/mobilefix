<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\ArtGallery\ArtGalleryUpdateOrCreate;
use App\Livewire\Admin\Pages\ArtGallery\ArtGalleryTable;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/art-gallery', 'as' => 'admin.art-gallery.'], function () {
    Route::get('/', ArtGalleryTable::class)->name('index');
    Route::get('create', ArtGalleryUpdateOrCreate::class)->name('create')->can('create,App\Models\ArtGallery');
    Route::get('{artGallery}/edit', ArtGalleryUpdateOrCreate::class)->name('edit')->can('update,artGallery');
});
