<?php

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Actions\ContactUs\StoreContactUsAction;
use App\Actions\ContactUs\UpdateContactUsAction;
use App\Models\ContactUs;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ContactUsUpdateOrCreate extends Component
{
    use Toast;

    public ContactUs $model;
    public string $name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $subject = null;
    public string $message = '';
    public bool $is_read = false;

    public function mount(ContactUs $contactUs): void
    {
        $this->model = $contactUs;
        if ($this->model->id) {
            $this->name = $this->model->name;
            $this->email = $this->model->email;
            $this->phone = $this->model->phone;
            $this->subject = $this->model->subject;
            $this->message = $this->model->message;
            $this->is_read = $this->model->is_read->value;
        }
    }

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|min:2|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'subject'     => 'nullable|string|max:255',
            'message'     => 'required|string|min:10|max:1000',
            'is_read'     => 'boolean',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateContactUsAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('contactUs.model')]),
                redirectTo: route('admin.contact-us.index')
            );
        } else {
            StoreContactUsAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('contactUs.model')]),
                redirectTo: route('admin.contact-us.index')
            );
        }
    }

    public function render(): View
    {
        $isEdit = $this->model->id;
        
        return view('livewire.admin.pages.contactUs.contactUs-update-or-create', [
            'edit_mode'          => $isEdit,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.contact-us.index'), 'label' => trans('general.page.index.title', ['model' => trans('contactUs.model')])],
                ['label' => $isEdit ? 'Edit Contact Message' : 'Create Contact Message'],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.contact-us.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
