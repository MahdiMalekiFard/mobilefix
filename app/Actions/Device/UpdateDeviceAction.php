<?php

namespace App\Actions\Device;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Device;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateDeviceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
    ) {}


    /**
     * @param Device $device
     * @param array{
     *     title:string,
     *     description:string,
     *     slug:string,
     *     ordering:int,
     *     published:bool,
     *     languages:array,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical:string,
     *     old_url:string,
     *     redirect_to:string,
     *     robots_meta:string,
     * }               $payload
     * @return Device
     * @throws Throwable
     */
    public function handle(Device $device, array $payload): Device
    {
        return DB::transaction(function () use ($device, $payload) {
            $device->update($payload);
            $this->syncTranslationAction->handle($device, Arr::only($payload, ['title', 'description']));
            $this->seoOptionService->create($device, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));

            return $device->refresh();
        });
    }
}
