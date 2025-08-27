<?php

namespace App\Livewire\Admin\Pages\Problem;

use App\Actions\Problem\StoreProblemAction;
use App\Actions\Problem\UpdateProblemAction;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Problem;
use Illuminate\View\View;
use Mary\Traits\Toast;

class ProblemUpdateOrCreate extends BaseAdminComponent
{
    use Toast;

    public Problem $model;
    public string $title       = '';
    public string $description = '';
    public float $min_price    = 0;
    public float $max_price    = 0;
    public int $ordering       = 1;
    public bool $published     = false;

    public function mount(Problem $problem): void
    {
        $this->model = $problem;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
            $this->min_price   = $this->model->min_price;
            $this->max_price   = $this->model->max_price;
            $this->ordering    = $this->model->ordering;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required|boolean',
            'min_price'   => 'required|numeric|min:0|lte:max_price',
            'max_price'   => 'required|numeric|min:0|gte:min_price',
            'ordering'    => 'required|integer|min:1|max:100',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateProblemAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('problem.model')]),
                redirectTo: route('admin.problem.index')
            );
        } else {
            StoreProblemAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('problem.model')]),
                redirectTo: route('admin.problem.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.problem.problem-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.problem.index'), 'label' => trans('general.page.index.title', ['model' => trans('problem.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('problem.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.problem.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
