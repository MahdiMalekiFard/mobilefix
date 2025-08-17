<?php

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
use Livewire\Component;

class BlogPage extends Component
{
    public function render()
    {
        $blogs = Blog::query()
            ->where('published', true)
            ->get();

        return view('livewire.web.pages.blog-page', compact('blogs'))
            ->layout('components.layouts.web');
    }
}
