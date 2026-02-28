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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('article_categories')->onDelete('set null');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('image', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('category_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
