<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Team;

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Team;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class TeamTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_team_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('team.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.team.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('team.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Team::query();
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_formatted', fn ($row) => Str::limit($row?->name, 30))
            ->add('job_formatted', fn ($row) => Str::limit($row?->job, 30))
            ->add('special_formatted', fn ($row) => $row->special->title());
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle('name', 'name_formatted'),
            Column::make('job', 'job_formatted'),
            PowerGridHelper::columnPublished('special_formatted', 'special'),
            PowerGridHelper::columnCreatedAT('created_at'),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Team $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnToggle($row, 'special'),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.team.create'),
        ]);
    }
}
