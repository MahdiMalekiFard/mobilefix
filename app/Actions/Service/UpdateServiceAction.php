<?php

namespace App\Actions\Service;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Service;
use App\Services\SeoOption\SeoOptionService;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateServiceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}


    /**
     * @param Service $service
     * @param array{
     *     title:string,
     *     description:string,
     *     body:string,
     *     published:bool,
     *     slug:string,
     *     image:string,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical:string,
     *     old_url:string,
     *     redirect_to:string,
     *     robots_meta:string,
     * }               $payload
     * @return Service
     * @throws Throwable
     */
    public function handle(Service $service, array $payload): Service
    {
        return DB::transaction(function () use ($service, $payload) {
            $service->update(Arr::only($payload, ['slug', 'published']));
            $this->syncTranslationAction->handle($service, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->update($service, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->fileService->addMedia($service, Arr::get($payload, 'image'));

            return $service->refresh();
        });
    }
}
