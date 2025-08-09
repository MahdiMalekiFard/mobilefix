<?php

namespace App\Livewire\User\Pages\Address;

use App\Actions\Address\SearchAddressAction;
use App\Helpers\PowerGridUserHelper;
use App\Models\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use Livewire\Attributes\On;
use App\Enums\BooleanEnum;
use App\Traits\PowerGridHelperTrait;
use Mary\Traits\Toast;

final class UserAddressList extends PowerGridComponent
{
    use PowerGridHelperTrait, Toast;

    public string $tableName = 'index_user_address_datatable';
    public string $sortDirection = 'desc';

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('user.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('address.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('user.address.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('address.model')])],
        ];
    }

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop("components.user.shared.bread-crumbs")
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if((new Agent())->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'actions');
        }

        return $setup;
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function datasource(): Builder
    {
        $query = Address::query()->where('user_id', auth()->user()?->id);
        
        // Apply search using the action
        if ($this->search) {
            $searchAction = new SearchAddressAction();
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
            ->add('title', fn ($row) => PowerGridUserHelper::fieldTitle($row))
            ->add('is_default_formated', fn ($row) => PowerGridUserHelper::fieldIsDefaultFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridUserHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => $row->updated_at->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            PowerGridUserHelper::columnId(),
            PowerGridUserHelper::columnTitle(),
            Column::make('address', 'address')
                ->searchable(),
            PowerGridUserHelper::columnIsDefault(),
            PowerGridUserHelper::columnUpdatedAT(),
            PowerGridUserHelper::columnAction(),
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

    public function actions(Address $row): array
    {
        $buttons = [
            PowerGridUserHelper::btnEdit($row),
            PowerGridUserHelper::btnDelete($row),
        ];

        if (!$row->is_default->value) {
            $buttons[] = PowerGridUserHelper::btnMakeDefault($row);
        }
    
        return $buttons;
    }

    public function noDataLabel(): string|View
    {
        return view('user.datatable-shared.empty-table',[
            'link'=>null
        ]);
    }

    #[On('make-default')]
    public function makeDefault($rowId): void
    {
        try {
            $user = auth()->user();
            if($user) {
                Address::where('user_id', $user->id)->update(['is_default' => BooleanEnum::DISABLE->value]);
            }
            
            $address = Address::findOrFail($rowId);
            $address->update(['is_default' => BooleanEnum::ENABLE->value]);
            
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
            
            $this->success(trans('general.model_has_set_default_successfully', ['model' => trans('address.model')]));
        } catch (\Exception $e) {
            $this->error(trans('general.model_has_set_default_failed', ['model' => trans('address.model')]));
        }
    }
}
