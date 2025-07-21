<?php

namespace App\Livewire\Admin\Pages\Blog;

use App\Actions\Blog\StoreBlogAction;
use App\Actions\Blog\UpdateBlogAction;
use App\Models\Blog;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class BlogUpdateOrCreate extends Component
{
    use Toast;

    public Blog   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Blog $blog): void
    {
        $this->model = $blog;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required'
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateBlogAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('blog.model')]),
                redirectTo: route('admin.blog.index')
            );
        } else {
            StoreBlogAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('blog.model')]),
                redirectTo: route('admin.blog.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.blog.blog-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.blog.index'), 'label' => trans('general.page.index.title', ['model' => trans('blog.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('blog.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.blog.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
