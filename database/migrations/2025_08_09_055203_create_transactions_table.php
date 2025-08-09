<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Add payment-related columns
            $table->string('transaction_id')->unique(); // Our internal transaction ID
            $table->string('external_id')->nullable()->index(); // Payment provider's transaction ID
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Payment details
            $table->string('payment_provider')->default('stripe'); // stripe, paypal, etc.
            $table->string('payment_method')->nullable(); // card, paypal, bank_transfer, etc.
            $table->string('status')->default('pending'); // pending, processing, completed, failed, cancelled, refunded
            $table->string('type')->default('payment'); // payment, refund, partial_refund
            
            // Amount details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('fee', 10, 2)->nullable(); // Payment provider fee
            $table->decimal('net_amount', 10, 2)->nullable(); // Amount after fees
            
            // Payment provider specific data
            $table->json('gateway_response')->nullable(); // Full response from payment gateway
            $table->string('gateway_transaction_id')->nullable()->index(); // Provider's transaction ID
            $table->string('gateway_reference')->nullable(); // Additional reference
            
            // Customer details (for guest payments)
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->json('billing_details')->nullable();
            
            // Payment method details
            $table->json('payment_method_details')->nullable(); // Card last 4, PayPal email, etc.
            
            // Timing
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            
            // Additional metadata
            $table->text('notes')->nullable();
            $table->string('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            
            // Refund information
            $table->foreignId('parent_transaction_id')->nullable()->constrained('transactions')->onDelete('set null'); // For refunds
            $table->boolean('is_refundable')->default(true);
            
            // Add indexes
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['payment_provider', 'status']);
            $table->index(['created_at']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
