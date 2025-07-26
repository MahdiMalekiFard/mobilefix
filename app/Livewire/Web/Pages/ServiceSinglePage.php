<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class ServiceSinglePage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.service-single-page')
            ->layout('components.layouts.web');
    }
}
