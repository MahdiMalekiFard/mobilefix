<?php

use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\AboutUsPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\User\Auth\UserLoginPage;
use App\Livewire\User\Pages\Dashboard\UserDashboardIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home-page');

// auth
Route::get('user/auth/login', UserLoginPage::class)->name('user.auth.login');
Route::get('user/auth/logout', function () {
    auth()->logout();

    return redirect()->route('user.auth.login');
})->name('user.auth.logout');

// pages
Route::get('/contact-us', ContactUsPage::class)->name('contact-us-page');
Route::get('/about-us', AboutUsPage::class)->name('about-us-page');
Route::get('/blog', BlogPage::class)->name('blog-page');

// user dashboard
Route::get('/dashboard', UserDashboardIndex::class)->name('user.dashboard');
