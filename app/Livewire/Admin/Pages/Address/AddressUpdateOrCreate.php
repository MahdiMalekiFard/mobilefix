<?php

namespace App\Livewire\Admin\Pages\Address;

use App\Actions\Address\StoreAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Address;
use App\Models\User;
use Illuminate\View\View;
use Mary\Traits\Toast;

class AddressUpdateOrCreate extends BaseAdminComponent
{
    use Toast;

    public Address $model;
    public string $title       = '';
    public string $address     = '';
    public bool $is_default    = false;
    public ?int $user_id       = null;

    public function mount(Address $address): void
    {
        $this->model = $address;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->address     = $this->model->address;
            $this->is_default  = $this->model->is_default->value;
            $this->user_id     = $this->model->user_id;
        }
    }

    protected function rules(): array
    {
        return [
            'title'      => 'required|string',
            'address'    => 'required|string',
            'is_default' => 'required',
            'user_id'    => 'required|integer|exists:users,id',
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
            'users'              => User::all(['id', 'name']),
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.address.index'), 'label' => trans('general.page.index.title', ['model' => trans('address.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('address.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.address.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
