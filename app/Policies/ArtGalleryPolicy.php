<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArtGallery;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ArtGalleryPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Index'));
    }

    public function view(User $user, ArtGallery $artGallery): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Store'));
    }

    public function update(User $user, ArtGallery $artGallery): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Update'));
    }

    public function delete(User $user, ArtGallery $artGallery): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Delete'));
    }

    public function restore(User $user, ArtGallery $artGallery): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class, 'Restore'));
    }

    public function forceDelete(User $user, ArtGallery $artGallery): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ArtGallery::class));
    }
}
