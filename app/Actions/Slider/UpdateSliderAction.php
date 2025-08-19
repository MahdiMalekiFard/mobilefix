<?php

namespace App\Actions\Slider;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Slider;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSliderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}


    /**
     * @param Slider $slider
     * @param array{
     *     title:string,
     *     description:string,
     *     published:boolean,
     *     image:string,
     * }               $payload
     * @return Slider
     * @throws Throwable
     */
    public function handle(Slider $slider, array $payload): Slider
    {
        return DB::transaction(function () use ($slider, $payload) {
            $slider->update(Arr::only($payload, ['published']));
            $this->syncTranslationAction->handle($slider, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $slider->refresh();
        });
    }
}
