<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Actions\Order\SearchOrderAction;
use App\Enums\BooleanEnum;
use App\Enums\OrderStatusEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Order;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;

final class OrderTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_order_datatable';
    public string $sortDirection = 'desc';

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('order.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.order.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('order.model')])],
        ];
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

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }


    public function datasource(): Builder
    {
        $query = Order::query();
        
        // Apply search using the action
        if ($this->search) {
            $searchAction = new SearchOrderAction();
            $query = $searchAction->execute($query, $this->search);
        }
        
        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
                'mobile',
                'email',
            ],
            'address' => [
                'title',
            ],
            'paymentMethod.translations' => [
                'value',
            ],
            'brand.translations' => [
                'value',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('order_number', fn ($row) => $row->order_number)
            ->add('status', function ($row) {
                $statusEnum = OrderStatusEnum::from($row->status);
                $colorClass = match ($statusEnum->color()) {
                    'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'info' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'danger' => 'bg-red-100 text-red-800 border-red-200',
                    'success' => 'bg-green-100 text-green-800 border-green-200',
                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                };
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $colorClass . '">' . $statusEnum->title() . '</span>';
            })
            ->add('total', fn ($row) => number_format((int)$row->total, 0))
            ->add('user_name', fn ($row) => $row->user_name)
            ->add('user_phone', fn ($row) => $row->user_phone)
            ->add('user_email', fn ($row) => $row->user_email)
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => $row->updated_at);
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make('order_number', 'order_number')
                ->sortable()
                ->searchable(),
            Column::make('status', 'status')
                ->title('Status')
                ->bodyAttribute('class', 'whitespace-nowrap')
                ->sortable()
                ->searchable(),
            Column::make('total', 'total')
                ->sortable()
                ->searchable(),
            Column::make('name', 'user_name')
                ->sortable()
                ->searchable(),
            Column::make('mobile', 'user_phone')
                ->sortable()
                ->searchable(),
            Column::make('email', 'user_email')
                ->sortable()
                ->searchable(),
            PowerGridHelper::columnUpdatedAT()
                ->searchable(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('updated_at_formatted', 'updated_at')
                  ->params([
                      'maxDate' => now(),
                  ])
        ];
    }

    public function actions(Order $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
            PowerGridHelper::btnShow($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>route('admin.order.create')
        ]);
    }

}
