<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\ContactUs;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;

final class ContactUsTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_contactUs_datatable';
    public string $sortDirection = 'desc';

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('contactUs.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.contact-us.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('contactUs.model')])],
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
        $query = ContactUs::query();

        // Apply filters
        if (!empty($this->filters['is_read'])) {
            $query->where('is_read', $this->filters['is_read']);
        }
        
        if (!empty($this->filters['created_at'])) {
            $query->whereDate('created_at', $this->filters['created_at']);
        }
        
        return $query;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('subject', fn ($row) => Str::limit($row->subject, 20))
            ->add('is_read_formatted', fn ($row) => $row->is_read->value ? 'Read' : 'Unread')
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('M d, Y H:i'));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make('Name', 'name'),
            Column::make('Email', 'email'),
            Column::make('Phone', 'phone'),
            Column::make('Subject', 'subject'),
            Column::make('Status', 'is_read_formatted'),
            Column::make('Created At', 'created_at_formatted'),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('is_read', 'is_read')
                  ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at', 'created_at')
                  ->params([
                      'maxDate' => now(),
                  ])
        ];
    }

    public function actions(ContactUs $row): array
    {
        return [
            PowerGridHelper::btnShow($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>route('admin.contact-us.create')
        ]);
    }

    // Helper method to debug filter values
    public function getFilterValues(): array
    {
        return [
            'is_read' => $this->filters['is_read'] ?? null,
            'created_at' => $this->filters['created_at'] ?? null,
            'all_filters' => $this->filters ?? [],
        ];
    }

}
