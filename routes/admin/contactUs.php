<?php

declare(strict_types=1);


use App\Livewire\Admin\Pages\ContactUs\ContactUsUpdateOrCreate;
use App\Livewire\Admin\Pages\ContactUs\ContactUsTable;
use App\Livewire\Admin\Pages\ContactUs\ContactUsView;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/contact-us', 'as' => 'admin.contact-us.'], function () {
    Route::get('/', ContactUsTable::class)->name('index');
    Route::get('create', ContactUsUpdateOrCreate::class)->name('create');
    Route::get('{contactUs}', ContactUsView::class)->name('show');
    Route::get('{contactUs}/edit', ContactUsUpdateOrCreate::class)->name('edit');
})->where(['contactUs' => '[0-9]+']);
