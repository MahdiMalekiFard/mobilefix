<?php

use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\ContactUsPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home-page');

// pages
Route::get('/contact-us', ContactUsPage::class)->name('contact-us-page');
