<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected static ?array $settingsCache = null;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get($key, $default = null)
    {
        if (static::$settingsCache === null) {
            static::$settingsCache = self::query()
                ->pluck('value', 'key')
                ->all();
        }

        return static::$settingsCache[$key] ?? $default;
    }

    public static function set($key, $value, $type = 'text')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        static::$settingsCache = null;

        return $setting;
    }

    public static function getJson($key, $default = [])
    {
        $value = self::get($key);
        return $value ? json_decode($value, true) : $default;
    }

    public static function setJson($key, $value)
    {
        return self::set($key, json_encode($value), 'json');
    }
}
