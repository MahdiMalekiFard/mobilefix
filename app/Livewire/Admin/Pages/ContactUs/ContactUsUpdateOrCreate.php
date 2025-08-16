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

    public ContactUs   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(ContactUs $contactUs): void
    {
        $this->model = $contactUs;
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
            UpdateContactUsAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('contactUs.model')]),
                redirectTo: route('admin.contactUs.index')
            );
        } else {
            StoreContactUsAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('contactUs.model')]),
                redirectTo: route('admin.contactUs.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.contactUs.contactUs-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.contactUs.index'), 'label' => trans('general.page.index.title', ['model' => trans('contactUs.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('contactUs.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.contactUs.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
