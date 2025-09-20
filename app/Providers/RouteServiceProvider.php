<?php

namespace App\Providers;

use App\Models\Conversation;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteProvider
{
    /** Register services. */
    public function register(): void {}

    /** Bootstrap services. */
    public function boot(): void
    {
        // Register broadcasting authentication routes
        Broadcast::routes(['middleware' => ['web', 'auth:web,admin']]);

        // Laravel 12: Register channels directly in service provider
        $this->registerBroadcastChannels();

        // Debug broadcasting auth (without CSRF for testing)
        Route::middleware(['web'])->match(['GET', 'POST'], '/broadcasting/auth/debug', function () {
            $user = auth()->user();

            // Test role checking if user exists
            $roleTest = [];
            if ($user && method_exists($user, 'hasRole')) {
                $roleTest = [
                    'hasRole_admin'       => $user->hasRole('admin'),
                    'hasRole_super_admin' => $user->hasRole('super_admin'),
                ];
            }

            if ($user && method_exists($user, 'roles')) {
                $roleTest['all_roles'] = $user->roles->pluck('name')->toArray();
            }

            return response()->json([
                'authenticated' => auth()->check(),
                'user_id'       => auth()->id(),
                'role_test'     => $roleTest,
            ]);
        });
    }

    /** Register broadcast channels for Laravel 12 */
    protected function registerBroadcastChannels(): void
    {
        // Register conversation channels
        Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
            // Allow admin users (user_id 1 or has admin role)
            if ($user->id == 1 || $user->hasRole('admin') || $user->hasRole('super_admin')) {
                return true;
            }

            // Allow users who own the conversation
            $conversation = Conversation::find($conversationId);

            return (bool) ($conversation && $conversation->user_id == $user->id);
        });

        // Register global admin channel for real-time conversation list updates
        Broadcast::channel('admin-chat', function ($user) {
            // Only allow admin users
            return $user->id == 1 || $user->hasRole('admin') || $user->hasRole('super_admin');
        });

        // Register user chat channel for live badge updates
        Broadcast::channel('user-chat.{userId}', function ($user, $userId) {
            // Only allow the specific user to listen to their chat notifications
            return (int) $user->id === (int) $userId;
        });

        // Register user channels
        Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
