<?php

namespace App\Livewire\Public;

use App\Models\GalleryAlbum;
use Livewire\Component;

class GalleryShow extends Component
{
    public GalleryAlbum $album;

    public function mount(GalleryAlbum $album): void
    {
        abort_unless($album->status === 'published' && (! $album->published_at || $album->published_at <= now()), 404);
        $this->album = $album->load('images');
    }

    public function render()
    {
        return view('livewire.public.gallery-show', [
            'title' => $this->album->title,
            'metaDescription' => $this->album->description,
        ])->layout('layouts.guest');
    }
}
