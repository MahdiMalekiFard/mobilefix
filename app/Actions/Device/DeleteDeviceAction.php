<?php

namespace App\Actions\Device;

use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteDeviceAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Device $device): bool
    {
        return DB::transaction(function () use ($device) {
            return $device->delete();
        });
    }
}
