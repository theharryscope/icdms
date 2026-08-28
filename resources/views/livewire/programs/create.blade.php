<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Create Strategic Program</h2>
            <p class="text-sm text-ink-muted mt-1">Define an umbrella development program for foundation operations.</p>
        </div>
        <a href="{{ route('programs.index') }}" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-semibold transition">
            Back to Programs
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Program Code</label>
                <input type="text" wire:model="program_code" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('program_code') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Program Manager</label>
                <select wire:model="manager_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="">Select Manager</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                    @endforeach
                </select>
                @error('manager_id') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Program Title</label>
                <input type="text" wire:model="title" placeholder="e.g. Healthcare Infrastructure Expansion 2026" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('title') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Description & Objectives</label>
                <textarea wire:model="description" rows="4" placeholder="Detailed objective description of this program..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
                @error('description') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Allocated Budget (₦)</label>
                <input type="number" step="0.01" wire:model="budget" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('budget') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Lifecycle Status</label>
                <select wire:model="status" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="planning">Planning</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="completed">Completed</option>
                </select>
                @error('status') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Start Date</label>
                <input type="date" wire:model="start_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('start_date') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Target Completion Date</label>
                <input type="date" wire:model="end_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('end_date') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-line">
            <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
                Save & Initialize Program
            </button>
        </div>
    </form>
</div>