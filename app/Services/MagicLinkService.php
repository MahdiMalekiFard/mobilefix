<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MagicLinkService
{
    /**
     * Generate a magic link for the given email
     *
     * @param string $email
     * @param int $expiryHours
     * @return string
     */
    public function generateMagicLink(string $email, int $expiryHours = 24): string
    {
        // Check if there's already an active magic link for this email
        $existingLink = DB::table('magic_links')
            ->where('email', $email)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if ($existingLink) {
            // Update existing link instead of creating a new one
            DB::table('magic_links')
                ->where('id', $existingLink->id)
                ->update([
                    'expires_at' => Carbon::now()->addHours($expiryHours),
                    'updated_at' => Carbon::now(),
                ]);
            
            return route('user.magic-link', ['token' => $existingLink->token]);
        }

        // Generate unique token
        do {
            $token = Str::random(64);
            $exists = DB::table('magic_links')->where('token', $token)->exists();
        } while ($exists);

        // Store magic link in database
        DB::table('magic_links')->insert([
            'token' => $token,
            'email' => $email,
            'expires_at' => Carbon::now()->addHours($expiryHours),
            'used' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Return the full URL
        $url = route('user.magic-link', ['token' => $token]);
        
        Log::info('Magic link generated', [
            'email' => $email,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours($expiryHours),
            'url' => $url
        ]);
        
        return $url;
    }

    /**
     * Validate a magic link token
     *
     * @param string $token
     * @return array|null
     */
    public function validateToken(string $token): ?array
    {
        $magicLink = DB::table('magic_links')
            ->where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if (!$magicLink) {
            Log::warning('Invalid magic link attempt', ['token' => $token]);
            return null;
        }

        Log::info('Magic link validated successfully', [
            'token' => $token,
            'email' => $magicLink->email
        ]);

        return (array) $magicLink;
    }

    /**
     * Mark a magic link as used
     *
     * @param string $token
     * @return bool
     */
    public function markAsUsed(string $token): bool
    {
        return DB::table('magic_links')
            ->where('token', $token)
            ->update(['used' => true, 'updated_at' => Carbon::now()]) > 0;
    }

    /**
     * Clean up expired magic links
     *
     * @return int
     */
    public function cleanupExpired(): int
    {
        return DB::table('magic_links')
            ->where('expires_at', '<', Carbon::now())
            ->delete();
    }

    /**
     * Link existing orders to a user account
     *
     * @param string $email
     * @param int $userId
     * @return int
     */
    public function linkOrdersToUser(string $email, int $userId): int
    {
        return DB::table('orders')
            ->where('config->email', $email)
            ->whereNull('user_id')
            ->update(['user_id' => $userId]);
    }

    /**
     * Check if there are existing orders for a given email
     *
     * @param string $email
     * @return int
     */
    public function getExistingOrdersCount(string $email): int
    {
        return DB::table('orders')
            ->where('config->email', $email)
            ->whereNull('user_id')
            ->count();
    }
}
