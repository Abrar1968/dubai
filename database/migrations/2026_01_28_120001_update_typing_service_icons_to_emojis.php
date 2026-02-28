<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $iconMap = [
            'passport' => '🛂',
            'briefcase' => '💼',
            'building' => '🏢',
            'home' => '🏠',
            'users' => '👨‍👩‍👧‍👦',
            'heart' => '❤️',
            'shield' => '🛡️',
            'file-check' => '📋',
            'calculator' => '🧮',
            'building-2' => '🏛️',
            'id-card' => '🪪',
            'credit-card' => '💳',
        ];

        foreach ($iconMap as $oldIcon => $newIcon) {
            DB::table('typing_services')
                ->where('icon', $oldIcon)
                ->update(['icon' => $newIcon]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $iconMap = [
            '🛂' => 'passport',
            '💼' => 'briefcase',
            '🏢' => 'building',
            '🏠' => 'home',
            '👨‍👩‍👧‍👦' => 'users',
            '❤️' => 'heart',
            '🛡️' => 'shield',
            '📋' => 'file-check',
            '🧮' => 'calculator',
            '🏛️' => 'building-2',
            '🪪' => 'id-card',
            '💳' => 'credit-card',
        ];

        foreach ($iconMap as $newIcon => $oldIcon) {
            DB::table('typing_services')
                ->where('icon', $newIcon)
                ->update(['icon' => $oldIcon]);
        }
    }
};
