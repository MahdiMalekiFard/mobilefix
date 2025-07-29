<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteUserAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            return $user->delete();
        });
    }
}
