<?php

namespace App\Actions\Brand;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Brand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBrandAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Brand $brand
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Brand
     * @throws Throwable
     */
    public function handle(Brand $brand, array $payload): Brand
    {
        return DB::transaction(function () use ($brand, $payload) {
            $brand->update($payload);
            $this->syncTranslationAction->handle($brand, Arr::only($payload, ['title', 'description']));

            return $brand->refresh();
        });
    }
}
