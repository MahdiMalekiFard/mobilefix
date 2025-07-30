<?php

use App\Enums\BooleanEnum;
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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            // translations (title - description)
            $table->text('languages')->nullable();
            $table->string('slug')->unique()->index();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->integer('ordering')->default(0)->comment('1 - 100');
            $table->softDeletes();

            $table->schemalessAttributes('config');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
