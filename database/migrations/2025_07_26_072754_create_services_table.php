<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // translation: title, description, body
            $table->text('languages')->nullable();
            $table->string('slug')->unique()->index();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
