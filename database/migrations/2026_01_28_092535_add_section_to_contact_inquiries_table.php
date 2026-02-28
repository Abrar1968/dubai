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
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->enum('section', ['hajj', 'tour', 'typing', 'global'])->default('hajj')->after('id');
            $table->string('service_interested', 255)->nullable()->after('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropColumn(['section', 'service_interested']);
        });
    }
};
