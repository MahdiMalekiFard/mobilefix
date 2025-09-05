<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Problem;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Problem;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ProblemTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_problem_datatable';
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'price_range', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('problem.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.problem.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('problem.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Problem::query();
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
            'min_price',
            'max_price',
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('price_range', fn ($row) => PowerGridHelper::fieldPriceRange($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            PowerGridHelper::columnPriceRange(),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnUpdatedAT('updated_at'),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

            Filter::inputText('min_price', 'min_price')
                ->placeholder('Min price...'),
            Filter::inputText('max_price', 'max_price')
                ->placeholder('Max price...'),

            Filter::datepicker('updated_at', 'updated_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Problem $row): array
    {
        return [
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.problem.create'),
        ]);
    }
}
