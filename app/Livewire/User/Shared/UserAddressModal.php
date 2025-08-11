<?php

namespace App\Livewire\User\Shared;

use App\Actions\Address\StoreAddressAction;
use App\Models\Address;
use Livewire\Component;
use Mary\Traits\Toast;

class UserAddressModal extends Component
{
    use Toast;

    public bool $showModal = false;
    public string $title = '';
    public string $address = '';
    public bool $is_default = false;

    protected $listeners = ['openAddressModal' => 'openModal'];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'is_default' => 'boolean'
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'title' => 'Address Title',
            'address' => 'Address',
            'is_default' => 'Default Address'
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->address = '';
        $this->is_default = false;
    }

    public function submit()
    {
        $payload = $this->validate();
        
        try {
            StoreAddressAction::run(array_merge($payload, ['user_id' => auth()->id()]));
            
            $this->success(
                title: 'Address Added Successfully!',
                description: 'Your new address has been saved.'
            );
            
            $this->closeModal();
            
            // Emit event to refresh the address list
            $this->dispatch('addressAdded');
            
        } catch (\Exception $e) {
            $this->error(
                title: 'Error',
                description: 'Failed to save address. Please try again.'
            );
        }
    }

    public function render()
    {
        return view('livewire.user.shared.user-address-modal');
    }
}
