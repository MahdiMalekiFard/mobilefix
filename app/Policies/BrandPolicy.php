<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BrandPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Index'));
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Store'));
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Update'));
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Delete'));
    }

    public function restore(User $user, Brand $brand): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class, 'Restore'));
    }

    public function forceDelete(User $user, Brand $brand): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Brand::class));
    }
}
