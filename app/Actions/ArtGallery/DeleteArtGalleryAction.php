<?php

namespace App\Actions\ArtGallery;

use App\Models\ArtGallery;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteArtGalleryAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(ArtGallery $artGallery): bool
    {
        return DB::transaction(function () use ($artGallery) {
            return $artGallery->delete();
        });
    }
}
