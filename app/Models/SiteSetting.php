<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name', 'tagline', 'email', 'phone', 'address', 'about',
        'facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url',
        'logo_path', 'footer_logo_path', 'footer_text'
    ];

    /**
     * Get a setting by key or return default
     */
    public static function get($key, $default = null)
    {
        $setting = self::first();
        return $setting?->{$key} ?? $default;
    }
}
