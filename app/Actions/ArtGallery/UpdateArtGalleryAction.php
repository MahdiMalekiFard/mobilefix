<?php

declare(strict_types=1);

namespace App\Actions\ArtGallery;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ArtGallery;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateArtGalleryAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:boolean,
     *     icon:string,
     *     images?:array,
     *     videos?:array,
     * }               $payload
     * @throws Throwable
     */
    public function handle(ArtGallery $artGallery, array $payload): ArtGallery
    {
        return DB::transaction(function () use ($artGallery, $payload) {
            // Extract media for later handling
            $images = $payload['images'] ?? null;
            $videos = $payload['videos'] ?? null;
            unset($payload['images'], $payload['videos']);

            $artGallery->update(Arr::only($payload, ['published', 'icon']));
            $this->syncTranslationAction->handle($artGallery, Arr::only($payload, ['title', 'description']));

            // Handle media uploads
            if ($images) {
                foreach ($images as $image) {
                    $artGallery->addMedia($image->getRealPath())->preservingOriginal()
                        ->usingName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }

            if ($videos) {
                foreach ($videos as $video) {
                    $artGallery->addMedia($video->getRealPath())->preservingOriginal()
                        ->usingName($video->getClientOriginalName())
                        ->toMediaCollection('videos');
                }
            }

            return $artGallery->refresh();
        });
    }
}
