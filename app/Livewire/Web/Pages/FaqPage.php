<?php

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Models\Faq;
use Livewire\Component;

class FaqPage extends Component
{
    public function render()
    {
        $faqs = Faq::where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.faq-page', [
            'faqs' => $faqs,
        ])
            ->layout('components.layouts.web');
    }
}
