<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\NotifyHelper;
use App\Livewire\Admin\BaseAdminComponent;
use App\Models\Address;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Opinion;
use App\Models\Service;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Mary\Traits\Toast;

class Header extends BaseAdminComponent
{
    use Toast;

    private const SEARCH_SCOPES = [
        'all',
        'orders',
        'users',
        'addresses',
        'categories',
        'blogs',
        'services',
        'opinions',
        'faqs',
    ];

    public bool $notifications_drawer = false;
    public string $search = '';
    public string $searchScope = 'all';

    public function setSearchScope(string $scope): void
    {
        if (in_array($scope, self::SEARCH_SCOPES, true)) {
            $this->searchScope = $scope;
        }
    }

    private function shouldSearchScope(string $scope): bool
    {
        return $this->searchScope === 'all' || $this->searchScope === $scope;
    }

    public function getOrderResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('orders')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Order::query()
            ->where(function ($query) use ($term) {
                $query->where('order_number', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'order_number', 'status', 'updated_at', 'total']);
    }

    public function getUserResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('users')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return User::query()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('mobile', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'name', 'email', 'mobile', 'updated_at']);
    }

    public function getAddressResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('addresses')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Address::query()
            ->where(function ($query) use ($term) {
                $query->where('title', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'address', 'updated_at']);
    }

    public function getCategoryResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('categories')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Category::query()
            ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%")
                    ->orWhereHas('translations', function ($translationQuery) use ($term) {
                        $translationQuery->where('value', 'like', "%{$term}%");
                    });
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'slug', 'updated_at']);
    }

    public function getBlogResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('blogs')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Blog::query()
            ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%")
                    ->orWhereHas('translations', function ($translationQuery) use ($term) {
                        $translationQuery->where('value', 'like', "%{$term}%");
                    });
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'slug', 'updated_at']);
    }

    public function getServiceResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('services')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Service::query()
            ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%")
                    ->orWhereHas('translations', function ($translationQuery) use ($term) {
                        $translationQuery->where('value', 'like', "%{$term}%");
                    });
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'slug', 'updated_at']);
    }

    public function getOpinionResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('opinions')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Opinion::query()
            ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%{$term}%")
                    ->orWhere('user_name', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%")
                    ->orWhere('comment', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'user_name', 'company', 'comment', 'updated_at']);
    }

    public function getFaqResultsProperty(): Collection
    {
        if (! $this->shouldSearchScope('faqs')) {
            return collect();
        }

        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Faq::query()
            ->where(function ($query) use ($term) {
                $query->where('id', 'like', "%{$term}%")
                    ->orWhereHas('translations', function ($translationQuery) use ($term) {
                        $translationQuery->where('value', 'like', "%{$term}%");
                    });
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'updated_at']);
    }

    public function getHasSearchResultsProperty(): bool
    {
        return $this->orderResults->isNotEmpty()
            || $this->userResults->isNotEmpty()
            || $this->addressResults->isNotEmpty()
            || $this->categoryResults->isNotEmpty()
            || $this->blogResults->isNotEmpty()
            || $this->serviceResults->isNotEmpty()
            || $this->opinionResults->isNotEmpty()
            || $this->faqResults->isNotEmpty();
    }

    public function submitSearch()
    {
        if ($this->hasSearchResults === false) {
            $this->warning('Kein passendes Ergebnis gefunden.');

            return null;
        }

        if ($this->orderResults->isNotEmpty()) {
            return redirect()->route('admin.order.show', ['order' => $this->orderResults->first()->id]);
        }

        if ($this->userResults->isNotEmpty()) {
            return redirect()->route('admin.user.edit', ['user' => $this->userResults->first()->id]);
        }

        if ($this->addressResults->isNotEmpty()) {
            return redirect()->route('admin.address.edit', ['address' => $this->addressResults->first()->id]);
        }

        if ($this->categoryResults->isNotEmpty()) {
            return redirect()->route('admin.category.edit', ['category' => $this->categoryResults->first()->id]);
        }

        if ($this->blogResults->isNotEmpty()) {
            return redirect()->route('admin.blog.edit', ['blog' => $this->blogResults->first()->id]);
        }

        if ($this->serviceResults->isNotEmpty()) {
            return redirect()->route('admin.service.edit', ['service' => $this->serviceResults->first()->id]);
        }

        if ($this->opinionResults->isNotEmpty()) {
            return redirect()->route('admin.opinion.edit', ['opinion' => $this->opinionResults->first()->id]);
        }

        return redirect()->route('admin.faq.edit', ['faq' => $this->faqResults->first()->id]);
    }

    public function toastNotification($notificationId): void
    {
        $this->info(NotifyHelper::subTitle(DatabaseNotification::find($notificationId)->data));
    }

    public function render(): View
    {
        return view('livewire.admin.shared.header', [
            'notifications' => DatabaseNotification::where('notifiable_type', User::class)
                ->where('notifiable_id', auth()->id())
                ->where('read_at', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);
    }
}
