<?php

namespace App\Livewire\Admin;

use App\Models\GalleryAlbum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GalleryManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showFormModal = false;
    public ?int $editingAlbumId = null;
    public string $title = '';
    public string $slug = '';
    public ?string $description = null;
    public string $category = 'events';
    public string $status = 'draft';
    public $images = [];
    public ?int $confirmingDeleteId = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|alpha_dash',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:events,meetings,academy',
            'status' => 'required|in:draft,published',
            'images' => $this->editingAlbumId ? 'nullable|array' : 'required|array|min:1',
            'images.*' => 'image|max:5120',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);
        $this->editingAlbumId = $album->id;
        $this->title = $album->title;
        $this->slug = $album->slug;
        $this->description = $album->description;
        $this->category = $album->category;
        $this->status = $album->status;
        $this->images = [];
        $this->showFormModal = true;
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->editingAlbumId) {
            $this->slug = GalleryAlbum::generateUniqueSlug($value);
        }
    }

    public function save(): void
    {
        $this->validate(array_merge($this->rules(), [
            'slug' => 'required|string|max:150|alpha_dash|unique:gallery_albums,slug,' . ($this->editingAlbumId ?? 'NULL') . ',id',
        ]));

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
        ];

        if ($this->editingAlbumId) {
            $album = GalleryAlbum::findOrFail($this->editingAlbumId);
            if ($this->status === 'published' && ! $album->published_at) {
                $data['published_at'] = now();
            }
            $album->update($data);
        } else {
            $data['created_by'] = auth()->id();
            $data['published_at'] = $this->status === 'published' ? now() : null;
            $album = GalleryAlbum::create($data);
        }

        foreach ($this->images as $index => $image) {
            $album->images()->create([
                'image_path' => $image->store('gallery', 'site_uploads'),
                'sort_order' => $index,
            ]);
        }

        session()->flash('message', $this->editingAlbumId ? 'Gallery updated successfully.' : 'Gallery created successfully.');
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);
        $status = $album->status === 'published' ? 'draft' : 'published';
        $album->update([
            'status' => $status,
            'published_at' => $status === 'published' ? ($album->published_at ?? now()) : $album->published_at,
        ]);
    }

    public function confirmDelete(int $albumId): void
    {
        $this->confirmingDeleteId = $albumId;
    }

    public function delete(): void
    {
        if ($this->confirmingDeleteId) {
            $album = GalleryAlbum::with('images')->findOrFail($this->confirmingDeleteId);
            foreach ($album->images as $image) {
                Storage::disk('site_uploads')->delete($image->image_path);
            }
            $album->delete();
            session()->flash('message', 'Gallery deleted.');
        }
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['editingAlbumId', 'title', 'slug', 'description', 'category', 'status', 'images']);
        $this->category = 'events';
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.admin.gallery-management', [
            'albums' => GalleryAlbum::withCount('images')->latest()->paginate(12),
        ])->layout('layouts.app');
    }
}
