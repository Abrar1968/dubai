<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Cache key prefix for settings.
     */
    protected string $cachePrefix = 'site_settings_';

    /**
     * Get all settings for a section.
     */
    public function getAll(string $section = 'hajj'): Collection
    {
        return Cache::remember($this->cachePrefix . $section, 3600, function () use ($section) {
            return SiteSetting::where('section', $section)->get()->keyBy('key');
        });
    }

    /**
     * Get a single setting value.
     */
    public function get(string $key, string $section = 'hajj', mixed $default = null): mixed
    {
        $settings = $this->getAll($section);

        if ($settings->has($key)) {
            $setting = $settings->get($key);
            return $this->castValue($setting->value, $setting->type);
        }

        return $default;
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value, string $section = 'hajj', string $type = 'text'): SiteSetting
    {
        $setting = SiteSetting::updateOrCreate(
            ['key' => $key, 'section' => $section],
            [
                'value' => $this->prepareValue($value, $type),
                'type' => $type,
            ]
        );

        $this->clearCache($section);

        return $setting;
    }

    /**
     * Set multiple settings at once.
     */
    public function setMany(array $settings, string $section = 'hajj'): void
    {
        foreach ($settings as $key => $data) {
            $value = is_array($data) ? ($data['value'] ?? $data) : $data;
            $type = is_array($data) ? ($data['type'] ?? 'text') : 'text';

            $this->set($key, $value, $section, $type);
        }
    }

    /**
     * Delete a setting.
     */
    public function delete(string $key, string $section = 'hajj'): bool
    {
        $deleted = SiteSetting::where('key', $key)
            ->where('section', $section)
            ->delete();

        $this->clearCache($section);

        return $deleted > 0;
    }

    /**
     * Get settings grouped by category.
     */
    public function getGrouped(string $section = 'hajj'): array
    {
        return [
            'company' => [
                'company_name' => $this->get('company_name', $section, ''),
                'company_tagline' => $this->get('company_tagline', $section, ''),
                'company_email' => $this->get('company_email', $section, ''),
                'company_phone' => $this->get('company_phone', $section, ''),
                'company_whatsapp' => $this->get('company_whatsapp', $section, ''),
                'company_address' => $this->get('company_address', $section, ''),
                'company_logo' => $this->get('company_logo', $section, ''),
                'company_description' => $this->get('company_description', $section, ''),
                'company_mission' => $this->get('company_mission', $section, ''),
                'company_vision' => $this->get('company_vision', $section, ''),
                'company_values' => $this->get('company_values', $section, ''),
                'banner_image' => $this->get('banner_image', $section, ''),
                'hero_image' => $this->get('hero_image', $section, ''),
            ],
            'seo' => [
                'meta_title' => $this->get('meta_title', $section, ''),
                'meta_description' => $this->get('meta_description', $section, ''),
                'meta_keywords' => $this->get('meta_keywords', $section, ''),
                'og_image' => $this->get('og_image', $section, ''),
                'google_analytics' => $this->get('google_analytics', $section, ''),
            ],
            'social' => [
                'social_facebook' => $this->get('social_facebook', $section, ''),
                'social_twitter' => $this->get('social_twitter', $section, ''),
                'social_instagram' => $this->get('social_instagram', $section, ''),
                'social_linkedin' => $this->get('social_linkedin', $section, ''),
                'social_youtube' => $this->get('social_youtube', $section, ''),
                'social_tiktok' => $this->get('social_tiktok', $section, ''),
                'contact_description' => $this->get('contact_description', $section, ''),
            ],
            'booking' => [
                'booking_email' => $this->get('booking_email', $section, ''),
                'booking_phone' => $this->get('booking_phone', $section, ''),
                'terms_conditions' => $this->get('terms_conditions', $section, ''),
                'privacy_policy' => $this->get('privacy_policy', $section, ''),
                'cancellation_policy' => $this->get('cancellation_policy', $section, ''),
            ],
            'contact' => [
                'contact_email' => $this->get('contact_email', $section, ''),
                'contact_phone' => $this->get('contact_phone', $section, ''),
                'office_address' => $this->get('office_address', $section, ''),
                'office_hours' => $this->get('office_hours', $section, ''),
                'google_maps_embed' => $this->get('google_maps_embed', $section, ''),
                'contact_description' => $this->get('contact_description', $section, ''),
            ],
        ];
    }

    /**
     * Cast a stored value to its proper type.
     */
    protected function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Prepare a value for storage.
     */
    protected function prepareValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json', 'array' => json_encode($value),
            default => (string) $value,
        };
    }

    /**
     * Clear the settings cache for a section.
     */
    public function clearCache(string $section = 'hajj'): void
    {
        Cache::forget($this->cachePrefix . $section);
    }

    /**
     * Clear all settings cache.
     */
    public function clearAllCache(): void
    {
        foreach (['hajj', 'tour', 'typing'] as $section) {
            $this->clearCache($section);
        }
    }
}
