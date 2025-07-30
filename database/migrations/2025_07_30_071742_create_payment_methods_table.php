<?php

use App\Enums\BooleanEnum;
use App\Enums\PaymentProviderEnum;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            // translations (title - description)
            $table->text('languages')->nullable();
            $table->string('provider')->default(PaymentProviderEnum::STRIPE->value);
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->schemalessAttributes('config');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
