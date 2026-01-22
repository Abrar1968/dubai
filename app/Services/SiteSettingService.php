<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Collection;

class SiteSettingService
{
    /**
     * Get all settings.
     */
    public function list(?string $section = null): Collection
    {
        $query = SiteSetting::orderBy('section')->orderBy('key');

        if ($section) {
            $query->section($section);
        }

        return $query->get();
    }

    /**
     * Get a setting value.
     */
    public function get(string $key, string $section = 'global', $default = null)
    {
        return SiteSetting::get($key, $section, $default);
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, $value, string $section = 'global', string $type = 'string'): SiteSetting
    {
        return SiteSetting::set($key, $value, $section, $type);
    }

    /**
     * Get all settings for a section.
     */
    public function getForSection(string $section): array
    {
        return SiteSetting::allForSection($section);
    }

    /**
     * Update multiple settings at once.
     */
    public function updateBatch(array $settings, string $section = 'global'): void
    {
        foreach ($settings as $key => $value) {
            $type = is_bool($value) ? 'boolean'
                : (is_int($value) ? 'integer'
                    : (is_array($value) ? 'json' : 'string'));

            $this->set($key, $value, $section, $type);
        }
    }

    /**
     * Delete a setting.
     */
    public function delete(string $key, string $section = 'global'): bool
    {
        return SiteSetting::where('section', $section)->where('key', $key)->delete() > 0;
    }

    /**
     * Get a setting by ID.
     */
    public function getById(int $id): SiteSetting
    {
        return SiteSetting::findOrFail($id);
    }

    /**
     * Update a setting by model.
     */
    public function update(SiteSetting $setting, array $data): SiteSetting
    {
        $setting->update($data);

        return $setting->fresh();
    }

    /**
     * Delete a setting by model.
     */
    public function deleteModel(SiteSetting $setting): bool
    {
        return $setting->delete();
    }

    /**
     * Get global settings.
     */
    public function getGlobalSettings(): array
    {
        return $this->getForSection('global');
    }

    /**
     * Get section-specific settings.
     */
    public function getHajjSettings(): array
    {
        return $this->getForSection('hajj');
    }

    public function getTourSettings(): array
    {
        return $this->getForSection('tour');
    }

    public function getTypingSettings(): array
    {
        return $this->getForSection('typing');
    }
}
