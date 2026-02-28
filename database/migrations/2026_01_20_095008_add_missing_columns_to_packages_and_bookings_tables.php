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
        // Add missing columns to packages table
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'duration_nights')) {
                $table->unsignedInteger('duration_nights')->nullable()->after('duration_days');
            }
            if (!Schema::hasColumn('packages', 'short_description')) {
                $table->string('short_description', 500)->nullable()->after('type');
            }
            if (!Schema::hasColumn('packages', 'discounted_price')) {
                $table->decimal('discounted_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('packages', 'departure_location')) {
                $table->string('departure_location', 255)->nullable()->after('duration_nights');
            }
            if (!Schema::hasColumn('packages', 'departure_date')) {
                $table->date('departure_date')->nullable()->after('departure_location');
            }
            if (!Schema::hasColumn('packages', 'return_date')) {
                $table->date('return_date')->nullable()->after('departure_date');
            }
            if (!Schema::hasColumn('packages', 'available_slots')) {
                $table->unsignedInteger('available_slots')->nullable()->after('max_capacity');
            }
        });

        // Add missing columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'travelers_count')) {
                $table->unsignedInteger('travelers_count')->default(1)->after('status');
            }
            if (!Schema::hasColumn('bookings', 'contact_name')) {
                $table->string('contact_name', 255)->nullable()->after('special_requests');
            }
            if (!Schema::hasColumn('bookings', 'contact_email')) {
                $table->string('contact_email', 255)->nullable()->after('contact_name');
            }
            if (!Schema::hasColumn('bookings', 'contact_phone')) {
                $table->string('contact_phone', 50)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('paid_amount');
            }
            if (!Schema::hasColumn('bookings', 'payment_reference')) {
                $table->string('payment_reference', 255)->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['duration_nights', 'short_description', 'discounted_price', 'departure_location', 'departure_date', 'return_date', 'available_slots']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['travelers_count', 'contact_name', 'contact_email', 'contact_phone', 'payment_method', 'payment_reference']);
        });
    }
};
