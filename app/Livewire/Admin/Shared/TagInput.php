<?php

namespace App\Livewire\Admin\Shared;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Tags\Tag;

class TagInput extends Component
{
    public array $selected = []; // bound to parent
    public array $options  = [];

    public function mount($selected = []): void
    {
        // Preload all existing tags
        $this->options = Tag::pluck('name')->toArray();

        // Preselect if editing
        $this->selected = $selected;
    }

    public function render(): View
    {
        return view('livewire.admin.shared.tag-input');
    }
}
