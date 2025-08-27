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
     *     user_id:int,
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
