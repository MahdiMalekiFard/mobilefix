<?php

namespace App\Actions\Team;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Team;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTeamAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Team $team
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Team
     * @throws Throwable
     */
    public function handle(Team $team, array $payload): Team
    {
        return DB::transaction(function () use ($team, $payload) {
            $team->update($payload);
            $this->syncTranslationAction->handle($team, Arr::only($payload, ['title', 'description']));

            return $team->refresh();
        });
    }
}
