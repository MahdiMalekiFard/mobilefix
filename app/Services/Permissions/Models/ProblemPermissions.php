<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Problem;

class ProblemPermissions extends BasePermissions
{
    public const All     = "Problem.All";
    public const Index   = "Problem.Index";
    public const Show    = "Problem.Show";
    public const Store   = "Problem.Store";
    public const Update  = "Problem.Update";
    public const Toggle  = "Problem.Toggle";
    public const Delete  = "Problem.Delete";
    public const Restore = "Problem.Restore";

    protected string $model = Problem::class;
}
