<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class TermsPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.terms-page')
            ->layout('components.layouts.web');
    }
}
