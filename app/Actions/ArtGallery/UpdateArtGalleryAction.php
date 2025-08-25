<?php

declare(strict_types=1);

namespace App\Actions\ArtGallery;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ArtGallery;
use App\Services\VideoPosterService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
                    // 1) attach video
                    $videoMedia = $artGallery->addMedia($video->getRealPath())
                        ->preservingOriginal()
                        ->usingName($video->getClientOriginalName())
                        ->toMediaCollection('videos');

                    // 2) build a poster on Windows-safe temp path
                    $sourcePath = $videoMedia->getPath(); // absolute path of stored video
                    $posterName = Str::uuid() . '.jpg';
                    $posterPath = storage_path('app' . DIRECTORY_SEPARATOR . 'temp_posters' . DIRECTORY_SEPARATOR . $posterName);

                    app(VideoPosterService::class)->makePoster($sourcePath, $posterPath, 1);

                    // 3) attach poster as an image (so it benefits from your image conversions/CDN/etc.)
                    $posterMedia = $artGallery->addMedia($posterPath)
                        ->usingFileName($posterName)
                        ->toMediaCollection('images');

                    // 4) link poster to the video via custom properties (store both id and url)
                    $videoMedia->setCustomProperty('poster_media_id', $posterMedia->id);
                    $videoMedia->setCustomProperty('poster_url', $posterMedia->getUrl());
                    $videoMedia->save();

                    // 5) cleanup temp file
                    @unlink($posterPath);
                }
            }

            return $artGallery->refresh();
        });
    }
}
