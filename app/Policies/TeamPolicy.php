<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TeamPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Index'));
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Store'));
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Update'));
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Delete'));
    }

    public function restore(User $user, Team $team): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class, 'Restore'));
    }

    public function forceDelete(User $user, Team $team): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Team::class));
    }
}
