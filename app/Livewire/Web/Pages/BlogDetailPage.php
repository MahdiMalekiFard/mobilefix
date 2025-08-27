<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Enums\CategoryTypeEnum;
use App\Models\Blog;
use App\Models\Category;
use Livewire\Component;

class BlogDetailPage extends Component
{
    public Blog $blog;

    public function mount(string $slug): void
    {
        $this->blog = Blog::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $recentBlogs = Blog::latest()->whereNot('id', $this->blog?->id)->limit(3)->get() ?? [];
        $categories  = Category::where('type', CategoryTypeEnum::BLOG)->limit(8)->get() ?? [];

        return view('livewire.web.pages.blog-detail-page', [
            'blog'        => $this->blog,
            'recentBlogs' => $recentBlogs ?? [],
            'categories'  => $categories ?? [],
        ])->layout('components.layouts.web');
    }
}
