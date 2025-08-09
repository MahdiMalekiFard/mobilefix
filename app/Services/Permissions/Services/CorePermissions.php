<?php

declare(strict_types=1);

namespace App\Services\Permissions\Services;

use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\CategoryPermissions;
use App\Services\Permissions\Models\UserPermissions;
use App\Services\Permissions\Models\OrderPermissions;
use App\Services\Permissions\Models\PaymentMethodPermissions;
use App\Services\Permissions\Models\AddressPermissions;

class CorePermissions
{
    public static function all(): array
    {
        return [
            resolve(UserPermissions::class)->all(),
            resolve(BlogPermissions::class)->all(),
            resolve(CategoryPermissions::class)->all(),
            resolve(OrderPermissions::class)->all(),
            resolve(PaymentMethodPermissions::class)->all(),
            resolve(AddressPermissions::class)->all(),
        ];
    }
}
