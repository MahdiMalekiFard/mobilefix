<?php

namespace App\Providers;

use App\View\Composers\NavbarComposer;
use App\View\Composers\UserNavbarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PowerComponents\LivewirePowerGrid\Button;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['admin.layouts.navbar', 'admin.layouts.navbar-mobile'], NavbarComposer::class);
        View::composer(['user.layouts.navbar', 'user.layouts.navbar-mobile'], UserNavbarComposer::class);

        Button::macro('navigate', function () {
            $this->attributes([
                'wire:navigate' => true,
            ]);

            return $this;
        });
    }
}
