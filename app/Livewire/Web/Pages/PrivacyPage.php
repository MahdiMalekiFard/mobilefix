<?php

namespace App\Livewire\Web\Pages;

use App\Enums\PageTypeEnum;
use App\Models\Page;
use Livewire\Component;

class PrivacyPage extends Component
{
    public function render()
    {
        $page = Page::where('type', PageTypeEnum::PRIVACY_POLICY)->first();

        return view('livewire.web.pages.privacy-page', [
            'page' => $page,
        ])
            ->layout('components.layouts.web');
    }
}
