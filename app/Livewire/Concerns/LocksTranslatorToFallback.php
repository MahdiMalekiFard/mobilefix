<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Lang;

trait LocksTranslatorToFallback
{
    protected function forceFallback(): void
    {
        if (session('__area') === 'admin') {
            $fallback = config('app.fallback_locale');
            Lang::setLocale($fallback);
            app('translator')->setLocale($fallback);
        }
    }

    // Livewire calls this on every AJAX lifecycle hydration
    public function hydrate(): void
    {
        $this->forceFallback();
    }

    // Also enforce right before view building
    public function render()
    {
        $this->forceFallback();
        return parent::render();
    }
}
