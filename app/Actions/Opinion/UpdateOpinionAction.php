<?php

namespace App\Actions\Opinion;

use App\Models\Opinion;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOpinionAction
{
    use AsAction;

    /**
     * @param Opinion $opinion
     * @param array{
     *     user_name:string,
     *     comment:string,
     *     company:string,
     *     published:boolean,
     *     ordering:int,
     *     published_at:string,
     *     image:string,
     * }              $payload
     * @return Opinion
     * @throws Throwable
     */
    public function handle(Opinion $opinion, array $payload): Opinion
    {
        return DB::transaction(function () use ($opinion, $payload) {
            $opinion->update($payload);

            return $opinion->refresh();
        });
    }
}
