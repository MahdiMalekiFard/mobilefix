<?php

namespace App\View\Composers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserNavbarComposer
{
    public function compose(View $view): void
    {
        $userId = Auth::id();
        $conversation = $userId ? Conversation::where('user_id', $userId)->first() : null;
        $unreadCount = $conversation
            ? $conversation->messages()->where('sender_type', 'admin')->where('is_read', false)->count()
            : 0;

        $view->with('navbarMenu', [
            [
                'icon'       => 's-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.dashboard'),
                'route_name' => 'user.dashboard',
            ],
            [
                'icon'       => 's-shopping-cart',
                'title'      => trans('_menu.orders'),
                'route_name' => 'user.order.index',
                'access'     => true,
                'params'     => [],
                'exact'      => true,
            ],
            [
                'icon'       => 's-map-pin',
                'title'      => trans('_menu.my_address'),
                'route_name' => 'user.address.index',
                'access'     => true,
                'params'     => [],
                'exact'      => true,
            ],
            [
                'type'          => 'custom_component',
                'component'     => 'user.components.support-chat-menu-item',
                'route_name'    => 'user.chat.index',
                'access'        => true,
            ],
        ]);
    }
}