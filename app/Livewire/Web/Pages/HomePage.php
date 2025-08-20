<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Enums\PageTypeEnum;
use App\Enums\YesNoEnum;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Faq;
use App\Models\Opinion;
use App\Models\Page;
use App\Models\Service;
use App\Models\Slider;
use Livewire\Component;

class HomePage extends Component
{
    public $tab_service_selected = 'tab_0';

    public function render()
    {
        $sliders     = Slider::where('published', BooleanEnum::ENABLE)->get();
        $services    = Service::where('published', BooleanEnum::ENABLE)->get();
        $blogs       = Blog::where('published', BooleanEnum::ENABLE)->limit(3)->get();
        $opinions    = Opinion::where('published', BooleanEnum::ENABLE)->orderByDesc('ordering')->get();
        $aboutUsPage = Page::where('type', PageTypeEnum::ABOUT_US)->first();
        $faqs        = Faq::where('favorite', YesNoEnum::YES)->get();
        $brands      = Brand::where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.home-page', [
            'sliders'     => $sliders ?? [],
            'aboutUsPage' => $aboutUsPage,
            'services'    => $services ?? [],
            'faqs'        => $faqs ?? [],
            'opinions'    => $opinions ?? [],
            'blogs'       => $blogs ?? [],
            'brands'      => $brands ?? [],
        ])
            ->layout('components.layouts.web');
    }
}
