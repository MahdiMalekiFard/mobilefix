<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Problem;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ProblemPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Index'));
    }

    public function view(User $user, Problem $problem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Store'));
    }

    public function update(User $user, Problem $problem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Update'));
    }

    public function delete(User $user, Problem $problem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Delete'));
    }

    public function restore(User $user, Problem $problem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class, 'Restore'));
    }

    public function forceDelete(User $user, Problem $problem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Problem::class));
    }
}
