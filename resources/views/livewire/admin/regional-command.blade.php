<div>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Geographic Command Setup</h2>
                <p class="text-sm text-ink-muted mt-1">Configure Zonal, State, and LGA administrative structures and assign coordinators.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
                {{ session('message') }}
            </div>
        @endif

        <!-- Creation Forms Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Form 1: Create Zone -->
            <form wire:submit.prevent="createZone" class="bg-surface border border-line rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-ochre">1. Create Zone</h3>
                
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Zone Name</label>
                    <input type="text" wire:model="zone_name" placeholder="e.g. South-East Zone" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Zone Code</label>
                    <input type="text" wire:model="zone_code" placeholder="e.g. SEZ" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Zonal Coordinator</label>
                    <select wire:model="zonal_coordinator_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select Coordinator</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full py-2 bg-ochre hover:bg-ochre/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                    Create Zone
                </button>
            </form>

            <!-- Form 2: Add State to Zone -->
            <form wire:submit.prevent="createState" class="bg-surface border border-line rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-teal">2. Add State to Zone</h3>
                
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Select Target Zone</label>
                    <select wire:model="selected_zone_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select Zone</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}">{{ $z->name }} ({{ $z->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">State Name</label>
                    <input type="text" wire:model="state_name" placeholder="e.g. Anambra State" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">State Coordinator</label>
                    <select wire:model="state_coordinator_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select State Coordinator</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full py-2 bg-teal hover:bg-teal/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-teal/10 transition">
                    Add State to Zone
                </button>
            </form>

            <!-- Form 3: Add LGA & Project Leaders -->
            <form wire:submit.prevent="createLga" class="bg-surface border border-line rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-ochre">3. Register LGA & Leaders</h3>
                
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Select Parent State</label>
                    <select wire:model="selected_state_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select State</option>
                        @foreach($allStates as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LGA Name</label>
                    <input type="text" wire:model="lga_name" placeholder="e.g. Awka South" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LGA Coordinator</label>
                    <select wire:model="lga_coordinator_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select LGA Coordinator</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LGA Project Leader</label>
                    <select wire:model="project_leader_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select Project Leader</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full py-2 bg-ochre hover:bg-ochre/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                    Register LGA Command
                </button>
            </form>

        </div>

        <!-- Bulk LGA Import -->
        <form wire:submit.prevent="importLgas" class="bg-surface border border-line rounded-2xl p-5 space-y-4">
            <div>
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-teal">Bulk Import Local Government Areas</h3>
                <p class="text-[11px] text-ink-muted mt-1">Select a state, then upload a CSV or text file with one LGA name per line. A CSV with a <span class="font-mono">name</span> column is also supported.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Target State</label>
                    <select wire:model="selected_state_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-teal">
                        <option value="">Select State</option>
                        @foreach($allStates as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('selected_state_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">CSV / Text File</label>
                    <input type="file" wire:model="lga_import_file" accept=".csv,.txt,text/csv,text/plain" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink-muted focus:outline-none">
                    @error('lga_import_file') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto px-5 py-2 bg-teal hover:bg-teal/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-teal/10 transition" wire:loading.attr="disabled" wire:target="importLgas,lga_import_file">
                Import LGAs to Selected State
            </button>
        </form>

        <!-- Hierarchical Tree Overview -->
        <div class="bg-surface border border-line rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-ink uppercase tracking-wider">Active Regional Command Hierarchy</h3>
            
            <div class="space-y-6">
                @forelse($zones as $zone)
                    <div class="p-4 rounded-xl bg-canvas border border-line space-y-3">
                        <div class="flex items-center justify-between border-b border-line pb-2">
                            <div>
                                <span class="text-[10px] text-teal font-mono font-bold">{{ $zone->code }} ZONE</span>
                                <h4 class="text-sm font-bold text-ink">{{ $zone->name }}</h4>
                            </div>
                            <span class="text-xs text-ink-muted">
                                Zonal Coordinator: <strong class="text-teal">{{ $zone->coordinator->name ?? 'Unassigned' }}</strong>
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-4 border-l-2 border-teal">
                            @foreach($zone->states as $state)
                                <div class="p-3 rounded-lg bg-surface border border-line space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-teal">{{ $state->name }}</span>
                                        <span class="text-[11px] text-ink-muted">
                                            State Coord: <strong class="text-teal">{{ $state->coordinator->name ?? 'Unassigned' }}</strong>
                                        </span>
                                    </div>

                                    <div class="space-y-1 text-[11px]">
                                        @foreach($state->localGovernments as $lga)
                                            <div class="flex justify-between items-center py-1 border-t border-line/50">
                                                <span class="text-ink-muted font-medium">{{ $lga->name }} LGA</span>
                                                <div class="space-x-2 text-[10px]">
                                                    <span class="text-ochre">Coord: {{ $lga->lgaCoordinator->name ?? 'None' }}</span>
                                                    <span class="text-ink-muted">|</span>
                                                    <span class="text-teal">Leader: {{ $lga->projectLeader->name ?? 'None' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-ink-muted text-center py-8">No regional structures created yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>