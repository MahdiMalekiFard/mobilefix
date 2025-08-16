<?php

namespace App\Livewire\Admin\Pages\Category;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use App\Livewire\Traits\SeoOptionTrait;
use App\Traits\CrudHelperTrait;
use Livewire\WithFileUploads;

class CategoryUpdateOrCreate extends Component
{
    use Toast, SeoOptionTrait, WithFileUploads, CrudHelperTrait;

    public Category   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;
    public ?string  $type        = CategoryTypeEnum::BLOG->value;
    public ?int     $ordering    = 1;
    public          $image;

    public function mount(Category $category): void
    {
        $this->model = $category;
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
            $this->type = $this->model->type->value;
            $this->ordering = $this->model->ordering;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'title'       => 'required|string',
            'description' => 'required|string',
            'slug'        => 'required|string|unique:categories,slug,' . $this->model->id,
            'published'   => 'required|boolean',
            'type'        => 'required|string|in:' . implode(',', CategoryTypeEnum::values()),
            'image'       => 'nullable|image|max:2048',
            'ordering'    => 'nullable|integer',
        ]);
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCategoryAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('category.model')]),
                redirectTo: route('admin.category.index')
            );
        } else {
            StoreCategoryAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('category.model')]),
                redirectTo: route('admin.category.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.category.category-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.category.index'), 'label' => trans('general.page.index.title', ['model' => trans('category.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('category.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.category.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
