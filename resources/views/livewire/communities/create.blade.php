<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Register Target Community</h2>
            <p class="text-sm text-ink-muted mt-1">Add geographic location, demographics, and baseline indicators.</p>
        </div>
        <a href="{{ route('communities.index') }}" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-semibold transition">
            Back to Communities
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Community Name</label>
                <input type="text" wire:model="name" placeholder="e.g. Umueze Village" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('name') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">State</label>
                <input type="text" wire:model="state" placeholder="e.g. Anambra State" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('state') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Local Government Area (LGA)</label>
                <input type="text" wire:model="lga" placeholder="e.g. Awka South" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('lga') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Latitude (GPS)</label>
                <input type="number" step="any" wire:model="latitude" placeholder="e.g. 6.2105" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Longitude (GPS)</label>
                <input type="number" step="any" wire:model="longitude" placeholder="e.g. 7.0722" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Estimated Population</label>
                <input type="number" wire:model="estimated_population" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>
        </div>

        <!-- Needs Assessment Checkboxes -->
        <div class="pt-4 border-t border-line">
            <h3 class="text-xs font-bold text-ink-muted uppercase tracking-wider mb-3">Community Baseline Needs Assessment</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs text-ink-muted">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="needs_education" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                    <span>Education Infrastructure</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="needs_tech" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                    <span>Technology / Digital Access</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="needs_infrastructure" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                    <span>Basic Infrastructure</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="needs_healthcare" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                    <span>Healthcare Facilities</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-line">
            <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
                Register Community
            </button>
        </div>
    </form>
</div>