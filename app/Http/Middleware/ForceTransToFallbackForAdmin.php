<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Lang;
use Illuminate\Translation\Translator as BaseTranslator;
use Symfony\Component\HttpFoundation\Response;

class ForceTransToFallbackForAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Trust session flag (set by 'area:admin') to catch Livewire posts
        $isAdmin = $request->is('admin/*') || session('__area') === 'admin';
        if ( ! $isAdmin) {
            return $next($request);
        }

        $forced = Config::get('app.fallback_locale', 'en');

        // Replace any resolved translator + Lang facade
        app()->forgetInstance('translator');
        Facade::clearResolvedInstance('translator');
        Facade::clearResolvedInstance('Lang');

        app()->singleton('translator', function ($app) use ($forced) {
            return new class($app['translation.loader'], $forced) extends BaseTranslator {
                protected string $forced;

                public function __construct($loader, string $forced)
                {
                    parent::__construct($loader, $forced);
                    $this->forced = $forced;
                    parent::setLocale($forced);
                }

                public function setLocale($locale)
                { /* lock: ignore changes */
                }

                public function get($key, array $replace = [], $locale = null, $fallback = true)
                {
                    return parent::get($key, $replace, $this->forced, $fallback);
                }

                public function choice($key, $number, array $replace = [], $locale = null)
                {
                    return parent::choice($key, $number, $replace, $this->forced);
                }

                public function getLocale()
                {
                    return $this->forced;
                }
            };
        });

        Lang::setLocale($forced); // for code using Lang facade

        // Extra hardening: if *anyone* re-resolves translator later, keep it locked
        app()->resolving('translator', function ($t) use ($forced) {
            if (method_exists($t, 'setLocale')) {
                $t->setLocale($forced);
            }
        });

        return $next($request);
    }
}
