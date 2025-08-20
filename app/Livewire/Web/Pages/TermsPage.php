<?php

namespace App\Livewire\Web\Pages;

use App\Enums\PageTypeEnum;
use App\Models\Page;
use Livewire\Component;

class TermsPage extends Component
{
    public function render()
    {
        $page = Page::where('type', PageTypeEnum::TERMS_OF_SERVICE)->first();

        return view('livewire.web.pages.terms-page', [
            'page' => $page,
        ])
            ->layout('components.layouts.web');
    }
}
