<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum PaymentProviderEnum: string
{
    use EnumToArray;

    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CREDIT = 'credit';

    public function title(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'PayPal',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CASH => 'Cash',
            self::CREDIT => 'Store Credit',
        };
    }

    public function toArray(): array
    {
        return [
            'id' => $this->value,
            'title' => $this->title(),
        ];
    }

    public function icon(): string
    {
        return match ($this) {
            self::STRIPE => 'fab fa-stripe',
            self::PAYPAL => 'fab fa-paypal',
            self::BANK_TRANSFER => 'fas fa-university',
            self::CASH => 'fas fa-money-bill',
            self::CREDIT => 'fas fa-credit-card',
        };
    }

    public function isOnline(): bool
    {
        return in_array($this, [self::STRIPE, self::PAYPAL]);
    }

    public function requiresGateway(): bool
    {
        return in_array($this, [self::STRIPE, self::PAYPAL]);
    }
}