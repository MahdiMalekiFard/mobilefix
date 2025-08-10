<?php

namespace App\Livewire\User\Pages\Address;

use App\Actions\Address\StoreAddressAction;
use App\Actions\Address\UpdateAddressAction;
use App\Models\Address;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UserAddressUpdateOrCreate extends Component
{
    use Toast;

    public Address   $model;
    public string $title       = '';
    public string $address     = '';
    public bool   $is_default  = false;

    public function mount(Address $address): void
    {
        $this->model = $address;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->address = $this->model->address;
            $this->is_default = $this->model->is_default->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'address'     => 'required|string',
            'is_default'  => 'required'
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateAddressAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('address.model')]),
                redirectTo: route('user.address.index')
            );
        } else {
            StoreAddressAction::run(array_merge($payload, ['user_id' => auth()->id()]));
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('address.model')]),
                redirectTo: route('user.address.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.user.pages.address.user-address-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('user.dashboard'), 'icon' => 's-home'],
                ['link' => route('user.address.index'), 'label' => trans('general.page.index.title', ['model' => trans('address.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('address.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('user.address.index'), 'icon' => 's-arrow-left']
            ],
        ])->layout('components.layouts.user_panel');
    }
}
