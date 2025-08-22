<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Enums\PageTypeEnum;
use App\Models\Opinion;
use App\Models\Page;
use Livewire\Component;

class AboutUsPage extends Component
{
    public function render()
    {
        $page        = Page::where('type', PageTypeEnum::ABOUT_US)->first();
        $opinions    = Opinion::where('published', BooleanEnum::ENABLE)->orderByDesc('ordering')->get();

        return view('livewire.web.pages.about-us-page', [
            'page'     => $page,
            'opinions' => $opinions,
        ])
            ->layout('components.layouts.web');
    }
}
