<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    use EnumToArray;

    case PENDING           = 'pending'; // در حال بررسی
    case RECEIVED          = 'received'; // دریافت شده
    case REJECTED          = 'rejected'; // رد شده
    case PROCESSING        = 'processing'; // در حال تعمیر
    case FAILED            = 'failed'; // ناموفق
    case COMPLETED         = 'completed'; // در انتظار پرداخت
    case PAID              = 'paid'; // پرداخت شده
    case DELIVERED         = 'delivered'; // تحویل داده شده
    case CANCELLED_BY_USER = 'cancelled_by_user'; // لغو شده توسط کاربر

    public function title(): string
    {
        return match ($this) {
            self::PENDING           => trans('order.enum.pending'),
            self::RECEIVED          => trans('order.enum.received'),
            self::REJECTED          => trans('order.enum.rejected'),
            self::PROCESSING        => trans('order.enum.processing'),
            self::FAILED            => trans('order.enum.failed'),
            self::CANCELLED_BY_USER => trans('order.enum.cancelled_by_user'),
            self::COMPLETED         => trans('order.enum.completed'),
            self::PAID              => trans('order.enum.paid'),
            self::DELIVERED         => trans('order.enum.delivered'),
        };
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->value,
            'title' => $this->title(),
        ];
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING           => 'warning',
            self::RECEIVED          => 'info',
            self::REJECTED          => 'danger',
            self::PROCESSING        => 'info',
            self::FAILED            => 'danger',
            self::CANCELLED_BY_USER => 'danger',
            self::COMPLETED         => 'success',
            self::PAID              => 'success',
            self::DELIVERED         => 'success',
        };
    }
}
