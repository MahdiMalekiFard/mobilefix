<?php

namespace App\Livewire\Web\Pages;

use App\Enums\BooleanEnum;
use App\Models\ArtGallery;
use Livewire\Component;

class GalleryPage extends Component
{
    public function render()
    {
        $artGalleries = ArtGallery::query()->with('media')->where('published', BooleanEnum::ENABLE)->get();

        return view('livewire.web.pages.gallery-page', [
            'artGalleries' => $artGalleries,
        ])
            ->layout('components.layouts.web');
    }
}
