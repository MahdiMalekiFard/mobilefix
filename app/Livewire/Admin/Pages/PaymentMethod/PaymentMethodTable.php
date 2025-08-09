<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\PaymentMethod;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\PaymentMethod;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PaymentMethodTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_paymentMethod_datatable';
    public string $sortDirection = 'desc';

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('paymentMethod.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.payment-method.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('paymentMethod.model')])],
        ];
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop("components.admin.shared.bread-crumbs")
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if((new Agent())->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }


    public function datasource(): Builder
    {
        return PaymentMethod::query();
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
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                  ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                  ->params([
                      'maxDate' => now(),
                  ])
        ];
    }

    public function actions(PaymentMethod $row): array
    {
        return [
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>route('admin.payment-method.create')
        ]);
    }

}
