<?php

namespace App\Actions\Faq;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Faq;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateFaqAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Faq $faq
     * @param array{
     *     title:string,
     *     description:string,
     *     category_id:int,
     *     favorite:boolean,
     *     ordering:int,
     *     published:boolean,
     *     published_at:string,
     * }               $payload
     * @return Faq
     * @throws Throwable
     */
    public function handle(Faq $faq, array $payload): Faq
    {
        return DB::transaction(function () use ($faq, $payload) {
            $faq->update(Arr::except($payload, ['title', 'description']));
            $this->syncTranslationAction->handle($faq, Arr::only($payload, ['title', 'description']));

            return $faq->refresh();
        });
    }
}
