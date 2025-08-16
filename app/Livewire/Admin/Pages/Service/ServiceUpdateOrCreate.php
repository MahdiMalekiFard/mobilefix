<?php

namespace App\Livewire\Admin\Pages\Service;

use App\Actions\Service\StoreServiceAction;
use App\Actions\Service\UpdateServiceAction;
use App\Models\Service;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use App\Livewire\Traits\SeoOptionTrait;
use App\Traits\CrudHelperTrait;
use Livewire\WithFileUploads;

class ServiceUpdateOrCreate extends Component
{
    use Toast, SeoOptionTrait, WithFileUploads, CrudHelperTrait;

    public Service   $model;
    public ?string $title       = '';
    public ?string $description = '';
    public ?string $body = '';
    public bool   $published   = false;
    public $image;

    public function mount(Service $service): void
    {
        $this->model = $service;
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->body = $this->model->body;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'        => 'required|string|unique:services,slug,' . $this->model->id,
            'title'       => 'required|string',
            'description' => 'required|string',
            'body'        => 'required|string',
            'published'   => 'required|boolean',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateServiceAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('service.model')]),
                redirectTo: route('admin.service.index')
            );
        } else {
            StoreServiceAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('service.model')]),
                redirectTo: route('admin.service.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.service.service-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.service.index'), 'label' => trans('general.page.index.title', ['model' => trans('service.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('service.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.service.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
