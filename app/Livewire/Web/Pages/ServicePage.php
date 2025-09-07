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

    public string $q = '';

    public function render()
    {
        $search = trim(request()->query('q', $this->q));
        $this->q = $search;

        $services = Service::query()
            ->where('published', BooleanEnum::ENABLE)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('translations', function ($t) use ($search) {
                    $t->whereIn('key', ['title', 'description'])
                      ->where('value', 'like', "%{$search}%");
                });
            })
            ->paginate(6)
            ->withQueryString();

        return view('livewire.web.pages.service-page', [
            'services' => $services,
        ])
            ->layout('components.layouts.web');
    }
}
