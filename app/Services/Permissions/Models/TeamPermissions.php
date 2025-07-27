<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Team;

class TeamPermissions extends BasePermissions
{
    public const All     = "Team.All";
    public const Index   = "Team.Index";
    public const Show    = "Team.Show";
    public const Store   = "Team.Store";
    public const Update  = "Team.Update";
    public const Toggle  = "Team.Toggle";
    public const Delete  = "Team.Delete";
    public const Restore = "Team.Restore";

    protected string $model = Team::class;
}
