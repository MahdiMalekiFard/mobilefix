<?php

namespace App\View\Composers;

use Illuminate\View\View;

class UserNavbarComposer
{
    public function compose(View $view): void
    {
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
        ]);
    }
}