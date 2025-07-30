<?php

namespace App\Actions\Address;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Address;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreAddressAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @return Address
     * @throws Throwable
     */
    public function handle(array $payload): Address
    {
        return DB::transaction(function () use ($payload) {
            $model =  Address::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
