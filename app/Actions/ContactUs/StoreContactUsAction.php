<?php

namespace App\Actions\ContactUs;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ContactUs;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreContactUsAction
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
     * @return ContactUs
     * @throws Throwable
     */
    public function handle(array $payload): ContactUs
    {
        return DB::transaction(function () use ($payload) {
            $model =  ContactUs::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
