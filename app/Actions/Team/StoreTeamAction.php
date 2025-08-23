<?php

namespace App\Actions\Team;

use App\Models\Team;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTeamAction
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
     * @return Team
     * @throws Throwable
     */
    public function handle(array $payload): Team
    {
        return DB::transaction(function () use ($payload) {
            $model =  Team::create(Arr::only($payload, ['name', 'job', 'special']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            $model->config()->set('social_media', Arr::get($payload, 'social_media') ?? []);

            return $model->refresh();
        });
    }
}
