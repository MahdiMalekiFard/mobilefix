<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseAuthProvider;
use Illuminate\Support\ServiceProvider;
use App\Policies\TagPolicy;

class AuthServiceProvider extends BaseAuthProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Tag::class => TagPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
