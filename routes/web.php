<?php

use App\Livewire\Web\Pages\ServiceSinglePage;
use App\Livewire\Web\Pages\ServicePage;
use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\AboutUsPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\Web\Pages\BlogDetailPage;
use App\Livewire\Web\Pages\FaqPage;
use App\Livewire\Web\Pages\TeamPage;
use App\Livewire\Web\Pages\TermsPage;
use App\Livewire\Web\Pages\PrivacyPage;
use App\Livewire\Web\Pages\GalleryPage;
use App\Livewire\User\Auth\UserLoginPage;
use App\Livewire\User\Pages\Dashboard\UserDashboardIndex;
use App\Livewire\User\Pages\Setting\UserSettingList;
use App\Livewire\User\Pages\Order\UserOrderList;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home-page');

// Payment webhooks and callbacks (must be before auth middleware)
Route::post('/stripe/webhook', [WebhookController::class, 'handleStripe'])->name('stripe.webhook');
Route::post('/paypal/webhook', [WebhookController::class, 'handlePayPal'])->name('paypal.webhook');

// PayPal return URLs
Route::get('/payment/paypal/success', function () {
    return redirect()->route('user.dashboard')->with('success', 'Payment completed successfully!');
})->name('payment.paypal.success');

Route::get('/payment/paypal/cancel', function () {
    return redirect()->route('user.dashboard')->with('error', 'Payment was cancelled.');
})->name('payment.paypal.cancel');

// auth
Route::get('user/auth/login', UserLoginPage::class)->name('user.auth.login');
Route::post('user/auth/logout', function () {
    auth()->logout();

    return redirect()->route('user.auth.login');
})->name('user.auth.logout');

// pages
Route::get('/contact-us', ContactUsPage::class)->name('contact-us-page');
Route::get('/about-us', AboutUsPage::class)->name('about-us-page');
Route::get('/blog', BlogPage::class)->name('blog-page');
Route::get('/blog/{slug}', BlogDetailPage::class)->name('blog-detail-page');
Route::get('/service', ServicePage::class)->name('service-page');
Route::get('/service/{slug}', ServiceSinglePage::class)->name('service-single-page');
Route::get('/faq', FaqPage::class)->name('faq-page');
Route::get('/team', TeamPage::class)->name('team-page');
Route::get('/terms', TermsPage::class)->name('terms-page');
Route::get('/privacy', PrivacyPage::class)->name('privacy-page');
Route::get('/gallery', GalleryPage::class)->name('gallery-page');

// user dashboard
Route::group(['middleware' => ['user.dashboard', 'cors'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/dashboard', UserDashboardIndex::class)->name('dashboard');
    Route::get('/setting', UserSettingList::class)->name('setting');

    $files = array_diff(scandir(__DIR__ . '/user', SCANDIR_SORT_ASCENDING), ['.', '..']);
    foreach ($files as $file_name) {
        require_once sprintf('user/%s', $file_name);
    }
});
