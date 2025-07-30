<?php

namespace App\Actions\Address;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Address;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateAddressAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Address $address
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Address
     * @throws Throwable
     */
    public function handle(Address $address, array $payload): Address
    {
        return DB::transaction(function () use ($address, $payload) {
            $address->update($payload);
            $this->syncTranslationAction->handle($address, Arr::only($payload, ['title', 'description']));

            return $address->refresh();
        });
    }
}
