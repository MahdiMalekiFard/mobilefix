<?php

namespace App\Livewire\Admin\Pages\Device;

use App\Actions\Device\StoreDeviceAction;
use App\Actions\Device\UpdateDeviceAction;
use App\Enums\BooleanEnum;
use App\Livewire\Admin\BaseAdminComponent;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Brand;
use App\Models\Device;
use Illuminate\View\View;
use Mary\Traits\Toast;

class DeviceUpdateOrCreate extends BaseAdminComponent
{
    use SeoOptionTrait, Toast;

    public Device $model;
    public string $title        = '';
    public string $description  = '';
    public array $brands        = [];
    public int $ordering        = 1;
    public ?int $brand_id       = 1;
    public bool $published      = false;

    public function mount(Device $device): void
    {
        $this->model       = $device;
        $this->brands      = Brand::where('published', BooleanEnum::ENABLE)->get()->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
            $this->ordering    = $this->model->ordering;
            $this->brand_id    = $this->model->brand_id;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'        => 'required|string|unique:devices,slug,' . $this->model->id,
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required|boolean',
            'brand_id'    => 'required|exists:brands,id',
            'ordering'    => 'required|integer|min:1',
        ]);
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
                ['link'  => route('admin.device.index'), 'label' => trans('general.page.index.title', ['model' => trans('device.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('device.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.device.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
