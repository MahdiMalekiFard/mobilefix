<?php

namespace App\Actions\Service;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreServiceAction
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
     * @return Service
     * @throws Throwable
     */
    public function handle(array $payload): Service
    {
        return DB::transaction(function () use ($payload) {
            $model =  Service::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
