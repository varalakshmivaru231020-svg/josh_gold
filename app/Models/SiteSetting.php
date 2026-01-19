<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
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
