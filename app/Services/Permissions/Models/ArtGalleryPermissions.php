<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\ArtGallery;

class ArtGalleryPermissions extends BasePermissions
{
    public const All     = "ArtGallery.All";
    public const Index   = "ArtGallery.Index";
    public const Show    = "ArtGallery.Show";
    public const Store   = "ArtGallery.Store";
    public const Update  = "ArtGallery.Update";
    public const Toggle  = "ArtGallery.Toggle";
    public const Delete  = "ArtGallery.Delete";
    public const Restore = "ArtGallery.Restore";

    protected string $model = ArtGallery::class;
}
