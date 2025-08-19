<?php

namespace App\Actions\Slider;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Slider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSliderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Slider $slider
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Slider
     * @throws Throwable
     */
    public function handle(Slider $slider, array $payload): Slider
    {
        return DB::transaction(function () use ($slider, $payload) {
            $slider->update($payload);
            $this->syncTranslationAction->handle($slider, Arr::only($payload, ['title', 'description']));

            return $slider->refresh();
        });
    }
}
