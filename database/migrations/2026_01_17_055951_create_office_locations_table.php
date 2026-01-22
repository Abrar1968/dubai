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
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->enum('section', ['hajj', 'tour', 'typing', 'global'])->default('global');
            $table->string('name', 150);
            $table->text('address');
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->decimal('map_lat', 10, 8)->nullable();
            $table->decimal('map_lng', 11, 8)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('section');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_locations');
    }
};
