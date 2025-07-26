<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class ServicePage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.service-page')
            ->layout('components.layouts.web');
    }
}
