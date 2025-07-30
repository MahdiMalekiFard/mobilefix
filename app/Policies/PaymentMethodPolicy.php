<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class PaymentMethodPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Index'));
    }

    public function view(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Store'));
    }

    public function update(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Update'));
    }

    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Delete'));
    }

    public function restore(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class, 'Restore'));
    }

    public function forceDelete(User $user, PaymentMethod $paymentMethod): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PaymentMethod::class));
    }
}
