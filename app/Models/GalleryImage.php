<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'gallery_album_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function getImageUrlAttribute(): string
    {
        return url('uploads/site/' . ltrim($this->image_path, '/'));
    }
}
