<?php

namespace App\Actions\Brand;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Brand;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBrandAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}


    /**
     * @param Brand $brand
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
     *     image:string,
     * }               $payload
     * @return Brand
     * @throws Throwable
     */
    public function handle(Brand $brand, array $payload): Brand
    {
        return DB::transaction(function () use ($brand, $payload) {
            $brand->update(Arr::only($payload, ['slug', 'published', 'ordering']));
            $this->seoOptionService->create($brand, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->syncTranslationAction->handle($brand, Arr::only($payload, ['title', 'description']));

            return $brand->refresh();
        });
    }
}
