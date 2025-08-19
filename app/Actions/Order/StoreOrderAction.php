<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Actions\Translation\SyncTranslationAction;
use App\Enums\OrderStatusEnum;
use App\Mail\RepairRequestSubmitted;
use App\Models\Order;
use App\Services\MagicLinkService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOrderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly MagicLinkService $magicLinkService,
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
     * @throws Throwable
     */
    public function handle(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            // Set default status to pending if not provided
            $payload['status'] ??= OrderStatusEnum::PENDING->value;
            $payload['user_id'] = auth()->check() ? auth()->user()->id : null;

            // Generate order number if not provided
            if ( ! isset($payload['order_number'])) {
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

            if ( ! empty($customerInfo)) {
                $payload['config'] = array_merge($payload['config'] ?? [], $customerInfo);
            }

            // Extract media for later handling
            $images = $payload['images'] ?? null;
            $videos = $payload['videos'] ?? null;
            unset($payload['images'], $payload['videos']);

            $model = Order::create($payload);

            // Attach problems to the order if any
            if ( ! empty($problems)) {
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
            if ( ! empty($translationFields)) {
                $this->syncTranslationAction->handle($model, $translationFields);
            }

            // Send email notification
            try {
                // Only send email if we have a valid email address
                if ( ! empty($customerInfo['email'])) {
                    // Load relationships for email data
                    $model->load(['brand', 'device', 'problems']);

                    // Generate magic link for easy access
                    $magicLink = $this->magicLinkService->generateMagicLink($customerInfo['email']);

                    $emailData = [
                        'name'             => $customerInfo['name'] ?? '',
                        'email'            => $customerInfo['email'],
                        'phone'            => $customerInfo['phone'] ?? '',
                        'brand'            => $model->brand?->title ?? 'Unknown Brand',
                        'model'            => $model->device?->title ?? 'Unknown Device',
                        'problems'         => $model->problems->pluck('title')->toArray(),
                        'description'      => $model->user_note ?? '',
                        'tracking_code'    => $model->tracking_code,
                        'order_id'         => $model->id,
                        'is_authenticated' => auth()->check(),
                        'user_id'          => auth()->id(),
                        'magic_link'       => $magicLink,
                    ];

                    Mail::to($customerInfo['email'])->send(new RepairRequestSubmitted($emailData));
                }
            } catch (Exception $e) {
                // Log email sending error but don't fail the order creation
                Log::error('Failed to send repair request email: ' . $e->getMessage());
            }

            return $model->refresh();
        });
    }
}
