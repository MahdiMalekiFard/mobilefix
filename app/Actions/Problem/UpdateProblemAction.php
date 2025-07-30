<?php

namespace App\Actions\Problem;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Problem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateProblemAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Problem $problem
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Problem
     * @throws Throwable
     */
    public function handle(Problem $problem, array $payload): Problem
    {
        return DB::transaction(function () use ($problem, $payload) {
            $problem->update($payload);
            $this->syncTranslationAction->handle($problem, Arr::only($payload, ['title', 'description']));

            return $problem->refresh();
        });
    }
}
