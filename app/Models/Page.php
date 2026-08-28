<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description',
        'show_in_header',
        'show_in_footer',
        'nav_order',
        'status',
        'created_by',
    ];

    protected $casts = [
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
        'nav_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Generate a unique slug from a title, appending -2, -3, etc. on collision.
     * Pass $ignoreId when editing an existing page so it doesn't collide with itself.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'page';
        $slug = $base;
        $i = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInHeader($query)
    {
        return $query->published()->where('show_in_header', true)->orderBy('nav_order');
    }

    public function scopeInFooter($query)
    {
        return $query->published()->where('show_in_footer', true)->orderBy('nav_order');
    }
}
