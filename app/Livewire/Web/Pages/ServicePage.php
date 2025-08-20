<?php

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Models\Service;
use Livewire\Component;

class ServicePage extends Component
{
    public function render()
    {
        $services = Service::where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.service-page', [
            'services' => $services,
        ])
            ->layout('components.layouts.web');
    }
}
