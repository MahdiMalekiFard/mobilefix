<?php

namespace App\Actions\Transaction;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTransactionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Transaction $transaction
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Transaction
     * @throws Throwable
     */
    public function handle(Transaction $transaction, array $payload): Transaction
    {
        return DB::transaction(function () use ($transaction, $payload) {
            $transaction->update($payload);
            $this->syncTranslationAction->handle($transaction, Arr::only($payload, ['title', 'description']));

            return $transaction->refresh();
        });
    }
}
