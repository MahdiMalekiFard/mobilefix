<?php

namespace App\Actions\Device;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Device;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateDeviceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Device $device
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Device
     * @throws Throwable
     */
    public function handle(Device $device, array $payload): Device
    {
        return DB::transaction(function () use ($device, $payload) {
            $device->update($payload);
            $this->syncTranslationAction->handle($device, Arr::only($payload, ['title', 'description']));

            return $device->refresh();
        });
    }
}
