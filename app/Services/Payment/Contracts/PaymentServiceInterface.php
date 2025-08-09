<?php

namespace App\Services\Payment\Contracts;

use App\Models\Order;
use App\Models\Transaction;

interface PaymentServiceInterface
{
    /**
     * Create a payment transaction for an order
     *
     * @param Order $order
     * @param array $options
     * @return Transaction
     */
    public function createPayment(Order $order, array $options = []): Transaction;

    /**
     * Process a payment for the given transaction
     *
     * @param Transaction $transaction
     * @param array $paymentData
     * @return array
     */
    public function processPayment(Transaction $transaction, array $paymentData = []): array;

    /**
     * Verify a payment transaction
     *
     * @param Transaction $transaction
     * @param string $externalId
     * @return bool
     */
    public function verifyPayment(Transaction $transaction, string $externalId): bool;

    /**
     * Handle successful payment callback
     *
     * @param Transaction $transaction
     * @param array $gatewayResponse
     * @return void
     */
    public function handleSuccessfulPayment(Transaction $transaction, array $gatewayResponse = []): void;

    /**
     * Handle failed payment callback
     *
     * @param Transaction $transaction
     * @param array $gatewayResponse
     * @return void
     */
    public function handleFailedPayment(Transaction $transaction, array $gatewayResponse = []): void;

    /**
     * Process a refund for the given transaction
     *
     * @param Transaction $transaction
     * @param float $amount
     * @param string|null $reason
     * @return Transaction
     */
    public function processRefund(Transaction $transaction, float $amount, ?string $reason = null): Transaction;

    /**
     * Get the configuration for the frontend
     *
     * @return array
     */
    public function getFrontendConfig(): array;

    /**
     * Get the payment provider name
     *
     * @return string
     */
    public function getProviderName(): string;
}
