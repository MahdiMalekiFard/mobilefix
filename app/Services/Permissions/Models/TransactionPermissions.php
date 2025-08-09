<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Transaction;

class TransactionPermissions extends BasePermissions
{
    public const All     = "Transaction.All";
    public const Index   = "Transaction.Index";
    public const Show    = "Transaction.Show";
    public const Store   = "Transaction.Store";
    public const Update  = "Transaction.Update";
    public const Toggle  = "Transaction.Toggle";
    public const Delete  = "Transaction.Delete";
    public const Restore = "Transaction.Restore";

    protected string $model = Transaction::class;
}
