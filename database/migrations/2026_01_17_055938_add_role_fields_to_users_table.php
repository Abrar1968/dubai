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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'user'])->default('user')->after('email');
            $table->string('phone', 50)->nullable()->after('role');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('passport_number', 50)->nullable()->after('date_of_birth');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->text('address')->nullable()->after('passport_expiry');
            $table->string('emergency_contact', 255)->nullable()->after('address');
            $table->string('profile_photo', 500)->nullable()->after('emergency_contact');
            $table->boolean('is_active')->default(true)->after('profile_photo');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->softDeletes();

            $table->index('role');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'role',
                'phone',
                'date_of_birth',
                'passport_number',
                'passport_expiry',
                'address',
                'emergency_contact',
                'profile_photo',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};
