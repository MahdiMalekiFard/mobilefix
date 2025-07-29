<?php

namespace App\Actions\User;

use App\Services\File\FileService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreUserAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService
    ) {}

    /**
     * @param array{
     *     name:string,
     *     email:string,
     *     mobile:string,
     *     status:string,
     *     password:string,
     *     rules:array,
     *     avatar:string,
     * } $payload
     * @return User
     * @throws Throwable
     */
    public function handle(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $payload['password'] = Hash::make($payload['password']);
            
            $user =  User::create($payload);
            $user->syncRoles(Arr::get($payload, 'rules', []));
            $this->fileService->addMedia($user, Arr::get($payload,'avatar'), 'avatar');

            return $user->refresh();
        });
    }
}
