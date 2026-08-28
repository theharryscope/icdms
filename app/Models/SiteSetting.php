<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'contact_email',
        'contact_phone',
        'address',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'whatsapp_group_links',
    ];

    protected $casts = [
        'whatsapp_group_links' => 'array',
    ];

    const CACHE_KEY = 'site_settings';

    /**
     * Get the single settings row, creating a default one if it doesn't
     * exist yet. Cached forever — call clearCache() after any update.
     */
    public static function current(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::first() ?? static::create([
                'site_name' => 'InnoTech Future Foundation',
                'tagline' => 'Digital Skills, Regional Command, Verified Impact',
            ]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? url('uploads/site/' . ltrim($this->logo_path, '/')) : null;
    }

    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon_path ? url('uploads/site/' . ltrim($this->favicon_path, '/')) : null;
    }
}
