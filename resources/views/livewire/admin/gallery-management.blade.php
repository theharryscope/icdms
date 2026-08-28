<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Content</span>
            <h1 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Gallery</h1>
            <p class="text-sm text-ink-muted mt-1">Publish photos from events, meetings and academy activities.</p>
        </div>
        <button wire:click="openCreateModal" class="px-5 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">+ New Gallery</button>
    </div>

    @if(session('message'))
        <div class="bg-teal-soft border border-teal-dim rounded-lg px-4 py-3 text-xs text-teal font-medium">{{ session('message') }}</div>
    @endif

    <div class="bg-surface border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-mono font-semibold tracking-wider">
                    <tr><th class="px-6 py-3">Gallery</th><th class="px-6 py-3">Category</th><th class="px-6 py-3">Photos</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($albums as $album)
                        <tr class="hover:bg-canvas/40 transition">
                            <td class="px-6 py-4 font-semibold text-ink">{{ $album->title }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-ochre-soft text-ochre border border-ochre-dim">{{ $album->category }}</span></td>
                            <td class="px-6 py-4 text-ink-muted">{{ $album->images_count }}</td>
                            <td class="px-6 py-4"><button wire:click="togglePublish({{ $album->id }})" class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase {{ $album->status === 'published' ? 'bg-teal-soft text-teal border border-teal-dim' : 'bg-surface-raised text-ink-muted border border-line' }}">{{ $album->status }}</button></td>
                            <td class="px-6 py-4 text-right space-x-3"><button wire:click="openEditModal({{ $album->id }})" class="text-teal hover:text-teal/80 font-bold">Edit</button><button wire:click="confirmDelete({{ $album->id }})" class="text-red-400 hover:text-red-300 font-bold">Delete</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-ink-muted">No galleries created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($albums->hasPages())<div class="px-6 py-4 border-t border-line">{{ $albums->links() }}</div>@endif
    </div>

    @if($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-canvas/80" wire:click.self="$set('showFormModal', false)">
            <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-surface border border-line rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between"><h2 class="text-lg font-display font-bold text-ink">{{ $editingAlbumId ? 'Edit Gallery' : 'New Gallery' }}</h2><button wire:click="$set('showFormModal', false)" class="text-ink-muted text-xl">&times;</button></div>
                <form wire:submit.prevent="save" class="space-y-4">
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Title</label><input type="text" wire:model.live.debounce.400ms="title" placeholder="e.g. 2026 Digital Skills Academy Graduation" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">@error('title')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">URL Slug</label><div class="flex items-center bg-canvas border border-line rounded-lg overflow-hidden"><span class="px-3 text-ink-muted/60 text-xs font-mono">/gallery/</span><input type="text" wire:model="slug" class="flex-1 bg-transparent px-1 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none font-mono"></div>@error('slug')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Category</label><select wire:model="category" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre"><option value="events">Events</option><option value="meetings">Meetings</option><option value="academy">Academy</option></select>@error('category')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Description (optional)</label><textarea wire:model="description" rows="3" placeholder="A short description of this gallery" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre"></textarea>@error('description')<span class="text-red-400 text-[10px]">{{ $message }}</span>@enderror</div>
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Photos {{ $editingAlbumId ? '(optional: add more)' : '' }}</label><input type="file" wire:model="images" multiple accept="image/*" class="w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-surface-raised file:text-ink file:text-xs file:font-bold"><p class="text-[10px] text-ink-muted/70 mt-1">Select one or more images, up to 5MB each.</p>@error('images')<span class="text-red-400 text-[10px] block">{{ $message }}</span>@enderror @error('images.*')<span class="text-red-400 text-[10px] block">{{ $message }}</span>@enderror</div>
                    @if($images)<div class="grid grid-cols-4 gap-2">@foreach($images as $image)<img src="{{ $image->temporaryUrl() }}" class="aspect-square object-cover rounded-lg" alt="Preview">@endforeach</div>@endif
                    <div><label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Status</label><select wire:model="status" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre"><option value="draft">Draft</option><option value="published">Published</option></select></div>
                    <div class="flex justify-end gap-3 pt-2 border-t border-line"><button type="button" wire:click="$set('showFormModal', false)" class="px-5 py-2.5 bg-surface-raised text-ink-muted rounded-lg text-xs font-bold">Cancel</button><button type="submit" class="px-6 py-2.5 bg-ochre text-canvas rounded-lg text-xs font-bold" wire:loading.attr="disabled" wire:target="save,images">{{ $editingAlbumId ? 'Save Changes' : 'Create Gallery' }}</button></div>
                </form>
            </div>
        </div>
    @endif

    @if($confirmingDeleteId)<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-canvas/80"><div class="w-full max-w-sm bg-surface border border-line rounded-2xl p-6 space-y-4 text-center"><h3 class="text-base font-display font-bold text-ink">Delete this gallery?</h3><p class="text-xs text-ink-muted">The gallery and all of its photos will be deleted.</p><div class="flex justify-center gap-3"><button wire:click="cancelDelete" class="px-5 py-2.5 bg-surface-raised text-ink-muted rounded-lg text-xs font-bold">Cancel</button><button wire:click="delete" class="px-5 py-2.5 bg-red-600 text-white rounded-lg text-xs font-bold">Delete Gallery</button></div></div></div>@endif
</div>
