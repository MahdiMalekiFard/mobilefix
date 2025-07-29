<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\User;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Livewire\Attributes\On;

final class UserTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_user_datatable';
    public string $sortDirection = 'desc';

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('user.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.user.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('user.model')])],
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'name', 'actions');
        }

        return $setup;
    }


    public function datasource(): Builder
    {
        return User::query();
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
            ->add('name', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('status_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle('name', 'name'),
            PowerGridHelper::columnPublished('status_formated', 'status'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('status_formated', 'status')
                  ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                  ->params([
                      'maxDate' => now(),
                  ])
        ];
    }

    public function actions(User $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnToggle($row, 'status'),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>route('admin.user.create')
        ]);
    }

    #[On('toggle')]
    public function toggle(int $rowId): void
    {
        $user = User::find($rowId);
        $user->status = $user->status === BooleanEnum::ENABLE ? BooleanEnum::DISABLE : BooleanEnum::ENABLE;
        $user->save();

        $this->showSuccessMessage(__('datatable.messages.toggle'));
    }

    #[On('force-delete')]
    public function forceDelete(int $rowId): void
    {
        User::find($rowId)->delete();
        $this->showSuccessMessage(__('datatable.messages.delete'));
    }
}
