<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Address;

class AddressPermissions extends BasePermissions
{
    public const All     = "Address.All";
    public const Index   = "Address.Index";
    public const Show    = "Address.Show";
    public const Store   = "Address.Store";
    public const Update  = "Address.Update";
    public const Toggle  = "Address.Toggle";
    public const Delete  = "Address.Delete";
    public const Restore = "Address.Restore";

    protected string $model = Address::class;
}
