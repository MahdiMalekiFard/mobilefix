<?php

namespace App\Actions\Device;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Device;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreDeviceAction
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
     * @return Device
     * @throws Throwable
     */
    public function handle(array $payload): Device
    {
        return DB::transaction(function () use ($payload) {
            $model =  Device::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
