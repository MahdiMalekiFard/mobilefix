<?php

namespace App\Actions\Brand;

use App\Actions\Translation\SyncTranslationAction;
use App\Services\SeoOption\SeoOptionService;
use App\Services\File\FileService;
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
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
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
     * } $payload
     * @return Brand
     * @throws Throwable
     */
    public function handle(array $payload): Brand
    {
        return DB::transaction(function () use ($payload) {
            $model =  Brand::create(Arr::only($payload, ['slug', 'published', 'ordering']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));
            $this->seoOptionService->create($model, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
