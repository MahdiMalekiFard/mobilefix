<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTeamAction
{
    use AsAction;

    public function __construct(
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     name:string,
     *     job:string,
     *     special:boolean,
     *     social_media:array<string>,
     *     image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(Team $team, array $payload): Team
    {
        return DB::transaction(function () use ($team, $payload) {
            $team->update(Arr::only($payload, ['name', 'job', 'special']));
            $this->fileService->addMedia($team, Arr::get($payload, 'image'));
            $team->config()->set('social_media', Arr::get($payload, 'social_media') ?? []);

            return $team->refresh();
        });
    }
}
