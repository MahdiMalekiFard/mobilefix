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
        ]);
    }
}