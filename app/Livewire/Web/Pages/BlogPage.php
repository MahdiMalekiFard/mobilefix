<?php

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
{
    use WithPagination;

    public $tag            = null;
    protected $queryString = ['tag'];

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $blogs = Blog::query()
            ->where('published', true)
            ->when($this->tag, fn ($query) => $query->withAnyTags([$this->tag]))
            ->paginate(6);

        return view('livewire.web.pages.blog-page', compact('blogs'))
            ->layout('components.layouts.web');
    }
}
