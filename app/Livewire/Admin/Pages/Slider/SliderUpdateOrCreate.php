<?php

namespace App\Livewire\Admin\Pages\Slider;

use App\Actions\Slider\StoreSliderAction;
use App\Actions\Slider\UpdateSliderAction;
use App\Models\Slider;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class SliderUpdateOrCreate extends Component
{
    use Toast;

    public Slider   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Slider $slider): void
    {
        $this->model = $slider;
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
            UpdateSliderAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('slider.model')]),
                redirectTo: route('admin.slider.index')
            );
        } else {
            StoreSliderAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('slider.model')]),
                redirectTo: route('admin.slider.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.slider.slider-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.slider.index'), 'label' => trans('general.page.index.title', ['model' => trans('slider.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('slider.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.slider.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
