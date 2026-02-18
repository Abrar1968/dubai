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
        // Emirates table - Sharjah, Dubai, Abu Dhabi, etc.
        Schema::create('family_visa_emirates', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Sharjah, Dubai, etc.
            $table->string('slug')->unique();          // sharjah, dubai, etc.
            $table->text('description')->nullable();   // Emirate-specific info
            $table->text('intro_text')->nullable();    // Welcome/intro text for this emirate
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Visa types table - New Residency, Renewal, New Born Baby, Cancellation, etc.
        Schema::create('family_visa_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emirate_id')->constrained('family_visa_emirates')->onDelete('cascade');
            $table->string('name');                    // Apply for New Residency
            $table->string('slug');                    // new-residency
            $table->string('short_description')->nullable(); // Brief description for cards
            $table->text('long_description')->nullable();    // Full detailed content for the page
            $table->json('requirements')->nullable();        // List of requirements
            $table->json('documents')->nullable();           // Required documents
            $table->json('process_steps')->nullable();       // Step by step process
            $table->string('processing_time')->nullable();   // e.g., "3-5 working days"
            $table->string('price_range')->nullable();       // e.g., "AED 500 - 1500"
            $table->string('cta_text')->default('Apply Now');
            $table->string('cta_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['emirate_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_visa_types');
        Schema::dropIfExists('family_visa_emirates');
    }
};
