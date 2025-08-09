<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum TransactionTypeEnum: string
{
    use EnumToArray;

    case PAYMENT = 'payment';
    case REFUND = 'refund';
    case PARTIAL_REFUND = 'partial_refund';
    case CHARGEBACK = 'chargeback';
    case ADJUSTMENT = 'adjustment';

    public function title(): string
    {
        return match ($this) {
            self::PAYMENT => trans('transaction.type.payment'),
            self::REFUND => trans('transaction.type.refund'),
            self::PARTIAL_REFUND => trans('transaction.type.partial_refund'),
            self::CHARGEBACK => trans('transaction.type.chargeback'),
            self::ADJUSTMENT => trans('transaction.type.adjustment'),
        };
    }

    public function toArray(): array
    {
        return [
            'id' => $this->value,
            'title' => $this->title(),
        ];
    }

    public function isDebit(): bool
    {
        return in_array($this, [self::REFUND, self::PARTIAL_REFUND, self::CHARGEBACK]);
    }

    public function isCredit(): bool
    {
        return in_array($this, [self::PAYMENT, self::ADJUSTMENT]);
    }
}
