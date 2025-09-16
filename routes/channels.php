<?php

use Illuminate\Support\Facades\Broadcast;

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

// Private channel for conversations - both admin and user can listen
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Admin users can access any conversation
    if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
        return true;
    }
    
    // Regular users can only access their own conversations
    return \App\Models\Conversation::where('id', $conversationId)
        ->where('user_id', $user->id)
        ->exists();
});
