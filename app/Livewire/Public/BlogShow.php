<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Post;

class BlogShow extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless($post->status === 'published' && (! $post->published_at || $post->published_at <= now()), 404);
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.public.blog-show', [
            'title' => $this->post->title,
            'metaDescription' => $this->post->excerpt,
            'relatedPosts' => Post::published()->where('id', '!=', $this->post->id)->latestFirst()->take(3)->get(),
        ])->layout('layouts.guest');
    }
}
