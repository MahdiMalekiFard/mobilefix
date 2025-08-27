<?php

namespace App\Livewire\Admin\Pages\Team;

use App\Actions\Team\StoreTeamAction;
use App\Actions\Team\UpdateTeamAction;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Team;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TeamUpdateOrCreate extends BaseAdminComponent
{
    use Toast, WithFileUploads;

    public Team $model;
    public ?string $name    = null;
    public ?string $job     = null;
    public bool $special    = true;
    public array $social    = [
        'youtube'  => '',
        'facebook' => '',
        'twitter'  => '',   // formerly twitter
        'linkedin' => '',
    ];
    public $image;

    public function mount(Team $team): void
    {
        $this->model = $team;
        if ($this->model->id) {
            $this->name    = $this->model->name;
            $this->job     = $this->model->job;
            $this->special = $this->model->special->value;

            // merge existing stored values (if any)
            $stored       = (array) ($this->model->config()->get('social_media') ?? []);
            $this->social = array_merge($this->social, $stored);
        }
    }

    protected function rules(): array
    {
        return [
            'name'            => 'required|string',
            'job'             => 'required|string',
            'special'         => 'required|boolean',

            'social'          => ['nullable', 'array'],
            'social.youtube'  => ['nullable', 'string'],
            'social.facebook' => ['nullable', 'string'],
            'social.twitter'  => ['nullable', 'string'],
            'social.linkedin' => ['nullable', 'string'],

            'image'           => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // move socials into schemaless config key
        $social            = array_filter($payload['social'] ?? [], fn ($v) => filled($v));
        $payload['config'] = ['social_media' => $social];

        // you don’t want to mass-assign "social" itself
        unset($payload['social']);

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
                ['link'  => route('admin.team.index'), 'label' => trans('general.page.index.title', ['model' => trans('team.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('team.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.team.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
