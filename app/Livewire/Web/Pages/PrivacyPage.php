<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class PrivacyPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.privacy-page')
            ->layout('components.layouts.web');
    }
}
