<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Broadcast;

class BroadcastHelper
{
    /**
     * Check if broadcasting is properly configured and available
     */
    public static function isAvailable(): bool
    {
        try {
            $driver = config('broadcasting.default');
            
            // If driver is null, broadcasting is disabled
            if ($driver === 'null') {
                return false;
            }
            
            // For Pusher, check if the required environment variables are set
            if ($driver === 'pusher') {
                return !empty(env('PUSHER_APP_KEY')) && 
                       !empty(env('PUSHER_APP_SECRET')) && 
                       !empty(env('PUSHER_APP_ID')) &&
                       !empty(env('PUSHER_APP_CLUSTER'));
            }
            
            // For Reverb, check if the required environment variables are set
            if ($driver === 'reverb') {
                return !empty(env('REVERB_APP_KEY')) && 
                       !empty(env('REVERB_HOST')) && 
                       !empty(env('REVERB_PORT'));
            }
            
            // For other drivers, assume they're configured if the driver is set
            return true;
        } catch (\Exception $e) {
            \Log::warning('Broadcast availability check failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Safely broadcast an event with fallback handling
     */
    public static function safeBroadcast($event): bool
    {
        try {
            if (!self::isAvailable()) {
                \Log::info('Broadcasting skipped - service not available');
                return false;
            }
            
            broadcast($event);
            return true;
        } catch (\Exception $e) {
            \Log::warning('Broadcasting failed: ' . $e->getMessage());
            return false;
        }
    }
}
