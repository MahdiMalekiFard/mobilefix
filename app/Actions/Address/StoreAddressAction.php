<?php

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreAddressAction
{
    use AsAction;

    /**
     * @param array{
     *     title:string,
     *     address:string,
     *     is_default:boolean,
     * } $payload
     * @return Address
     * @throws Throwable
     */
    public function handle(array $payload): Address
    {
        return DB::transaction(function () use ($payload) {
            $model =  Address::create($payload);

            return $model->refresh();
        });
    }
}
