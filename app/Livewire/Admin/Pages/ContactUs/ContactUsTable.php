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
use PowerComponents\LivewirePowerGrid\Button;


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
                ->includeViewOnTop("components.admin.shared.success-message")
                ->includeViewOnTop("components.admin.pages.contactUs.mark-all-read-button")
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

        $urlFilters = request()->get('filters', []);

        if ($urlFilters && array_key_exists('is_read', $urlFilters)) {
            $isRead = $urlFilters['is_read'];
            $query->where('is_read', $isRead);
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
            ->add('is_read_badge', function ($row) {
                if ($row->is_read->value) {
                    return '<span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Read
                            </span>';
                }
    
                return '<span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">
                            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                            Unread
                        </span>';
            })
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
            Column::make('Status', 'is_read_badge'),
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
        $actions = [
            PowerGridHelper::btnShow($row),
        ];

        // Add mark as read button if message is unread
        if (!$row->is_read->value) {
            $actions[] = Button::add('mark-as-read')
                ->slot('<i class="fas fa-check text-success"></i>')
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                    'title' => 'Mark as Read',
                    'wire:click' => "markAsRead({$row->id})",
                ]);
        }

        $actions[] = PowerGridHelper::btnEdit($row);
        $actions[] = PowerGridHelper::btnDelete($row);

        return $actions;
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table',[
            'link'=>null
        ]);
    }

    /**
     * Mark a contact us message as read
     */
    public function markAsRead(int $contactUsId): void
    {
        $contactUs = ContactUs::find($contactUsId);
        
        if ($contactUs && !$contactUs->is_read->value) {
            $contactUs->markAsRead();

            // Show success message
            session()->flash('success', 'Message marked as read successfully.');

            // hard refresh so non-Livewire parts update
            $this->js('window.location.reload()');
        }
    }

    /**
     * Mark all unread messages as read
     */
    public function markAllAsRead(): void
    {
        $unreadCount = ContactUs::where('is_read', false)->count();
        
        if ($unreadCount > 0) {
            ContactUs::where('is_read', false)->update(['is_read' => true]);

            // Show success message
            session()->flash('success', "All {$unreadCount} unread messages have been marked as read.");

            // hard refresh
            $this->js('window.location.reload()');
        } else {
            session()->flash('info', 'No unread messages to mark as read.');
        }
    }

    /**
     * Get the count of unread messages
     */
    public function getUnreadCount(): int
    {
        return ContactUs::where('is_read', false)->count();
    }

}
