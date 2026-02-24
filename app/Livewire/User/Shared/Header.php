<?php

namespace App\Livewire\User\Shared;

use App\Helpers\NotifyHelper;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class Header extends Component
{
    use Toast;

    public bool $notifications_drawer = false;
    public string $search = '';

    public function getOrderResultsProperty(): Collection
    {
        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Order::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($term) {
                $query->where('order_number', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'order_number', 'status', 'updated_at', 'total']);
    }

    public function getAddressResultsProperty(): Collection
    {
        $term = trim($this->search);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        return Address::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($term) {
                $query->where('title', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%");
            })
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'address', 'updated_at']);
    }

    public function getHasSearchResultsProperty(): bool
    {
        return $this->orderResults->isNotEmpty() || $this->addressResults->isNotEmpty();
    }

    public function submitSearch()
    {
        if ($this->hasSearchResults === false) {
            $this->warning('Kein passendes Ergebnis gefunden.');

            return null;
        }

        if ($this->orderResults->isNotEmpty()) {
            return redirect()->route('user.order.show', ['order' => $this->orderResults->first()->id]);
        }

        return redirect()->route('user.address.edit', ['address' => $this->addressResults->first()->id]);
    }

    public function toastNotification($notificationId): void
    {
        $this->info(NotifyHelper::subTitle(DatabaseNotification::find($notificationId)->data));
    }

    public function render(): View
    {
        return view('livewire.user.shared.header', [
            'notifications' => DatabaseNotification::where('notifiable_type', User::class)
                ->where('notifiable_id', auth()->id())
                ->where('read_at', null)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ]);
    }
}
