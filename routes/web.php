<?php

declare(strict_types=1);

use App\Http\Controllers\DownloadMediaController;
use App\Http\Controllers\WebhookController;
use App\Livewire\User\Auth\UserLoginPage;
use App\Livewire\User\Pages\Dashboard\UserDashboardIndex;
use App\Livewire\User\Pages\Setting\UserSettingList;
use App\Livewire\Web\Pages\AboutUsPage;
use App\Livewire\Web\Pages\BlogDetailPage;
use App\Livewire\Web\Pages\BlogPage;
use App\Livewire\Web\Pages\ContactUsPage;
use App\Livewire\Web\Pages\FaqPage;
use App\Livewire\Web\Pages\GalleryPage;
use App\Livewire\Web\Pages\HomePage;
use App\Livewire\Web\Pages\PrivacyPage;
use App\Livewire\Web\Pages\ServicePage;
use App\Livewire\Web\Pages\ServiceSinglePage;
use App\Livewire\Web\Pages\TeamPage;
use App\Livewire\Web\Pages\TermsPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home-page');
Route::get('/media/{media}/download', DownloadMediaController::class)
    ->middleware(['auth'])
    ->name('media.download');

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

// Stripe return URLs
Route::get('/payment/stripe/success/{order}', function ($orderId) {
    $sessionId = request('session_id');

    Illuminate\Support\Facades\Log::info('Stripe success route called', [
        'order_id'   => $orderId,
        'session_id' => $sessionId,
    ]);

    // Handle Stripe checkout success
    if ($sessionId) {
        try {
            $order = App\Models\Order::find($orderId);
            if ($order) {
                Illuminate\Support\Facades\Log::info('Order found', [
                    'order_id'     => $order->id,
                    'order_status' => $order->status,
                ]);

                // Find the transaction for this order
                $transaction = $order->transactions()
                    ->where('external_id', $sessionId)
                    ->first();

                if ($transaction) {
                    Illuminate\Support\Facades\Log::info('Transaction found, processing payment', [
                        'transaction_id' => $transaction->transaction_id,
                        'session_id'     => $sessionId,
                    ]);

                    // Verify and complete the payment
                    $paymentService = App\Services\Payment\PaymentServiceFactory::create(App\Enums\PaymentProviderEnum::STRIPE);
                    $paymentService->handleCheckoutSuccess($transaction, $sessionId);

                    Illuminate\Support\Facades\Log::info('Payment processing completed successfully');
                } else {
                    Illuminate\Support\Facades\Log::warning('No transaction found for session', [
                        'order_id'         => $orderId,
                        'session_id'       => $sessionId,
                        'all_transactions' => $order->transactions()->pluck('external_id', 'transaction_id')->toArray(),
                    ]);
                }
            } else {
                Illuminate\Support\Facades\Log::warning('Order not found', [
                    'order_id' => $orderId,
                ]);
            }
        } catch (Exception $e) {
            Illuminate\Support\Facades\Log::error('Stripe checkout success handling failed', [
                'order_id'   => $orderId,
                'session_id' => $sessionId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    } else {
        Illuminate\Support\Facades\Log::warning('No session_id provided in success URL');
    }

    return redirect()->route('user.order.show', ['order' => $orderId])
        ->with('success', 'Payment completed successfully!');
})->name('user.order.payment.success');

Route::get('/payment/stripe/cancel/{order}', function ($orderId) {
    return redirect()->route('user.order.pay', ['order' => $orderId])
        ->with('error', 'Payment was cancelled.');
})->name('user.order.payment.cancel');

// auth
Route::get('user/auth/login', UserLoginPage::class)->name('user.auth.login');
Route::post('user/auth/logout', function () {
    auth()->logout();

    return redirect()->route('user.auth.login');
})->name('user.auth.logout');

// Magic link route (must be before user dashboard routes)
Route::get('magic-link/{token}', App\Livewire\User\Auth\UserMagicLinkPage::class)->name('user.magic-link');

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
