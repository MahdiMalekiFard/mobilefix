<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TransactionPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Index'));
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Store'));
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Update'));
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Delete'));
    }

    public function restore(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class, 'Restore'));
    }

    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Transaction::class));
    }
}
