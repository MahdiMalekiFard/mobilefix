<?php

namespace App\Actions\Service;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateServiceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Service $service
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Service
     * @throws Throwable
     */
    public function handle(Service $service, array $payload): Service
    {
        return DB::transaction(function () use ($service, $payload) {
            $service->update($payload);
            $this->syncTranslationAction->handle($service, Arr::only($payload, ['title', 'description']));

            return $service->refresh();
        });
    }
}
