<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Page;

class PageManagement extends Component
{
    use WithPagination;

    public bool $showFormModal = false;
    public ?int $editingPageId = null;

    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public ?string $meta_description = null;
    public bool $show_in_header = false;
    public bool $show_in_footer = false;
    public int $nav_order = 0;
    public string $status = 'draft';

    public ?int $confirmingDeleteId = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|alpha_dash',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:255',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'nav_order' => 'integer|min:0',
            'status' => 'in:draft,published',
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $pageId): void
    {
        $page = Page::findOrFail($pageId);

        $this->editingPageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->meta_description = $page->meta_description;
        $this->show_in_header = $page->show_in_header;
        $this->show_in_footer = $page->show_in_footer;
        $this->nav_order = $page->nav_order;
        $this->status = $page->status;

        $this->showFormModal = true;
    }

    // Auto-fill the slug from the title only while creating a new page —
    // once a page exists, changing the title shouldn't silently break its URL.
    public function updatedTitle(string $value): void
    {
        if (! $this->editingPageId) {
            $this->slug = Page::generateUniqueSlug($value);
        }
    }

    public function save(): void
    {
        $this->validate(array_merge($this->rules(), [
            'slug' => 'required|string|max:150|alpha_dash|unique:pages,slug,' . ($this->editingPageId ?? 'NULL') . ',id',
        ]));

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'meta_description' => $this->meta_description,
            'show_in_header' => $this->show_in_header,
            'show_in_footer' => $this->show_in_footer,
            'nav_order' => $this->nav_order,
            'status' => $this->status,
        ];

        if ($this->editingPageId) {
            Page::findOrFail($this->editingPageId)->update($data);
            session()->flash('message', 'Page updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            Page::create($data);
            session()->flash('message', 'Page created successfully.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $pageId): void
    {
        $page = Page::findOrFail($pageId);
        $page->update(['status' => $page->status === 'published' ? 'draft' : 'published']);
    }

    public function confirmDelete(int $pageId): void
    {
        $this->confirmingDeleteId = $pageId;
    }

    public function delete(): void
    {
        if ($this->confirmingDeleteId) {
            Page::findOrFail($this->confirmingDeleteId)->delete();
            session()->flash('message', 'Page deleted.');
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
            'editingPageId', 'title', 'slug', 'content', 'meta_description',
            'show_in_header', 'show_in_footer', 'nav_order', 'status',
        ]);
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.admin.page-management', [
            'pages' => Page::orderBy('nav_order')->orderByDesc('created_at')->paginate(15),
        ])->layout('layouts.app');
    }
}
