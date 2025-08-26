<?php

namespace App\Livewire\Admin\Pages\Address;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Address;
use App\Traits\PowerGridHelperTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AddressTable extends PowerGridComponent
{
    use PowerGridHelperTrait, Toast;
    public string $tableName     = 'index_address_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('address.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.address.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('address.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Address::query();
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
            ->add('is_default_formated', fn ($row) => PowerGridHelper::fieldIsDefaultFormated($row))
            ->add('user_name', fn ($row) => PowerGridHelper::fieldUserName($row))
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnUserName(),
            PowerGridHelper::columnTitle(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnIsDefault(),
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

    public function actions(Address $row): array
    {
        $actions = [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];

        if ( ! $row->is_default->value) {
            $actions[] = PowerGridHelper::btnMakeDefault($row);
        }

        return $actions;
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.address.create'),
        ]);
    }

    #[On('make-default')]
    public function makeDefault($rowId): void
    {
        try {
            $address = Address::findOrFail($rowId);
            $user    = $address->user;

            // First, set all addresses to not default for the user
            if ($user) {
                Address::where('user_id', $user->id)->update(['is_default' => BooleanEnum::DISABLE->value]);
            }

            $address->update(['is_default' => BooleanEnum::ENABLE->value]);

            $this->dispatch('pg:eventRefresh-' . $this->tableName);

            $this->success(trans('general.model_has_set_default_successfully', ['model' => trans('address.model')]));
        } catch (Exception $e) {
            $this->error(trans('general.model_has_set_default_failed', ['model' => trans('address.model')]));
        }
    }
}
