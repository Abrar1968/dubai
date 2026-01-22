<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global Settings
        $globalSettings = [
            ['key' => 'site_name', 'value' => 'Dubai Tourism & Travel', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Your Journey to Sacred Places Begins Here', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'info@dubaitravel.com', 'type' => 'string'],
            ['key' => 'contact_phone', 'value' => '+971 4 XXX XXXX', 'type' => 'string'],
            ['key' => 'whatsapp_number', 'value' => '+971 50 XXX XXXX', 'type' => 'string'],
            ['key' => 'address', 'value' => 'Dubai, United Arab Emirates', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/dubaitravel', 'type' => 'string'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/dubaitravel', 'type' => 'string'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/dubaitravel', 'type' => 'string'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/dubaitravel', 'type' => 'string'],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'string'],
        ];

        foreach ($globalSettings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], 'global', $setting['type']);
        }

        $this->command->info('Global settings seeded: ' . count($globalSettings) . ' settings');

        // Hajj Section Settings
        $hajjSettings = [
            ['key' => 'hero_title', 'value' => 'Hajj & Umrah Packages', 'type' => 'string'],
            ['key' => 'hero_subtitle', 'value' => 'Experience the spiritual journey of a lifetime with our carefully curated packages', 'type' => 'string'],
            ['key' => 'about_title', 'value' => 'About Our Hajj Services', 'type' => 'string'],
            ['key' => 'about_description', 'value' => 'We are dedicated to providing exceptional Hajj and Umrah services to pilgrims from around the world.', 'type' => 'text'],
            ['key' => 'show_testimonials', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'show_team', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'packages_per_page', 'value' => '9', 'type' => 'integer'],
        ];

        foreach ($hajjSettings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], 'hajj', $setting['type']);
        }

        $this->command->info('Hajj settings seeded: ' . count($hajjSettings) . ' settings');

        // Tour Section Settings (placeholder)
        $tourSettings = [
            ['key' => 'hero_title', 'value' => 'Tour & Travel Packages', 'type' => 'string'],
            ['key' => 'hero_subtitle', 'value' => 'Discover amazing destinations with our travel packages', 'type' => 'string'],
            ['key' => 'packages_per_page', 'value' => '12', 'type' => 'integer'],
        ];

        foreach ($tourSettings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], 'tour', $setting['type']);
        }

        $this->command->info('Tour settings seeded: ' . count($tourSettings) . ' settings');

        // Typing Section Settings (placeholder)
        $typingSettings = [
            ['key' => 'service_title', 'value' => 'Professional Typing Services', 'type' => 'string'],
            ['key' => 'service_description', 'value' => 'Fast and reliable document typing and processing services', 'type' => 'text'],
        ];

        foreach ($typingSettings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], 'typing', $setting['type']);
        }

        $this->command->info('Typing settings seeded: ' . count($typingSettings) . ' settings');
    }
}
