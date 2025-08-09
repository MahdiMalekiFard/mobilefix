<?php

namespace App\Actions\Transaction;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTransactionAction
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
     * @return Transaction
     * @throws Throwable
     */
    public function handle(array $payload): Transaction
    {
        return DB::transaction(function () use ($payload) {
            $model =  Transaction::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
