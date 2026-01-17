<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'key',
        'value',
        'type',
    ];

    /**
     * Scope to filter by section.
     */
    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to filter global settings.
     */
    public function scopeGlobal($query)
    {
        return $query->where('section', 'global');
    }

    /**
     * Get setting value with type casting.
     */
    public function getCastedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get a setting by section and key.
     */
    public static function get(string $key, string $section = 'global', $default = null)
    {
        $setting = self::where('section', $section)->where('key', $key)->first();

        return $setting ? $setting->casted_value : $default;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value, string $section = 'global', string $type = 'string'): self
    {
        if ($type === 'json' && is_array($value)) {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        return self::updateOrCreate(
            ['section' => $section, 'key' => $key],
            ['value' => (string) $value, 'type' => $type]
        );
    }

    /**
     * Get all settings for a section as key-value array.
     */
    public static function allForSection(string $section): array
    {
        return self::section($section)
            ->get()
            ->pluck('casted_value', 'key')
            ->toArray();
    }
}
