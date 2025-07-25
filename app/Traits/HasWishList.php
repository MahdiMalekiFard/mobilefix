<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasWishList
{
    use MorphableTrait;

    public function wishes(): MorphMany
    {
        return $this->morphMany(WishList::class, 'morphable');
    }

    public function isWished(): bool
    {
        if (auth()->check()) {
            return $this->morphable('user_wishlists')->exists();
        }

        return false;
    }
}
