<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
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

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image_path ? Storage::disk('public')->url($this->featured_image_path) : null;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where(function ($q) {
            $q->whereNull('published_at')->orWhere('published_at', '<=', now());
        });
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('published_at')->orderByDesc('created_at');
    }
}
