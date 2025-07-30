<?php

namespace App\Actions\Problem;

use App\Models\Problem;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteProblemAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Problem $problem): bool
    {
        return DB::transaction(function () use ($problem) {
            return $problem->delete();
        });
    }
}
