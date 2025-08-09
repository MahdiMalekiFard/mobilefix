<?php

namespace App\Services\Payment;

use App\Enums\PaymentProviderEnum;
use App\Services\Payment\Contracts\PaymentServiceInterface;
use App\Services\Payment\StripeService;
use App\Services\Payment\PayPalService;
use Illuminate\Support\Facades\App;

class PaymentServiceFactory
{
    /**
     * Create a payment service instance for the given provider
     *
     * @param PaymentProviderEnum $provider
     * @return PaymentServiceInterface
     * @throws \InvalidArgumentException
     */
    public static function create(PaymentProviderEnum $provider): PaymentServiceInterface
    {
        return match ($provider) {
            PaymentProviderEnum::STRIPE => App::make(StripeService::class),
            PaymentProviderEnum::PAYPAL => App::make(PayPalService::class),
            default => throw new \InvalidArgumentException("Unsupported payment provider: {$provider->value}")
        };
    }

    /**
     * Get all available payment providers
     *
     * @return array<PaymentProviderEnum>
     */
    public static function getAvailableProviders(): array
    {
        $providers = [];

        // Check Stripe configuration
        if (config('services.stripe.secret') && config('services.stripe.public')) {
            $providers[] = PaymentProviderEnum::STRIPE;
        }

        // Check PayPal configuration
        if (config('services.paypal.client_id') && config('services.paypal.client_secret')) {
            $providers[] = PaymentProviderEnum::PAYPAL;
        }

        return $providers;
    }

    /**
     * Get frontend configuration for all available providers
     *
     * @return array
     */
    public static function getFrontendConfigs(): array
    {
        $configs = [];
        
        foreach (static::getAvailableProviders() as $provider) {
            $service = static::create($provider);
            $configs[$provider->value] = $service->getFrontendConfig();
        }

        return $configs;
    }

    /**
     * Get the default payment provider
     *
     * @return PaymentProviderEnum
     */
    public static function getDefaultProvider(): PaymentProviderEnum
    {
        $available = static::getAvailableProviders();
        
        if (empty($available)) {
            throw new \RuntimeException('No payment providers are configured');
        }

        // Return the first available provider, or prefer Stripe if available
        return in_array(PaymentProviderEnum::STRIPE, $available) 
            ? PaymentProviderEnum::STRIPE 
            : $available[0];
    }
}
