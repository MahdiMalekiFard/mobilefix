<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Test if this file is even loaded
Log::info('Laravel 12: channels.php file loaded', [
    'timestamp' => now()->toIso8601String(),
]);

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for conversations - Laravel 12 compatible - TEMPORARY: Allow all users
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    \Illuminate\Support\Facades\Log::info('Laravel 12: Channel auth called - ALLOWING ALL', [
        'user_id' => $user->id,
        'conversation_id' => $conversationId,
        'timestamp' => now()->toIso8601String(),
    ]);
    
    // TEMPORARY: Allow all authenticated users
    return true;
});
