<?php

namespace App\Http\Controllers;

use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle Stripe webhook events
     *
     * @param Request $request
     * @return Response
     */
    public function handleStripe(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            Log::warning('Stripe webhook received without signature');
            return response('No signature provided', 400);
        }

        try {
            $event = $this->stripeService->verifyWebhook($payload, $signature);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage()
            ]);
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', [
            'event_type' => $event->type,
            'event_id' => $event->id
        ]);

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            case 'payment_intent.canceled':
                $this->handlePaymentIntentCanceled($event->data->object);
                break;

            case 'payment_intent.requires_action':
                $this->handlePaymentIntentRequiresAction($event->data->object);
                break;

            case 'payment_method.attached':
                $this->handlePaymentMethodAttached($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event type', [
                    'event_type' => $event->type
                ]);
        }

        return response('Webhook handled', 200);
    }

    /**
     * Handle PayPal webhook events
     *
     * @param Request $request
     * @return Response
     */
    public function handlePayPal(Request $request): Response
    {
        $payload = $request->getContent();
        
        // PayPal webhook verification would go here
        // For now, we'll just log the webhook
        Log::info('PayPal webhook received', [
            'payload' => $request->all()
        ]);

        // PayPal webhooks are less critical since we handle success/failure 
        // via frontend callbacks and order capture
        
        return response('PayPal webhook received', 200);
    }

    /**
     * Handle successful payment intent
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return void
     */
    private function handlePaymentIntentSucceeded($paymentIntent): void
    {
        Log::info('Payment intent succeeded', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'order_id' => $paymentIntent->metadata['order_id'] ?? null
        ]);

        $this->stripeService->handleSuccessfulPayment($paymentIntent);
    }

    /**
     * Handle failed payment intent
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return void
     */
    private function handlePaymentIntentFailed($paymentIntent): void
    {
        Log::warning('Payment intent failed', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'order_id' => $paymentIntent->metadata['order_id'] ?? null,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error'
        ]);

        $this->stripeService->handleFailedPayment($paymentIntent);
    }

    /**
     * Handle canceled payment intent
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return void
     */
    private function handlePaymentIntentCanceled($paymentIntent): void
    {
        Log::info('Payment intent canceled', [
            'payment_intent_id' => $paymentIntent->id,
            'order_id' => $paymentIntent->metadata['order_id'] ?? null
        ]);

        // You might want to update the order status to cancelled
        $orderId = $paymentIntent->metadata['order_id'] ?? null;
        if ($orderId) {
            $order = \App\Models\Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => \App\Enums\OrderStatusEnum::CANCELLED_BY_USER->value,
                    'config' => array_merge($order->config ?? [], [
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'payment_canceled_at' => now()->toISOString(),
                        'cancellation_reason' => 'Payment canceled by user or system',
                    ])
                ]);
            }
        }
    }

    /**
     * Handle payment intent that requires action
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return void
     */
    private function handlePaymentIntentRequiresAction($paymentIntent): void
    {
        Log::info('Payment intent requires action', [
            'payment_intent_id' => $paymentIntent->id,
            'order_id' => $paymentIntent->metadata['order_id'] ?? null,
            'next_action' => $paymentIntent->next_action->type ?? 'unknown'
        ]);

        // This usually happens with 3D Secure authentication
        // The frontend should handle this automatically with Stripe.js
    }

    /**
     * Handle payment method attached to customer
     *
     * @param \Stripe\PaymentMethod $paymentMethod
     * @return void
     */
    private function handlePaymentMethodAttached($paymentMethod): void
    {
        Log::info('Payment method attached', [
            'payment_method_id' => $paymentMethod->id,
            'customer_id' => $paymentMethod->customer,
            'type' => $paymentMethod->type
        ]);

        // You can store payment method information if needed for future use
    }
}
