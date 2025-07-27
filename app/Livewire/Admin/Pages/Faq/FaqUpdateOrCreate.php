<?php

namespace App\Livewire\Admin\Pages\Faq;

use App\Actions\Faq\StoreFaqAction;
use App\Actions\Faq\UpdateFaqAction;
use App\Models\Faq;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class FaqUpdateOrCreate extends Component
{
    use Toast;

    public Faq   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Faq $faq): void
    {
        $this->model = $faq;
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
            UpdateFaqAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('faq.model')]),
                redirectTo: route('admin.faq.index')
            );
        } else {
            StoreFaqAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('faq.model')]),
                redirectTo: route('admin.faq.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.faq.faq-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.faq.index'), 'label' => trans('general.page.index.title', ['model' => trans('faq.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('faq.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.faq.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
