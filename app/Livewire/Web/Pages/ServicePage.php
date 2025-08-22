<?php

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServicePage extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $services = Service::where('published', BooleanEnum::ENABLE)->paginate(6);

        return view('livewire.web.pages.service-page', [
            'services' => $services,
        ])
            ->layout('components.layouts.web');
    }
}
