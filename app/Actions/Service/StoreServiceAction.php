<?php

namespace App\Actions\Service;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Service;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreServiceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
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
     * } $payload
     * @return Service
     * @throws Throwable
     */
    public function handle(array $payload): Service
    {
        return DB::transaction(function () use ($payload) {
            $model =  Service::create(Arr::only($payload, ['slug', 'published']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
