<?php

declare(strict_types=1);

namespace App\Actions\Page;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Page;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdatePageAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     body:string,
     *     type:string,
     *     slug:string,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical?:string,
     *     old_url?:string,
     *     redirect_to?:string,
     *     robots_meta:string,
     *     images?:array,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Page $page, array $payload): Page
    {
        return DB::transaction(function () use ($page, $payload) {
            $images = $payload['images'] ?? null;
            unset($payload['images']);

            $page->update($payload);
            $this->syncTranslationAction->handle($page, Arr::only($payload, ['title', 'body']));
            $this->seoOptionService->update($page, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));

            // Handle media uploads
            if ($images) {
                foreach ($images as $image) {
                    $page->addMedia($image->getRealPath())->preservingOriginal()
                        ->usingName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }

            return $page->refresh();
        });
    }
}
