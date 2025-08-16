<?php

namespace App\Actions\ContactUs;

use App\Models\ContactUs;
use App\Models\User;
use App\Notifications\ContactUs\NewContactMessageNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreContactUsAction
{
    use AsAction;

    /**
     * @param array{
     *     name:string,
     *     email:string,
     *     phone?:string,
     *     subject?:string,
     *     message:string
     * } $payload
     * @return ContactUs
     * @throws Throwable
     */
    public function handle(array $payload): ContactUs
    {
        return DB::transaction(function () use ($payload) {
            $model = ContactUs::create($payload);
            
            // Send notification to admin users
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();
            
            if ($adminUsers->isNotEmpty()) {
                Notification::send($adminUsers, new NewContactMessageNotification($model));
            }
            
            return $model->refresh();
        });
    }
}
