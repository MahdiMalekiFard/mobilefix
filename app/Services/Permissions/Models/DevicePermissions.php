<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Device;

class DevicePermissions extends BasePermissions
{
    public const All     = "Device.All";
    public const Index   = "Device.Index";
    public const Show    = "Device.Show";
    public const Store   = "Device.Store";
    public const Update  = "Device.Update";
    public const Toggle  = "Device.Toggle";
    public const Delete  = "Device.Delete";
    public const Restore = "Device.Restore";

    protected string $model = Device::class;
}
