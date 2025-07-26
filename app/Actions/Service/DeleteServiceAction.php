<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteServiceAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Service $service): bool
    {
        return DB::transaction(function () use ($service) {
            return $service->delete();
        });
    }
}
