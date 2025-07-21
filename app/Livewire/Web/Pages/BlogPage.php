<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class BlogPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.blog-page')
            ->layout('components.layouts.web');
    }
}
