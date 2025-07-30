<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class DevicePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Index'));
    }

    public function view(User $user, Device $device): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Store'));
    }

    public function update(User $user, Device $device): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Update'));
    }

    public function delete(User $user, Device $device): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Delete'));
    }

    public function restore(User $user, Device $device): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class, 'Restore'));
    }

    public function forceDelete(User $user, Device $device): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Device::class));
    }
}
