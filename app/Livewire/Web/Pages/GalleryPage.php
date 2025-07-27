<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class GalleryPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.gallery-page')
            ->layout('components.layouts.web');
    }
}
