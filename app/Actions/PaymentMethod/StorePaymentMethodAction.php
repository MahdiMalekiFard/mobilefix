<?php

namespace App\Actions\PaymentMethod;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\PaymentMethod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StorePaymentMethodAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     provider:string,
     * } $payload
     * @return PaymentMethod
     * @throws Throwable
     */
    public function handle(array $payload): PaymentMethod
    {
        return DB::transaction(function () use ($payload) {
            $model =  PaymentMethod::create(Arr::only($payload, ['published', 'provider']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
