<?php

namespace App\Enums;

enum PaymentProviderEnum: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';

    public function title(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'Paypal',
        };
    }

    public function toArray(): array
    {
        return [
            'id' => $this->value,
            'title' => $this->title(),
        ];
    }
}
