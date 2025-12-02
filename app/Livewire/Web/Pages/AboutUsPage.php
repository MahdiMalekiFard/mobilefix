<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Enums\PageTypeEnum;
use App\Enums\YesNoEnum;
use App\Models\Opinion;
use App\Models\Page;
use App\Models\Team;
use App\Models\Brand;
use Livewire\Component;

class AboutUsPage extends Component
{
    public function render()
    {
        $page        = Page::where('type', PageTypeEnum::ABOUT_US)->first();
        $opinions    = Opinion::where('published', BooleanEnum::ENABLE)->orderByDesc('ordering')->get();
        $teams       = Team::where('special', YesNoEnum::YES)->limit(4)->get();
        $brands      = Brand::where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.about-us-page', [
            'page'     => $page,
            'opinions' => $opinions ?? [],
            'teams'    => $teams ?? [],
            'brands'   => $brands ?? [],
        ])
            ->layout('components.layouts.web');
    }
}
