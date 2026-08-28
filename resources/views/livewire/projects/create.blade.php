<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Create Project Workspace</h2>
            <p class="text-sm text-ink-muted mt-1">Bind community initiatives to core development programs.</p>
        </div>
        <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-semibold transition">
            Back to Projects
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Project Code -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Project Code</label>
                <input type="text" wire:model="project_code" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('project_code') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Parent Program -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Parent Program</label>
                <select wire:model="program_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->title }}</option>
                    @endforeach
                </select>
                @error('program_id') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Project Title -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Project Title</label>
                <input type="text" wire:model="title" placeholder="e.g. Rural Solar Power Grid Installation" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('title') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Location & Region Scoping Section -->
            <div class="md:col-span-2 p-4 bg-canvas border border-line rounded-xl space-y-4">
                <h3 class="text-xs font-bold text-teal uppercase tracking-wider">Geographic Jurisdiction Scoping</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- State Selection -->
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">1. State</label>
                        <select wire:model.live="selected_state_id" class="w-full bg-surface border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_state_id') <span class="text-red-400 text-[10px] mt-1 block">State required</span> @enderror
                    </div>

                    <!-- LGA Selection -->
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">2. Local Government Area</label>
                        <select wire:model.live="selected_lga_id" class="w-full bg-surface border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre" {{ !$selected_state_id ? 'disabled' : '' }}>
                            <option value="">Select LGA</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }} LGA</option>
                            @endforeach
                        </select>
                        @error('selected_lga_id') <span class="text-red-400 text-[10px] mt-1 block">LGA required</span> @enderror
                    </div>

                    <!-- Target Community Input Field -->
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">3. Target Community Name</label>
                        <input type="text" wire:model="community_name" placeholder="e.g. Umueze Village" class="w-full bg-surface border border-line rounded-lg px-3 py-2 text-xs text-ink placeholder-ink-muted/40 focus:outline-none focus:border-ochre">
                        @error('community_name') <span class="text-red-400 text-[10px] mt-1 block">Community name required</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Project Lead / Manager -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Project Lead / Manager</label>
                <select wire:model="project_manager_id" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="">Select Project Manager</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Project Budget -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Project Budget (₦)</label>
                <input type="number" step="0.01" wire:model="budget" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('budget') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Objectives -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Specific Objectives & Deliverables</label>
                <textarea wire:model="objectives" rows="3" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre" placeholder="Outline specific impact goals..."></textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Project Status</label>
                <select wire:model="status" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="draft">Draft</option>
                    <option value="approved">Approved</option>
                    <option value="in_progress">In Progress</option>
                    <option value="on_hold">On Hold</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                @error('status') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Start Date</label>
                <input type="date" wire:model="start_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('start_date') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Expected Completion</label>
                <input type="date" wire:model="end_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('end_date') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-line">
            <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
                Create Project Workspace
            </button>
        </div>
    </form>
</div>