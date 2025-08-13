<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Magic Links cleanup command
Artisan::command('magic-links:cleanup', function () {
    $magicLinkService = app(\App\Services\MagicLinkService::class);
    $deletedCount = $magicLinkService->cleanupExpired();
    
    $this->info("Cleanup completed! Deleted {$deletedCount} expired magic links.");
})->purpose('Clean up expired magic links from the database');

// Test magic link generation
Artisan::command('magic-links:test {email}', function (string $email) {
    $magicLinkService = app(\App\Services\MagicLinkService::class);
    $magicLink = $magicLinkService->generateMagicLink($email);
    
    $this->info("Magic link generated for {$email}:");
    $this->line($magicLink);
})->purpose('Test magic link generation for a given email');

// Test magic link validation
Artisan::command('magic-links:validate {token}', function (string $token) {
    $magicLinkService = app(\App\Services\MagicLinkService::class);
    $magicLink = $magicLinkService->validateToken($token);
    
    if ($magicLink) {
        $this->info("Magic link is valid!");
        $this->line("Email: {$magicLink['email']}");
        $this->line("Expires: {$magicLink['expires_at']}");
        $this->line("Used: " . ($magicLink['used'] ? 'Yes' : 'No'));
    } else {
        $this->error("Magic link is invalid or expired!");
    }
})->purpose('Test magic link validation for a given token');

// Test order creation with user info
Artisan::command('test:create-order {name} {email} {phone}', function (string $name, string $email, string $phone) {
    $order = \App\Models\Order::create([
        'order_number' => 'TEST-' . date('Ymd') . '-' . rand(1000, 9999),
        'tracking_code' => 'TEST-' . strtoupper(substr(md5(uniqid()), 0, 8)),
        'status' => 'pending',
        'total' => 0,
        'brand_id' => 1,
        'device_id' => 1,
        'config' => [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ],
    ]);
    
    $this->info("Test order created!");
    $this->line("Order ID: {$order->id}");
    $this->line("Name: {$name}");
    $this->line("Email: {$email}");
    $this->line("Phone: {$phone}");
    
    // Generate magic link
    $magicLinkService = app(\App\Services\MagicLinkService::class);
    $magicLink = $magicLinkService->generateMagicLink($email);
    
    $this->info("Magic link generated:");
    $this->line($magicLink);
})->purpose('Create a test order with user information');

// Test order info retrieval
Artisan::command('test:order-info {email}', function (string $email) {
    $magicLinkService = app(\App\Services\MagicLinkService::class);
    $orderInfo = $magicLinkService->getOrderInfoForEmail($email);
    
    if ($orderInfo) {
        $this->info("Order info found for {$email}:");
        $this->line("Name: {$orderInfo['name']}");
        $this->line("Email: {$orderInfo['email']}");
        $this->line("Phone: {$orderInfo['phone']}");
        $this->line("Mobile: {$orderInfo['mobile']}");
    } else {
        $this->error("No order found for {$email}");
    }
})->purpose('Test order info retrieval for a given email');
