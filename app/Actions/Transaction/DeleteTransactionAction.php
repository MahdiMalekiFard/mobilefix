<?php

namespace App\Actions\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTransactionAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Transaction $transaction): bool
    {
        return DB::transaction(function () use ($transaction) {
            return $transaction->delete();
        });
    }
}
