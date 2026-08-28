<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class BlogManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public bool $showFormModal = false;
    public ?int $editingPostId = null;

    public string $title = '';
    public string $slug = '';
    public ?string $excerpt = null;
    public string $content = '';
    public string $status = 'draft';
    public $newFeaturedImage;
    public ?string $existingFeaturedImagePath = null;

    public ?int $confirmingDeleteId = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|alpha_dash',
            'excerpt' => 'nullable|string|max:255',
            'content' => 'required|string',
            'status' => 'in:draft,published',
            'newFeaturedImage' => 'nullable|image|max:2048',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $postId): void
    {
        $post = Post::findOrFail($postId);

        $this->editingPostId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->existingFeaturedImagePath = $post->featured_image_path;
        $this->newFeaturedImage = null;

        $this->showFormModal = true;
    }

    // Auto-fill the slug from the title only while creating a new post —
    // once a post exists, changing the title shouldn't silently break its URL.
    public function updatedTitle(string $value): void
    {
        if (! $this->editingPostId) {
            $this->slug = Post::generateUniqueSlug($value);
        }
    }

    public function save(): void
    {
        $this->validate(array_merge($this->rules(), [
            'slug' => 'required|string|max:150|alpha_dash|unique:posts,slug,' . ($this->editingPostId ?? 'NULL') . ',id',
        ]));

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
        ];

        if ($this->newFeaturedImage) {
            if ($this->existingFeaturedImagePath) {
                Storage::disk('public')->delete($this->existingFeaturedImagePath);
            }
            $data['featured_image_path'] = $this->newFeaturedImage->store('blog', 'public');
        }

        if ($this->editingPostId) {
            $post = Post::findOrFail($this->editingPostId);
            if ($this->status === 'published' && ! $post->published_at) {
                $data['published_at'] = now();
            }
            $post->update($data);
            session()->flash('message', 'Post updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            $data['published_at'] = $this->status === 'published' ? now() : null;
            Post::create($data);
            session()->flash('message', 'Post created successfully.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $postId): void
    {
        $post = Post::findOrFail($postId);
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $post->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($post->published_at ?? now()) : $post->published_at,
        ]);
    }

    public function confirmDelete(int $postId): void
    {
        $this->confirmingDeleteId = $postId;
    }

    public function delete(): void
    {
        if ($this->confirmingDeleteId) {
            $post = Post::findOrFail($this->confirmingDeleteId);
            if ($post->featured_image_path) {
                Storage::disk('public')->delete($post->featured_image_path);
            }
            $post->delete();
            session()->flash('message', 'Post deleted.');
        }
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingPostId', 'title', 'slug', 'excerpt', 'content',
            'status', 'newFeaturedImage', 'existingFeaturedImagePath',
        ]);
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.admin.blog-management', [
            'posts' => Post::orderByDesc('created_at')->paginate(15),
        ])->layout('layouts.app');
    }
}
