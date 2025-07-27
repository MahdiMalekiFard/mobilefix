<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class TeamPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.team-page')
            ->layout('components.layouts.web');
    }
}
