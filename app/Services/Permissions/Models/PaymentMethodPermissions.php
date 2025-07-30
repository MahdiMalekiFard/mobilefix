<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\PaymentMethod;

class PaymentMethodPermissions extends BasePermissions
{
    public const All     = "PaymentMethod.All";
    public const Index   = "PaymentMethod.Index";
    public const Show    = "PaymentMethod.Show";
    public const Store   = "PaymentMethod.Store";
    public const Update  = "PaymentMethod.Update";
    public const Toggle  = "PaymentMethod.Toggle";
    public const Delete  = "PaymentMethod.Delete";
    public const Restore = "PaymentMethod.Restore";

    protected string $model = PaymentMethod::class;
}
