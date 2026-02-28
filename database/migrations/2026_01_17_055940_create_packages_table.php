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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->enum('type', ['hajj', 'umrah', 'tour'])->default('hajj');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('AED');
            $table->unsignedInteger('duration_days');
            $table->string('image', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('hotel_details')->nullable();
            $table->json('departure_dates')->nullable();
            $table->unsignedInteger('max_capacity')->nullable();
            $table->unsignedInteger('current_bookings')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
