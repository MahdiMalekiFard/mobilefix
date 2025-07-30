<?php

namespace App\Livewire\Admin\Pages\Problem;

use App\Actions\Problem\StoreProblemAction;
use App\Actions\Problem\UpdateProblemAction;
use App\Models\Problem;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ProblemUpdateOrCreate extends Component
{
    use Toast;

    public Problem   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Problem $problem): void
    {
        $this->model = $problem;
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
                ['link' => route('admin.problem.index'), 'label' => trans('general.page.index.title', ['model' => trans('problem.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('problem.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.problem.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
