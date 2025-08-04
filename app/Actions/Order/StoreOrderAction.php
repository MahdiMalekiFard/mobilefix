<?php

namespace App\Actions\Order;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOrderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     name?:string,
     *     email?:string,
     *     phone?:string,
     *     brand_id?:int,
     *     device_id?:int,
     *     problem_id?:int,
     *     description?:string,
     *     user_note?:string,
     *     status?:string,
     *     order_number?:string,
     *     total?:float,
     *     user_id?:int,
     *     address_id?:int,
     *     payment_method_id?:int,
     *     images?:array,
     *     videos?:array
     * } $payload
     * @return Order
     * @throws Throwable
     */
    public function handle(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            // Set default status to pending if not provided
            $payload['status'] = $payload['status'] ?? OrderStatusEnum::PENDING->value;
            $payload['user_id'] = auth()->check() ? auth()->user()->id : null;
            
            // Generate order number if not provided
            if (!isset($payload['order_number'])) {
                $payload['order_number'] = 'ORD-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            
            // Generate unique tracking code
            $payload['tracking_code'] = generateUniqueTrackingCode();
            
            // Map repair form fields to order fields
            if (isset($payload['brand'])) {
                $payload['brand_id'] = $payload['brand'];
                unset($payload['brand']);
            }
            
            if (isset($payload['model'])) {
                $payload['device_id'] = $payload['model'];
                unset($payload['model']);
            }
            
            // Extract problems for later attachment
            $problems = [];
            
            if (isset($payload['problems'])) {
                $problems = is_array($payload['problems']) ? $payload['problems'] : [$payload['problems']];
                unset($payload['problems']);
            }
            
            if (isset($payload['description'])) {
                $payload['user_note'] = $payload['description'];
                unset($payload['description']);
            }
            
            // Handle customer information in config for guest orders
            $customerInfo = [];
            if (isset($payload['name'])) {
                $customerInfo['name'] = $payload['name'];
                unset($payload['name']);
            }
            if (isset($payload['email'])) {
                $customerInfo['email'] = $payload['email'];
                unset($payload['email']);
            }
            if (isset($payload['phone'])) {
                $customerInfo['phone'] = $payload['phone'];
                unset($payload['phone']);
            }
            
            if (!empty($customerInfo)) {
                $payload['config'] = array_merge($payload['config'] ?? [], $customerInfo);
            }
            
            // Extract media for later handling
            $images = $payload['images'] ?? null;
            $videos = $payload['videos'] ?? null;
            unset($payload['images'], $payload['videos']);
            
            $model = Order::create($payload);
            
            // Attach problems to the order if any
            if (!empty($problems)) {
                $model->problems()->attach($problems);
            }
            
            // Handle media uploads
            if ($images) {
                foreach ($images as $image) {
                    $model->addMedia($image->getRealPath())->preservingOriginal()
                        ->usingName($image->getClientOriginalName())
                        ->toMediaCollection('images');
                }
            }
            
            if ($videos) {
                foreach ($videos as $video) {
                    $model->addMedia($video->getRealPath())->preservingOriginal()
                        ->usingName($video->getClientOriginalName())
                        ->toMediaCollection('videos');
                }
            }
            
            // Only sync translations if title and description are provided
            $translationFields = Arr::only($payload, ['title', 'description']);
            if (!empty($translationFields)) {
                $this->syncTranslationAction->handle($model, $translationFields);
            }

            return $model->refresh();
        });
    }
}
