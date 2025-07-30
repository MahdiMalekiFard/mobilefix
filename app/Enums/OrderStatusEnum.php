<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum OrderStatusEnum: string
{
    use EnumToArray;

    case PENDING = 'pending'; // در حال بررسی
    case REJECTED = 'rejected'; // رد شده
    case PROCESSING = 'processing'; // در حال تعمیر
    case FAILED = 'failed'; // ناموفق
    case COMPLETED = 'completed'; // در انتظار پرداخت
    case PAID = 'paid'; // پرداخت شده
    case DELIVERED = 'delivered'; // تحویل داده شده
    case CANCELLED_BY_USER = 'cancelled_by_user'; // لغو شده توسط کاربر

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'در حال بررسی',
            self::REJECTED => 'رد شده',
            self::PROCESSING => 'در حال تعمیر',
            self::FAILED => 'ناموفق',
            self::CANCELLED_BY_USER => 'لغو شده توسط کاربر',
            self::COMPLETED => 'در انتظار پرداخت',
            self::PAID => 'پرداخت شده',
            self::DELIVERED => 'تحویل داده شده',
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
            self::REJECTED => 'danger',
            self::PROCESSING => 'info',
            self::FAILED => 'danger',
            self::CANCELLED_BY_USER => 'danger',
            self::COMPLETED => 'success',
            self::PAID => 'success',
            self::DELIVERED => 'success',
        };
    }
}
