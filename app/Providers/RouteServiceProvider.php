<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register broadcasting authentication routes
        Broadcast::routes(['middleware' => ['web', 'auth']]);
        
        // Laravel 12: Register channels directly in service provider
        $this->registerBroadcastChannels();
        
        // Debug broadcasting auth (without CSRF for testing)
        Route::middleware(['web'])->match(['GET', 'POST'], '/broadcasting/auth/debug', function () {
            $user = auth()->user();
            
            Log::info('Broadcasting auth debug', [
                'authenticated' => auth()->check(),
                'user_id' => auth()->id(),
                'user_email' => $user->email ?? 'no user',
                'guard' => auth()->getDefaultDriver(),
                'session_id' => session()->getId(),
                'has_role_method' => method_exists($user, 'hasRole'),
                'has_roles_relation' => method_exists($user, 'roles'),
                'user_class' => get_class($user),
            ]);
            
            // Test role checking if user exists
            $roleTest = [];
            if ($user && method_exists($user, 'hasRole')) {
                $roleTest = [
                    'hasRole_admin' => $user->hasRole('admin'),
                    'hasRole_super_admin' => $user->hasRole('super_admin'),
                ];
            }
            
            if ($user && method_exists($user, 'roles')) {
                $roleTest['all_roles'] = $user->roles->pluck('name')->toArray();
            }
            
            Log::info('Role test results', $roleTest);
            
            return response()->json([
                'authenticated' => auth()->check(),
                'user_id' => auth()->id(),
                'role_test' => $roleTest,
            ]);
        });
    }

    /**
     * Register broadcast channels for Laravel 12
     */
    protected function registerBroadcastChannels(): void
    {
        // Register conversation channels
        Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
            Log::info('Laravel 12: Channel authorization called from RouteServiceProvider', [
                'user_id' => $user->id,
                'conversation_id' => $conversationId,
                'timestamp' => now()->toIso8601String(),
            ]);
            
            // Allow admin users (user_id 1 or has admin role)
            if ($user->id == 1 || $user->hasRole('admin') || $user->hasRole('super_admin')) {
                Log::info('Laravel 12: Admin access granted from RouteServiceProvider', [
                    'user_id' => $user->id,
                    'conversation_id' => $conversationId,
                ]);
                return true;
            }
            
            // Allow users who own the conversation
            $conversation = \App\Models\Conversation::find($conversationId);
            if ($conversation && $conversation->user_id == $user->id) {
                Log::info('Laravel 12: User access granted (owns conversation) from RouteServiceProvider', [
                    'user_id' => $user->id,
                    'conversation_id' => $conversationId,
                    'conversation_user_id' => $conversation->user_id,
                ]);
                return true;
            }
            
            Log::info('Laravel 12: Access denied from RouteServiceProvider', [
                'user_id' => $user->id,
                'conversation_id' => $conversationId,
                'conversation_exists' => $conversation ? 'yes' : 'no',
                'conversation_user_id' => $conversation?->user_id,
            ]);
            
            return false;
        });
        
        // Register user channels  
        Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
