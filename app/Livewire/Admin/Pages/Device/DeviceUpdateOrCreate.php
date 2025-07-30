<?php

namespace App\Livewire\Admin\Pages\Device;

use App\Actions\Device\StoreDeviceAction;
use App\Actions\Device\UpdateDeviceAction;
use App\Models\Device;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class DeviceUpdateOrCreate extends Component
{
    use Toast;

    public Device   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Device $device): void
    {
        $this->model = $device;
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
            UpdateDeviceAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('device.model')]),
                redirectTo: route('admin.device.index')
            );
        } else {
            StoreDeviceAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('device.model')]),
                redirectTo: route('admin.device.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.device.device-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.device.index'), 'label' => trans('general.page.index.title', ['model' => trans('device.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('device.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.device.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
