<div class="max-w-6xl mx-auto space-y-8">

    <div class="flex items-center justify-between">
        <div>
            <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Content</span>
            <h1 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Pages</h1>
            <p class="text-sm text-ink-muted mt-1">Custom pages you can link from the site header or footer — About, Privacy Policy, FAQs, and so on.</p>
        </div>
        <button wire:click="openCreateModal" class="px-5 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
            + New Page
        </button>
    </div>

    @if (session('message'))
        <div class="bg-teal-soft border border-teal-dim rounded-lg px-4 py-3 flex items-center space-x-2.5">
            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-teal font-medium">{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-surface border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-mono font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Nav Placement</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($pages as $page)
                        <tr class="hover:bg-canvas/40 transition">
                            <td class="px-6 py-4 font-semibold text-ink">{{ $page->title }}</td>
                            <td class="px-6 py-4 text-ink-muted font-mono">/page/{{ $page->slug }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    @if ($page->show_in_header)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-ochre-soft text-ochre border border-ochre-dim">Header</span>
                                    @endif
                                    @if ($page->show_in_footer)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-teal-soft text-teal border border-teal-dim">Footer</span>
                                    @endif
                                    @if (! $page->show_in_header && ! $page->show_in_footer)
                                        <span class="text-ink-muted/50">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="togglePublish({{ $page->id }})" class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase transition {{ $page->status === 'published' ? 'bg-teal-soft text-teal border border-teal-dim' : 'bg-surface-raised text-ink-muted border border-line' }}">
                                    {{ $page->status }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button wire:click="openEditModal({{ $page->id }})" class="text-teal hover:text-teal/80 font-bold">Edit</button>
                                <button wire:click="confirmDelete({{ $page->id }})" class="text-red-400 hover:text-red-300 font-bold">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-ink-muted">
                                No pages yet — create your first one to add it to the header or footer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pages->hasPages())
            <div class="px-6 py-4 border-t border-line">
                {{ $pages->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-canvas/80" wire:click.self="$set('showFormModal', false)">
            <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-surface border border-line rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-display font-bold text-ink">{{ $editingPageId ? 'Edit Page' : 'New Page' }}</h2>
                    <button wire:click="$set('showFormModal', false)" class="text-ink-muted hover:text-ink text-xl leading-none">&times;</button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Title</label>
                        <input type="text" wire:model.live.debounce.400ms="title" placeholder="e.g. Privacy Policy" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                        @error('title') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">URL Slug</label>
                        <div class="flex items-center bg-canvas border border-line rounded-lg overflow-hidden focus-within:border-ochre">
                            <span class="px-3 text-ink-muted/60 text-xs font-mono shrink-0">/page/</span>
                            <input type="text" wire:model="slug" class="flex-1 bg-transparent px-1 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none font-mono">
                        </div>
                        @error('slug') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Content</label>
                        <textarea wire:model="content" rows="8" placeholder="Page content. Basic HTML tags (p, h2, ul, a, strong, etc.) are allowed." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre font-mono"></textarea>
                        @error('content') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Meta Description (optional)</label>
                        <input type="text" wire:model="meta_description" placeholder="One sentence for search engines and link previews" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                        @error('meta_description') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="show_in_header" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                            <span class="text-xs text-ink-muted">Show in Header</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="show_in_footer" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                            <span class="text-xs text-ink-muted">Show in Footer</span>
                        </label>
                        <div>
                            <label class="block text-[10px] font-bold text-ink-muted uppercase mb-1">Nav Order</label>
                            <input type="number" wire:model="nav_order" min="0" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Status</label>
                        <select wire:model="status" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <p class="text-[10px] text-ink-muted/70 mt-1">Only published pages appear in the nav or are publicly reachable — drafts stay hidden even if header/footer is checked.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2 border-t border-line">
                        <button type="button" wire:click="$set('showFormModal', false)" class="px-5 py-2.5 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-bold transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                            {{ $editingPageId ? 'Save Changes' : 'Create Page' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation -->
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-canvas/80" wire:click.self="cancelDelete">
            <div class="w-full max-w-sm bg-surface border border-line rounded-2xl p-6 space-y-4 text-center">
                <h3 class="text-base font-display font-bold text-ink">Delete this page?</h3>
                <p class="text-xs text-ink-muted">This can't be undone. Any header or footer links to it will stop working immediately.</p>
                <div class="flex items-center justify-center space-x-3 pt-2">
                    <button wire:click="cancelDelete" class="px-5 py-2.5 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-bold transition">Cancel</button>
                    <button wire:click="delete" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-bold transition">Delete Page</button>
                </div>
            </div>
        </div>
    @endif
</div>
