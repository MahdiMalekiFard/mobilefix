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
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PaymentMethodTable extends PowerGridComponent
{
    use PowerGridHelperTrait, Toast;
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
            // Payment method page doesn't need a create action
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
            ->add('is_default_formated', fn ($row) => PowerGridHelper::fieldIsDefaultFormated($row))
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnIsDefault(),
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
        $actions = [
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];

        // Only show make default button if this payment method is not already default
        if (!$row->is_default->value) {
            $actions[] = PowerGridHelper::btnMakeDefault($row);
        }

        return $actions;
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>null
        ]);
    }

    #[On('make-default')]
    public function makeDefault($rowId): void
    {
        try {
            // First, set all payment methods to not default
            PaymentMethod::query()->update(['is_default' => BooleanEnum::DISABLE->value]);
            
            // Then set the selected one as default
            $paymentMethod = PaymentMethod::findOrFail($rowId);
            $paymentMethod->update(['is_default' => BooleanEnum::ENABLE->value]);
            
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
            
            $this->success(trans('general.model_has_set_default_successfully', ['model' => trans('paymentMethod.model')]));
        } catch (\Exception $e) {
            $this->error(trans('general.model_has_set_default_failed', ['model' => trans('paymentMethod.model')]));
        }
    }

}
