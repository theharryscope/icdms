<?php

namespace App\Livewire\Public;

use App\Models\GalleryAlbum;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.public.gallery-index', [
            'albums' => GalleryAlbum::with('images')->published()->latestFirst()->paginate(9),
            'title' => 'Gallery — InnoTech Future Foundation',
            'metaDescription' => 'Photos from foundation events, meetings and academy activities.',
        ])->layout('layouts.guest');
    }
}
