<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOrderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     order_number?:string,
     *     status?:string,
     *     total?:int,
     *     user_name?:string,
     *     user_phone?:string,
     *     user_email?:string,
     *     brand_id?:int,
     *     device_id?:int,
     *     user_id?:int,
     *     address_id?:int|null,
     *     payment_method_id?:int|null,
     *     user_note?:string,
     *     admin_note?:string,
     *     images?:array,
     *     videos?:array,
     *     problems?:array<int>
     * }               $payload
     * @throws Throwable
     */
    public function handle(Order $order, array $payload): Order
    {
        return DB::transaction(function () use ($order, $payload) {
            // Extract problems for later attachment
            $problems = [];
            
            if (isset($payload['problems'])) {
                $problems = is_array($payload['problems']) ? $payload['problems'] : [$payload['problems']];
                unset($payload['problems']);
            }
            
            // Handle customer information in config for guest orders
            $customerInfo = [];
            if (isset($payload['user_name'])) {
                $customerInfo['name'] = $payload['user_name'];
                unset($payload['user_name']);
            }
            if (isset($payload['user_email'])) {
                $customerInfo['email'] = $payload['user_email'];
                unset($payload['user_email']);
            }
            if (isset($payload['user_phone'])) {
                $customerInfo['phone'] = $payload['user_phone'];
                unset($payload['user_phone']);
            }
            
            if ( ! empty($customerInfo)) {
                $payload['config'] = array_merge($payload['config'] ?? [], $customerInfo);
            }
            
            // Extract media for later handling
            $images = $payload['images'] ?? null;
            $videos = $payload['videos'] ?? null;
            unset($payload['images'], $payload['videos']);
            
            $order->update($payload);
            
            // Attach problems to the order if any
            if ( ! empty($problems)) {
                $order->problems()->sync($problems);
            }
            
            // Handle media uploads
            if ($images) {
                foreach ($images as $image) {
                    $order->addMedia($image->getRealPath())->preservingOriginal()
                        ->usingName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }
            
            if ($videos) {
                foreach ($videos as $video) {
                    $order->addMedia($video->getRealPath())->preservingOriginal()
                        ->usingName($video->getClientOriginalName())
                        ->toMediaCollection('videos');
                }
            }

            return $order->refresh();
        });
    }
}
