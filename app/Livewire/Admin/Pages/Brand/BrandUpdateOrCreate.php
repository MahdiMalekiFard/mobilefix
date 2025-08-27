<?php

namespace App\Livewire\Admin\Pages\Brand;

use App\Actions\Brand\StoreBrandAction;
use App\Actions\Brand\UpdateBrandAction;
use App\Livewire\Admin\BaseAdminComponent;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Brand;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class BrandUpdateOrCreate extends BaseAdminComponent
{
    use SeoOptionTrait, Toast, WithFileUploads;

    public Brand $model;
    public ?string $title       = '';
    public ?string $description = '';
    public bool $published      = false;
    public int $ordering        = 1;
    public $image;

    public function mount(Brand $brand): void
    {
        $this->model = $brand;
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
            $this->ordering    = $this->model->ordering;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'        => 'required|string|unique:brands,slug,' . $this->model->id,
            'title'       => 'required|string',
            'description' => 'required|string',
            'ordering'    => 'required|integer|min:1',
            'published'   => 'required|boolean',
            'image'       => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
        ]);
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateBrandAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('brand.model')]),
                redirectTo: route('admin.brand.index')
            );
        } else {
            StoreBrandAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('brand.model')]),
                redirectTo: route('admin.brand.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.brand.brand-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.brand.index'), 'label' => trans('general.page.index.title', ['model' => trans('brand.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('brand.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.brand.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
