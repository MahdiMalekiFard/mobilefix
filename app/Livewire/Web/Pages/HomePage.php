<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Enums\PageTypeEnum;
use App\Enums\YesNoEnum;
use App\Models\Faq;
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
        $aboutUsPage = Page::where('type', PageTypeEnum::ABOUT_US)->first();
        $faqs        = Faq::where('favorite', YesNoEnum::YES)->get();

        return view('livewire.web.pages.home-page', [
            'sliders'     => $sliders,
            'aboutUsPage' => $aboutUsPage,
            'services'    => $services,
            'faqs'        => $faqs,
        ])
            ->layout('components.layouts.web');
    }
}
