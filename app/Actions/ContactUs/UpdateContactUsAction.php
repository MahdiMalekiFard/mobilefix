<?php

declare(strict_types=1);

namespace App\Actions\ContactUs;

use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateContactUsAction
{
    use AsAction;

    /**
     * @param ContactUs $contactUs
     * @param array{
     *     is_read?:boolean,
     * } $payload
     * @return ContactUs
     * @throws Throwable
     */
    public function handle(ContactUs $contactUs, array $payload): ContactUs
    {
        return DB::transaction(function () use ($contactUs, $payload) {
            $contactUs->update($payload);

            return $contactUs->refresh();
        });
    }
}
