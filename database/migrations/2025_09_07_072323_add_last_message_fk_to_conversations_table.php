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
        Schema::table('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->foreign('last_message_id')->references('id')->on('messages')->nullOnDelete();
            $table->index(['last_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no need to reverse
    }
};


