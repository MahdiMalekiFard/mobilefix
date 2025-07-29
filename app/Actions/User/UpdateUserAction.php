<?php

namespace App\Actions\User;

use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateUserAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * @param User $user
     * @param array{
     *     name:string,
     *     email:string,
     *     mobile:string,
     *     status:string,
     *     password:string,
     *     rules:array,
     *     avatar:string,
     * }               $payload
     * @return User
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->update($payload);
            $user->syncRoles(Arr::get($payload, 'rules', []));
            $this->fileService->addMedia($user, Arr::get($payload,'avatar'), 'avatar');

            return $user->refresh();
        });
    }
}
