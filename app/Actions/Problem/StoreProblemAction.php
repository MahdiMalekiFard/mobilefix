<?php

namespace App\Actions\Problem;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Problem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreProblemAction
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
     *     ordering:integer,
     *     min_price:float,
     *     max_price:float,
     *     config:array,
     * } $payload
     * @return Problem
     * @throws Throwable
     */
    public function handle(array $payload): Problem
    {
        return DB::transaction(function () use ($payload) {
            $model =  Problem::create(Arr::only($payload, ['published', 'ordering', 'min_price', 'max_price']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
