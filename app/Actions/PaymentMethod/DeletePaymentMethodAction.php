<?php

namespace App\Actions\PaymentMethod;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeletePaymentMethodAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(PaymentMethod $paymentMethod): bool
    {
        return DB::transaction(function () use ($paymentMethod) {
            return $paymentMethod->delete();
        });
    }
}
