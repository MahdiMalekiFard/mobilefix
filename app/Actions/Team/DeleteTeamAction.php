<?php

namespace App\Actions\Team;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTeamAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Team $team): bool
    {
        return DB::transaction(function () use ($team) {
            return $team->delete();
        });
    }
}
