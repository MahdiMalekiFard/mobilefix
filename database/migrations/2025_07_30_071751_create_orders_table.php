<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('total')->default(0);
            $table->string('status')->default(OrderStatusEnum::PENDING->value)->comment('pending - rejected - processing - failed - cancelled_by_user - completed - paid - delivered');
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->nullable();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('device_id')->constrained('devices');
            $table->text('user_note')->nullable();
            $table->text('admin_note')->nullable();

            // (name - email - phone)
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
        Schema::dropIfExists('orders');
    }
};
