<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Register Beneficiary Profile</h2>
            <p class="text-sm text-ink-muted mt-1">Enroll community members into foundation development tracking.</p>
        </div>
        <a href="{{ route('beneficiaries.index') }}" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs font-semibold transition">
            Back to Beneficiaries
        </a>
    </div>

    <form wire:submit.prevent="save" class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Beneficiary Code -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Beneficiary ID</label>
                <input type="text" wire:model="beneficiary_code" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('beneficiary_code') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Full Name</label>
                <input type="text" wire:model="full_name" placeholder="e.g. Chinedu Okafor" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('full_name') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Gender</label>
                <select wire:model="gender" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre capitalize">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                @error('gender') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Age -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Age (Years)</label>
                <input type="number" wire:model="age" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('age') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Phone Number</label>
                <input type="text" wire:model="phone" placeholder="e.g. +234..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <!-- Target Category -->
            <div>
                <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Segment Category</label>
                <select wire:model="category" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-xs text-ink focus:outline-none focus:border-ochre">
                    <option value="Youth">Youth</option>
                    <option value="Students">Students</option>
                    <option value="Women">Women</option>
                    <option value="Entrepreneurs">Entrepreneurs</option>
                    <option value="Community Members">Community Members</option>
                </select>
            </div>

            <!-- Dynamic Location Controls Section -->
            <div class="md:col-span-2 p-4 bg-canvas border border-line rounded-xl space-y-4">
                <h3 class="text-xs font-bold text-teal uppercase tracking-wider">Location & Community Scoping</h3>
                
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
                        @error('selected_state_id') <span class="text-red-400 text-[10px] mt-1 block">Please select a state</span> @enderror
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
                        @error('selected_lga_id') <span class="text-red-400 text-[10px] mt-1 block">Please select an LGA</span> @enderror
                    </div>

                    <!-- Community Input Field -->
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">3. Community Name</label>
                        <input type="text" wire:model="community_name" placeholder="e.g. Umueze Village" class="w-full bg-surface border border-line rounded-lg px-3 py-2 text-xs text-ink placeholder-ink-muted/40 focus:outline-none focus:border-ochre">
                        @error('community_name') <span class="text-red-400 text-[10px] mt-1 block">Please enter community name</span> @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-line">
            <button type="submit" class="px-6 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
                Save Beneficiary Profile
            </button>
        </div>
    </form>
</div>