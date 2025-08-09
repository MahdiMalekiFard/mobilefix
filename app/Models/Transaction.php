<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $transaction_id
 * @property string|null $external_id
 * @property int $order_id
 * @property int|null $user_id
 * @property PaymentProviderEnum $payment_provider
 * @property string|null $payment_method
 * @property TransactionStatusEnum $status
 * @property TransactionTypeEnum $type
 * @property float $amount
 * @property string $currency
 * @property float|null $fee
 * @property float|null $net_amount
 * @property array|null $gateway_response
 * @property string|null $gateway_transaction_id
 * @property string|null $gateway_reference
 * @property string|null $customer_email
 * @property string|null $customer_name
 * @property array|null $billing_details
 * @property array|null $payment_method_details
 * @property \Carbon\Carbon|null $processed_at
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon|null $failed_at
 * @property string|null $notes
 * @property string|null $failure_reason
 * @property array|null $metadata
 * @property int|null $parent_transaction_id
 * @property bool $is_refundable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'external_id',
        'order_id',
        'user_id',
        'payment_provider',
        'payment_method',
        'status',
        'type',
        'amount',
        'currency',
        'fee',
        'net_amount',
        'gateway_response',
        'gateway_transaction_id',
        'gateway_reference',
        'customer_email',
        'customer_name',
        'billing_details',
        'payment_method_details',
        'processed_at',
        'completed_at',
        'failed_at',
        'notes',
        'failure_reason',
        'metadata',
        'parent_transaction_id',
        'is_refundable',
    ];

    protected $casts = [
        'payment_provider' => PaymentProviderEnum::class,
        'status' => TransactionStatusEnum::class,
        'type' => TransactionTypeEnum::class,
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'billing_details' => 'array',
        'payment_method_details' => 'array',
        'metadata' => 'array',
        'is_refundable' => 'boolean',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = static::generateTransactionId();
            }
        });
    }

    /**
     * Model Relations --------------------------------------------------------------------------
     */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'parent_transaction_id');
    }

    public function childTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_transaction_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_transaction_id')
            ->whereIn('type', [TransactionTypeEnum::REFUND, TransactionTypeEnum::PARTIAL_REFUND]);
    }

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    public function scopeSuccessful($query)
    {
        return $query->where('status', TransactionStatusEnum::COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', [TransactionStatusEnum::FAILED, TransactionStatusEnum::CANCELLED]);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [TransactionStatusEnum::PENDING, TransactionStatusEnum::PROCESSING]);
    }

    public function scopeByProvider($query, PaymentProviderEnum $provider)
    {
        return $query->where('payment_provider', $provider);
    }

    public function scopePayments($query)
    {
        return $query->where('type', TransactionTypeEnum::PAYMENT);
    }

    public function scopeRefunds($query)
    {
        return $query->whereIn('type', [TransactionTypeEnum::REFUND, TransactionTypeEnum::PARTIAL_REFUND]);
    }

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . strtoupper($this->currency);
    }

    public function getFormattedFeeAttribute(): string
    {
        if (!$this->fee) {
            return 'N/A';
        }
        return number_format($this->fee, 2) . ' ' . strtoupper($this->currency);
    }

    public function getFormattedNetAmountAttribute(): string
    {
        if (!$this->net_amount) {
            return 'N/A';
        }
        return number_format($this->net_amount, 2) . ' ' . strtoupper($this->currency);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->attributes['customer_name'] ?? 
               $this->user?->name ?? 
               'Guest Customer';
    }

    public function getCustomerEmailAttribute(): string
    {
        return $this->attributes['customer_email'] ?? 
               $this->user?->email ?? 
               'N/A';
    }

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */

    public static function generateTransactionId(): string
    {
        do {
            $id = 'TXN-' . date('Ymd') . '-' . strtoupper(Str::random(8));
        } while (static::where('transaction_id', $id)->exists());

        return $id;
    }

    public function markAsProcessing(): void
    {
        $this->update([
            'status' => TransactionStatusEnum::PROCESSING,
            'processed_at' => now(),
        ]);
    }

    public function markAsCompleted(array $additionalData = []): void
    {
        $updateData = array_merge([
            'status' => TransactionStatusEnum::COMPLETED,
            'completed_at' => now(),
            'failed_at' => null,
            'failure_reason' => null,
        ], $additionalData);

        $this->update($updateData);
    }

    public function markAsFailed(string $reason = null, array $additionalData = []): void
    {
        $updateData = array_merge([
            'status' => TransactionStatusEnum::FAILED,
            'failed_at' => now(),
            'failure_reason' => $reason,
        ], $additionalData);

        $this->update($updateData);
    }

    public function markAsCancelled(string $reason = null): void
    {
        $this->update([
            'status' => TransactionStatusEnum::CANCELLED,
            'failed_at' => now(),
            'failure_reason' => $reason ?? 'Transaction cancelled',
        ]);
    }

    public function canBeRefunded(): bool
    {
        return $this->is_refundable && 
               $this->status === TransactionStatusEnum::COMPLETED &&
               $this->type === TransactionTypeEnum::PAYMENT;
    }

    public function getTotalRefundedAmount(): float
    {
        return $this->refunds()
            ->where('status', TransactionStatusEnum::COMPLETED)
            ->sum('amount');
    }

    public function getRemainingRefundableAmount(): float
    {
        if (!$this->canBeRefunded()) {
            return 0;
        }

        return $this->amount - $this->getTotalRefundedAmount();
    }

    public function isFullyRefunded(): bool
    {
        return $this->getTotalRefundedAmount() >= $this->amount;
    }

    public function createRefund(float $amount, string $reason = null): self
    {
        if (!$this->canBeRefunded()) {
            throw new \Exception('Transaction cannot be refunded');
        }

        $remainingAmount = $this->getRemainingRefundableAmount();
        if ($amount > $remainingAmount) {
            throw new \Exception('Refund amount exceeds remaining refundable amount');
        }

        $isPartialRefund = $amount < $remainingAmount;

        return static::create([
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'payment_provider' => $this->payment_provider,
            'payment_method' => $this->payment_method,
            'status' => TransactionStatusEnum::PENDING,
            'type' => $isPartialRefund ? TransactionTypeEnum::PARTIAL_REFUND : TransactionTypeEnum::REFUND,
            'amount' => $amount,
            'currency' => $this->currency,
            'customer_email' => $this->customer_email,
            'customer_name' => $this->customer_name,
            'parent_transaction_id' => $this->id,
            'notes' => $reason,
            'is_refundable' => false,
        ]);
    }
}
