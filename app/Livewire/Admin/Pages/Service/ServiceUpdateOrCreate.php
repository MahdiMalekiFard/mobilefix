<?php

namespace App\Livewire\Admin\Pages\Service;

use App\Actions\Service\StoreServiceAction;
use App\Actions\Service\UpdateServiceAction;
use App\Models\Service;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ServiceUpdateOrCreate extends Component
{
    use Toast;

    public Service   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Service $service): void
    {
        $this->model = $service;
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
