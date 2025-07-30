<?php

namespace App\Actions\PaymentMethod;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\PaymentMethod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdatePaymentMethodAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param PaymentMethod $paymentMethod
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return PaymentMethod
     * @throws Throwable
     */
    public function handle(PaymentMethod $paymentMethod, array $payload): PaymentMethod
    {
        return DB::transaction(function () use ($paymentMethod, $payload) {
            $paymentMethod->update($payload);
            $this->syncTranslationAction->handle($paymentMethod, Arr::only($payload, ['title', 'description']));

            return $paymentMethod->refresh();
        });
    }
}
