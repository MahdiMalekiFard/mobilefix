<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteAddressAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Address $address): bool
    {
        return DB::transaction(function () use ($address) {
            return $address->delete();
        });
    }
}
