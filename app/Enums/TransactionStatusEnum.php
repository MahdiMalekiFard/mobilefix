<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum TransactionStatusEnum: string
{
    use EnumToArray;

    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case PARTIALLY_REFUNDED = 'partially_refunded';

    public function title(): string
    {
        return match ($this) {
            self::PENDING => trans('transaction.enum.pending'),
            self::PROCESSING => trans('transaction.enum.processing'),
            self::COMPLETED => trans('transaction.enum.completed'),
            self::FAILED => trans('transaction.enum.failed'),
            self::CANCELLED => trans('transaction.enum.cancelled'),
            self::REFUNDED => trans('transaction.enum.refunded'),
            self::PARTIALLY_REFUNDED => trans('transaction.enum.partially_refunded'),
        };
    }

    public function toArray(): array
    {
        return [
            'id' => $this->value,
            'title' => $this->title(),
        ];
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::CANCELLED => 'secondary',
            self::REFUNDED => 'dark',
            self::PARTIALLY_REFUNDED => 'warning',
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isFailed(): bool
    {
        return in_array($this, [self::FAILED, self::CANCELLED]);
    }

    public function isPending(): bool
    {
        return in_array($this, [self::PENDING, self::PROCESSING]);
    }

    public function isRefunded(): bool
    {
        return in_array($this, [self::REFUNDED, self::PARTIALLY_REFUNDED]);
    }
}
