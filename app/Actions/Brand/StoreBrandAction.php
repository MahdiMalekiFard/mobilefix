<?php

namespace App\Actions\Brand;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Brand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBrandAction
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
     * @return Brand
     * @throws Throwable
     */
    public function handle(array $payload): Brand
    {
        return DB::transaction(function () use ($payload) {
            $model =  Brand::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
