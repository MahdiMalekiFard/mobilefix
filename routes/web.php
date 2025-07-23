<?php

use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\AboutUsPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\Web\Pages\BlogDetailPage;
use App\Livewire\User\Auth\UserLoginPage;
use App\Livewire\User\Pages\Dashboard\UserDashboardIndex;
use App\Livewire\User\Pages\Setting\UserSettingList;
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
Route::get('/blog/{slug}', BlogDetailPage::class)->name('blog-detail-page');

// user dashboard
Route::group(['middleware' => ['user.dashboard']], function () {
    Route::get('/user/dashboard', UserDashboardIndex::class)->name('user.dashboard');
    Route::get('/user/setting', UserSettingList::class)->name('user.setting');
});
