<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Brand;

class BrandPermissions extends BasePermissions
{
    public const All     = "Brand.All";
    public const Index   = "Brand.Index";
    public const Show    = "Brand.Show";
    public const Store   = "Brand.Store";
    public const Update  = "Brand.Update";
    public const Toggle  = "Brand.Toggle";
    public const Delete  = "Brand.Delete";
    public const Restore = "Brand.Restore";

    protected string $model = Brand::class;
}
