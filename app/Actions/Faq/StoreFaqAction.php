<?php

namespace App\Actions\Faq;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Faq;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreFaqAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     category_id:int,
     *     favorite:boolean,
     *     ordering:int,
     *     published:boolean,
     *     published_at:string,
     * } $payload
     * @return Faq
     * @throws Throwable
     */
    public function handle(array $payload): Faq
    {
        return DB::transaction(function () use ($payload) {
            $model =  Faq::create(Arr::except($payload, ['title', 'description']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
