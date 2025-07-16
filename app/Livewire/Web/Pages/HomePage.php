<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class HomePage extends Component
{
    public $tab_service_selected = 'tab_0';

    public function render()
    {
        return view('livewire.web.pages.home-page');
    }
}
