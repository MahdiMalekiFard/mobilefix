<?php

namespace App\Livewire\Admin\Pages\Team;

use App\Actions\Team\StoreTeamAction;
use App\Actions\Team\UpdateTeamAction;
use App\Models\Team;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class TeamUpdateOrCreate extends Component
{
    use Toast;

    public Team   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Team $team): void
    {
        $this->model = $team;
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
            UpdateTeamAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('team.model')]),
                redirectTo: route('admin.team.index')
            );
        } else {
            StoreTeamAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('team.model')]),
                redirectTo: route('admin.team.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.team.team-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.team.index'), 'label' => trans('general.page.index.title', ['model' => trans('team.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('team.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.team.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
