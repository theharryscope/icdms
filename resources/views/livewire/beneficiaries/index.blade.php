<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Beneficiaries Directory</h2>
            <p class="text-sm text-ink-muted mt-1">Manage, update, and remove enrolled community beneficiaries.</p>
        </div>
        <a href="{{ route('beneficiaries.create') }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition flex items-center space-x-2 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>New Beneficiary</span>
        </a>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold flex justify-between items-center">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Enrolled Records</h3>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search ID, name, or phone..." class="bg-canvas border border-line rounded-lg px-3.5 py-1.5 text-xs text-ink focus:outline-none focus:border-ochre">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">ID / Code</th>
                        <th class="px-6 py-3.5">Full Name</th>
                        <th class="px-6 py-3.5">Demographics</th>
                        <th class="px-6 py-3.5">Location</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($beneficiaries as $beneficiary)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-teal">
                                {{ $beneficiary->beneficiary_code }}
                            </td>
                            <td class="px-6 py-4 font-bold text-ink">
                                {{ $beneficiary->full_name }}
                                <span class="block text-[10px] text-ink-muted font-mono font-normal">{{ $beneficiary->phone ?? 'No phone' }}</span>
                            </td>
                            <td class="px-6 py-4 capitalize">
                                {{ $beneficiary->gender }}, {{ $beneficiary->age }} yrs
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-ink block">{{ $beneficiary->community->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-ink-muted">{{ $beneficiary->community->lga ?? 'N/A' }} LGA</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-surface-raised text-ink-muted border border-line">
                                    {{ $beneficiary->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <!-- Edit Action Button -->
                                <button wire:click="openEditModal({{ $beneficiary->id }})" class="p-1.5 bg-surface-raised hover:bg-teal/20 hover:text-teal text-ink-muted rounded-lg transition" title="Edit Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <!-- Delete Action Button -->
                                @if($confirmingDeletionId === $beneficiary->id)
                                    <button wire:click="deleteBeneficiary({{ $beneficiary->id }})" class="px-2 py-1 bg-red-600 hover:bg-red-500 text-white text-[10px] font-bold rounded shadow transition">
                                        Confirm
                                    </button>
                                    <button wire:click="cancelDelete" class="px-2 py-1 bg-surface-raised hover:bg-line text-ink-muted text-[10px] rounded transition">
                                        Cancel
                                    </button>
                                @else
                                    <button wire:click="confirmDelete({{ $beneficiary->id }})" class="p-1.5 bg-surface-raised hover:bg-red-600/20 hover:text-red-400 text-ink-muted rounded-lg transition" title="Delete Profile">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ink-muted">
                                No beneficiary records match your query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-line">
            {{ $beneficiaries->links() }}
        </div>
    </div>

    <!-- Edit Beneficiary Modal -->
    @if($isEditModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl space-y-6 p-6">
                <div class="flex justify-between items-center border-b border-line pb-4">
                    <h3 class="text-lg font-bold text-ink">Edit Beneficiary Profile ({{ $beneficiary_code }})</h3>
                    <button wire:click="closeModal" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateBeneficiary" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Full Name</label>
                            <input type="text" wire:model="full_name" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                            @error('full_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Phone Number</label>
                            <input type="text" wire:model="phone" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Gender</label>
                            <select wire:model="gender" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre capitalize">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Age</label>
                            <input type="number" wire:model="age" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Category</label>
                            <select wire:model="category" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                                <option value="Youth">Youth</option>
                                <option value="Students">Students</option>
                                <option value="Women">Women</option>
                                <option value="Entrepreneurs">Entrepreneurs</option>
                                <option value="Community Members">Community Members</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">State</label>
                            <select wire:model.live="selected_state_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LGA</label>
                            <select wire:model.live="selected_lga_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                                <option value="">Select LGA</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}">{{ $lga->name }} LGA</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Community Name</label>
                            <input type="text" wire:model="community_name" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-line">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink-muted rounded-lg text-xs transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-ochre hover:bg-ochre/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>