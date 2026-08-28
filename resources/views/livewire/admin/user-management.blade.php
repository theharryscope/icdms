<div>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Staff & Applicant Management</h2>
                <p class="text-sm text-ink-muted mt-1">Review applicant profiles, inspect submitted credentials, and manage accounts.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold flex justify-between items-center">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Form & List Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Create User Form -->
            <form wire:submit.prevent="createUser" class="bg-surface border border-line rounded-2xl p-6 space-y-4">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-teal border-b border-line pb-3">Register Staff User</h3>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" placeholder="e.g. David Okonkwo" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Email Address</label>
                    <input type="email" wire:model="email" placeholder="e.g. officer@innotech.org" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Password</label>
                    <input type="password" wire:model="password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('password') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" placeholder="+234..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">System Role</label>
                    <select wire:model="selected_role" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                        <option value="">Select System Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selected_role') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                    Create Staff Account
                </button>
            </form>

            <!-- User Accounts Directory Table -->
            <div class="lg:col-span-2 bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <h3 class="text-sm font-bold text-ink">Registered Accounts & Applicants</h3>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, email, role..." class="bg-canvas border border-line rounded-lg px-3 py-1.5 text-xs text-ink focus:outline-none focus:border-ochre">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Applicant Details</th>
                                <th class="px-6 py-3.5">Selected Role</th>
                                <th class="px-6 py-3.5">Jurisdiction Scope</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line text-ink-muted">
                            @forelse($users as $user)
                                <tr class="hover:bg-surface-raised/50 transition">
                                    
                                    <!-- Applicant Details -->
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-ink block">{{ $user->name }}</span>
                                        <span class="text-[10px] text-ink-muted font-mono">{{ $user->email }}</span>
                                        @if($user->phone)
                                            <span class="text-[10px] text-ink-muted font-mono block">{{ $user->phone }}</span>
                                        @endif
                                    </td>

                                    <!-- Selected Role Badge -->
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim inline-block">
                                            {{ $user->registration_role ?? $user->getRoleNames()->first() ?? 'No Role' }}
                                        </span>
                                    </td>

                                    <!-- Jurisdiction Scope -->
                                    <td class="px-6 py-4 text-[11px] text-ink-muted">
                                        {{ $user->zone->name ?? 'Global Scope' }} 
                                        @if($user->localGovernment) 
                                            <span class="block text-[10px] text-ink-muted">{{ $user->localGovernment->name }} LGA</span>
                                        @endif
                                    </td>

                                    <!-- Application Status -->
                                    <td class="px-6 py-4">
                                        @if($user->application_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim">
                                                Pending Approval
                                            </span>
                                        @elseif($user->application_status === 'rejected')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim">
                                                Active
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right space-x-2">
                                        
                                        <!-- View Full Application Modal Trigger -->
                                        <button wire:click="inspectApplicant({{ $user->id }})" class="p-1.5 bg-surface-raised hover:bg-teal/20 hover:text-teal text-ink-muted rounded-lg transition" title="View Full Application Info">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit User -->
                                        <button wire:click="openEditModal({{ $user->id }})" class="p-1.5 bg-surface-raised hover:bg-teal/20 hover:text-teal text-ink-muted rounded-lg transition" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete User -->
                                        @if($confirmingDeletionId === $user->id)
                                            <button wire:click="deleteUser({{ $user->id }})" class="px-2 py-1 bg-red-600 text-white text-[10px] font-bold rounded shadow transition">Confirm</button>
                                            <button wire:click="cancelDelete" class="px-2 py-1 bg-surface-raised text-ink-muted text-[10px] rounded transition">Cancel</button>
                                        @else
                                            <button wire:click="confirmDelete({{ $user->id }})" class="p-1.5 bg-surface-raised hover:bg-red-600/20 hover:text-red-400 text-ink-muted rounded-lg transition" title="Delete User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-ink-muted">
                                        No registered accounts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-line">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- FULL APPLICANT INFORMATION INSPECTION MODAL -->
    @if($isApplicantModalOpen && $inspectingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl space-y-6 p-6 max-h-[90vh] overflow-y-auto">
                
                <div class="flex justify-between items-center border-b border-line pb-4">
                    <div class="flex items-center gap-3">
                        @if($inspectingUser->profile_photo_path)
                            <img src="{{ Storage::disk('public')->url($inspectingUser->profile_photo_path) }}" class="w-14 h-14 rounded-full object-cover border border-line" alt="{{ $inspectingUser->name }} profile picture">
                        @else
                            <div class="w-14 h-14 rounded-full bg-surface-raised border border-line flex items-center justify-center text-sm font-bold text-ink-muted">
                                {{ strtoupper(substr($inspectingUser->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                        <h3 class="text-lg font-bold text-ink">{{ $inspectingUser->name }}</h3>
                        <span class="text-xs text-teal font-mono font-bold">Role: {{ $inspectingUser->registration_role ?? 'Staff' }}</span>
                        </div>
                    </div>
                    <button wire:click="closeApplicantModal" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    
                    <!-- Basic & Contact Information -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-canvas rounded-xl border border-line">
                        <div>
                            <span class="text-[10px] text-ink-muted uppercase font-bold block">Email Address</span>
                            <span class="text-ink font-mono">{{ $inspectingUser->email }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink-muted uppercase font-bold block">Phone Number</span>
                            <span class="text-ink font-mono">{{ $inspectingUser->phone ?? 'Not provided' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink-muted uppercase font-bold block">State Jurisdiction</span>
                            <span class="text-ink">{{ $inspectingUser->zoneState->name ?? 'Global' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink-muted uppercase font-bold block">LGA Jurisdiction</span>
                            <span class="text-ink">{{ $inspectingUser->localGovernment->name ?? 'Global' }} LGA</span>
                        </div>
                    </div>

                    <!-- Organization Name (If Partner) -->
                    @if($inspectingUser->organization_name)
                        <div class="p-4 bg-canvas rounded-xl border border-line">
                            <span class="text-[10px] text-teal font-bold uppercase block">Organization / Corporate Name</span>
                            <p class="text-ink font-semibold">{{ $inspectingUser->organization_name }}</p>
                        </div>
                    @endif

                    <!-- Qualification & Expertise -->
                    @if($inspectingUser->qualification_degree || $inspectingUser->skills_and_expertise)
                        <div class="p-4 bg-canvas rounded-xl border border-line space-y-2">
                            <span class="text-[10px] text-teal font-bold uppercase block">Qualifications & Skills</span>
                            <p class="text-ink-muted"><strong>Highest Qualification:</strong> {{ $inspectingUser->qualification_degree ?? 'N/A' }}</p>
                            <p class="text-ink-muted"><strong>Key Expertise:</strong> {{ $inspectingUser->skills_and_expertise ?? 'N/A' }}</p>
                        </div>
                    @endif

                    <!-- Motivation Statement -->
                    <div class="p-4 bg-canvas rounded-xl border border-line space-y-1">
                        <span class="text-[10px] text-ochre font-bold uppercase block">Motivation Statement</span>
                        <p class="text-ink-muted leading-relaxed">{{ $inspectingUser->motivation_statement ?? 'No statement submitted.' }}</p>
                    </div>

                    <!-- Uploaded Document / CV -->
                    <div class="p-4 bg-canvas rounded-xl border border-line flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-teal font-bold uppercase block">Verification Document</span>
                            <span class="text-ink-muted text-[11px]">Uploaded CV or Application Credentials</span>
                        </div>
                        @if($inspectingUser->document_path)
                            <a href="{{ Storage::url($inspectingUser->document_path) }}" target="_blank" class="px-3.5 py-1.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow transition">
                                View / Download File
                            </a>
                        @else
                            <span class="text-ink-muted font-mono text-[11px]">No file attached</span>
                        @endif
                    </div>

                    <div class="p-4 bg-canvas rounded-xl border border-line">
                        <span class="text-[10px] text-ochre font-bold uppercase block mb-2">Registration Consent</span>
                        <div class="flex flex-wrap gap-x-5 gap-y-1 text-[11px] text-ink-muted">
                            <span class="{{ $inspectingUser->privacy_policy_accepted ? 'text-teal' : 'text-red-400' }}">Privacy Policy: {{ $inspectingUser->privacy_policy_accepted ? 'Accepted' : 'Not accepted' }}</span>
                            <span class="{{ $inspectingUser->terms_accepted ? 'text-teal' : 'text-red-400' }}">Terms: {{ $inspectingUser->terms_accepted ? 'Accepted' : 'Not accepted' }}</span>
                        </div>
                    </div>

                </div>

                <!-- Approval Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-line">
                    <button wire:click="rejectApplicant({{ $inspectingUser->id }})" class="px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white rounded-lg text-xs font-bold transition">
                        Reject Application
                    </button>
                    
                    <div class="space-x-2">
                        <button wire:click="closeApplicantModal" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg text-xs font-semibold">
                            Close
                        </button>
                        <button wire:click="approveApplicant({{ $inspectingUser->id }})" class="px-5 py-2 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-bold shadow-lg shadow-ochre/10 transition">
                            Approve & Activate Account
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>