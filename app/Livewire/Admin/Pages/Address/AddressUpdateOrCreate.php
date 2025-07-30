<?php

namespace App\Livewire\Admin\Pages\Address;

use App\Actions\Address\StoreAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Models\Address;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class AddressUpdateOrCreate extends Component
{
    use Toast;

    public Address   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Address $address): void
    {
        $this->model = $address;
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
            UpdateAddressAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('address.model')]),
                redirectTo: route('admin.address.index')
            );
        } else {
            StoreAddressAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('address.model')]),
                redirectTo: route('admin.address.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.address.address-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.address.index'), 'label' => trans('general.page.index.title', ['model' => trans('address.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('address.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.address.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
