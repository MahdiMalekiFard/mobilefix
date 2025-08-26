<?php

namespace App\View\Components\Mx;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ThemeToggle extends Component
{
    /**
     * When to reload the page:
     * - "tinymce": reload only if page has TinyMCE (default)
     * - "always":  always reload on toggle
     * - "never":   never reload (no page refresh)
     */
    public string $mode;

    /** Extra CSS selector to detect editor presence (optional) */
    public ?string $selector;

    // theme & class names you use in your CSS/ Daisy/Mary
    public string $light;
    public string $dark;
    public string $lightClass;
    public string $darkClass;

    public function __construct(
        string $mode = 'tinymce',
        ?string $selector = '.tox-tinymce',
        string $light = 'light',
        string $dark = 'dark',
        string $lightClass = 'light',
        string $darkClass = 'dark',
    ) {
        $this->mode       = $mode;
        $this->selector   = $selector;
        $this->light      = $light;
        $this->dark       = $dark;
        $this->lightClass = $lightClass;
        $this->darkClass  = $darkClass;
    }

    public function render(): View
    {
        return view('components.mx.theme-toggle');
    }
}
