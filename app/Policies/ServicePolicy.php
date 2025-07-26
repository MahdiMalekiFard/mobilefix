<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ServicePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Index'));
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Store'));
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Update'));
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Delete'));
    }

    public function restore(User $user, Service $service): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class, 'Restore'));
    }

    public function forceDelete(User $user, Service $service): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Service::class));
    }
}
