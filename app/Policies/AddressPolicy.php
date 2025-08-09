<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class AddressPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Index'));
    }

    public function view(User $user, Address $address): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Show')) || $user->id === $address->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Store'));
    }

    public function update(User $user, Address $address): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Update')) || $user->id === $address->user_id;
    }

    public function delete(User $user, Address $address): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Delete')) || $user->id === $address->user_id;
    }

    public function restore(User $user, Address $address): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class, 'Restore'));
    }

    public function forceDelete(User $user, Address $address): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Address::class));
    }
}
