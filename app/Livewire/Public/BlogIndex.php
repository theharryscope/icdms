<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class BlogIndex extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.public.blog-index', [
            'posts' => Post::published()->latestFirst()->paginate(9),
            'title' => 'Blog — News & Stories',
            'metaDescription' => 'News, updates and stories from the field — programs, projects and community impact.',
        ])->layout('layouts.guest');
    }
}
