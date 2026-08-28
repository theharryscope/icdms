<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Page;

class ShowPage extends Component
{
    public Page $page;

    public function mount(Page $page): void
    {
        abort_unless($page->status === 'published', 404);
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.public.show-page', [
            'title' => $this->page->title,
            'metaDescription' => $this->page->meta_description,
        ])->layout('layouts.guest');
    }
}
