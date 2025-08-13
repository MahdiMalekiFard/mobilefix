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
