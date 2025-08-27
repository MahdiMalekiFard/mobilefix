<?php

namespace App\Livewire\Admin\Shared;

use App\Livewire\Admin\BaseAdminComponent;
use Illuminate\View\View;
use Spatie\Tags\Tag;

class TagInput extends BaseAdminComponent
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
