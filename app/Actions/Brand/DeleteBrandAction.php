<?php

namespace App\Actions\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBrandAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Brand $brand): bool
    {
        return DB::transaction(function () use ($brand) {
            return $brand->delete();
        });
    }
}
