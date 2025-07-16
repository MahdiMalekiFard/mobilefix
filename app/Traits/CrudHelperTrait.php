<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;

trait CrudHelperTrait
{
    public function setPublishedAt($publishedAt): ?string
    {
        return $publishedAt ? Carbon::parse($publishedAt)->format('Y-m-d') : null;
    }
    public function normalizePublishedAt(array $payload,$key='published_at'): array
    {
        $payload[$key] = $payload[$key] ?: null;
        return $payload;
    }

}
