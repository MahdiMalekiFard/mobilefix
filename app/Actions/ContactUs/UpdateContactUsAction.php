<?php

namespace App\Actions\ContactUs;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ContactUs;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateContactUsAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param ContactUs $contactUs
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return ContactUs
     * @throws Throwable
     */
    public function handle(ContactUs $contactUs, array $payload): ContactUs
    {
        return DB::transaction(function () use ($contactUs, $payload) {
            $contactUs->update($payload);
            $this->syncTranslationAction->handle($contactUs, Arr::only($payload, ['title', 'description']));

            return $contactUs->refresh();
        });
    }
}
