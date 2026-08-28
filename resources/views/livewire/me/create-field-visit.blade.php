<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Log Field Monitoring Visit</h2>
            <p class="text-sm text-ink-muted mt-1">Record on-site project inspections, challenges, and geolocation evidence.</p>
        </div>
        <a href="{{ route('me.dashboard') }}" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-semibold transition">
            Back to M&E
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Target Project Workspace</label>
                <select wire:model="project_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_code }} — {{ $project->title }}</option>
                    @endforeach
                </select>
                @error('project_id') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Inspection Date</label>
                <input type="date" wire:model="visit_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('visit_date') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Visit Status</label>
                <select wire:model="status" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="scheduled">Scheduled</option>
                    <option value="conducted">Conducted</option>
                    <option value="reviewed">Reviewed</option>
                    <option value="approved">Approved</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">GPS Latitude</label>
                <input type="number" step="any" wire:model="latitude" placeholder="e.g. 6.2105" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">GPS Longitude</label>
                <input type="number" step="any" wire:model="longitude" placeholder="e.g. 7.0722" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Field Observations & Findings</label>
                <textarea wire:model="observations" rows="3" placeholder="Detail what activities were observed on-site..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
                @error('observations') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Challenges / Bottlenecks Identified</label>
                <textarea wire:model="challenges" rows="2" placeholder="List key operational risks or field delays..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Officer Recommendations</label>
                <textarea wire:model="recommendations" rows="2" placeholder="Corrective steps for project management team..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-line">
            <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
                Submit Field Report
            </button>
        </div>
    </form>
</div>