<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Models\Service;
use Livewire\Component;

class ServiceSinglePage extends Component
{
    public Service $service;

    public function mount(string $slug): void
    {
        $this->service = Service::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $otherServices = Service::whereNot('id', $this->service->id)
            ->where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.service-single-page', [
            'service'       => $this->service,
            'otherServices' => $otherServices,
        ])
            ->layout('components.layouts.web');
    }
}
