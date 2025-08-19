<?php

declare(strict_types=1);

namespace App\Services\Permissions\Services;

use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\BrandPermissions;
use App\Services\Permissions\Models\CategoryPermissions;
use App\Services\Permissions\Models\ContactUsPermissions;
use App\Services\Permissions\Models\DevicePermissions;
use App\Services\Permissions\Models\FaqPermissions;
use App\Services\Permissions\Models\OpinionPermissions;
use App\Services\Permissions\Models\PagePermissions;
use App\Services\Permissions\Models\SliderPermissions;
use App\Services\Permissions\Models\UserPermissions;
use App\Services\Permissions\Models\OrderPermissions;
use App\Services\Permissions\Models\PaymentMethodPermissions;
use App\Services\Permissions\Models\AddressPermissions;
use App\Services\Permissions\Models\ProblemPermissions;
use App\Services\Permissions\Models\ServicePermissions;

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
            resolve(ProblemPermissions::class)->all(),
            resolve(ServicePermissions::class)->all(),
            resolve(SliderPermissions::class)->all(),
            resolve(PagePermissions::class)->all(),
            resolve(FaqPermissions::class)->all(),
            resolve(OpinionPermissions::class)->all(),
            resolve(ContactUsPermissions::class)->all(),
            resolve(BrandPermissions::class)->all(),
            resolve(DevicePermissions::class)->all(),
        ];
    }
}
