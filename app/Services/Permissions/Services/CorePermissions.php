<?php

declare(strict_types=1);

namespace App\Services\Permissions\Services;

use App\Services\Permissions\Models\UserPermissions;

class CorePermissions
{
    public static function all(): array
    {
        return [
            resolve(UserPermissions::class)->all(),
        ];
    }
}
