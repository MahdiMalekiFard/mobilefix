<?php

namespace App\Actions\Slider;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Slider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSliderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @return Slider
     * @throws Throwable
     */
    public function handle(array $payload): Slider
    {
        return DB::transaction(function () use ($payload) {
            $model =  Slider::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
