<div class="space-y-8">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Corporate Employers & Placement Center</h2>
            <p class="text-sm text-ink-muted mt-1">Manage corporate partner accounts, assign certified graduates to companies, and review performance feedback.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold flex justify-between items-center">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Form & List Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Employer Form -->
        <form wire:submit.prevent="createEmployer" class="bg-surface border border-line rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-ink uppercase tracking-wider text-teal border-b border-line pb-3">Register Corporate Employer</h3>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Company Name</label>
                <input type="text" wire:model="company_name" placeholder="e.g. InnoTech Solutions Ltd" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('company_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Industry Sector</label>
                <input type="text" wire:model="industry_sector" placeholder="e.g. Information Technology, Finance" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Contact Person Name</label>
                <input type="text" wire:model="contact_person_name" placeholder="e.g. Sarah Jenkins" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('contact_person_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Official Email Address</label>
                <input type="email" wire:model="contact_email" placeholder="e.g. hr@company.com" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                @error('contact_email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Phone Number</label>
                <input type="text" wire:model="contact_phone" placeholder="+234..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre font-mono">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Portal Login Password</label>
                <input type="password" wire:model="password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre">
                @error('password') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Office Address</label>
                <textarea wire:model="office_address" rows="2" placeholder="Corporate headquarters address..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink focus:outline-none focus:border-ochre"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-semibold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                Create Employer Workspace
            </button>
        </form>

        <!-- Employer Companies Directory Table -->
        <div class="lg:col-span-2 bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-line flex items-center justify-between">
                <h3 class="text-sm font-bold text-ink">Corporate Partners & Companies</h3>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search company or contact..." class="bg-canvas border border-line rounded-lg px-3 py-1.5 text-xs text-ink focus:outline-none focus:border-ochre">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Company / Sector</th>
                            <th class="px-6 py-3.5">Contact Person</th>
                            <th class="px-6 py-3.5">Assigned Graduates</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink-muted">
                        @forelse($employers as $employer)
                            <tr class="hover:bg-surface-raised/50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-ink block">{{ $employer->company_name }}</span>
                                    <span class="text-[10px] text-teal font-mono">{{ $employer->industry_sector ?? 'Corporate Partner' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-ink font-medium block">{{ $employer->contact_person_name }}</span>
                                    <span class="text-[10px] text-ink-muted font-mono">{{ $employer->contact_email }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim inline-block font-mono">
                                        {{ $employer->placements->count() }} Graduates Placed
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="openPlacementModal({{ $employer->id }})" class="px-3 py-1.5 bg-teal/20 text-teal hover:bg-teal hover:text-canvas rounded-lg text-xs font-bold transition">
                                        + Assign Student
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-ink-muted">
                                    No corporate employers created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-line">
                {{ $employers->links() }}
            </div>
        </div>

    </div>

    <!-- Assign Student to Employer Modal -->
    @if($showPlacementModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-md overflow-hidden shadow-2xl p-6 space-y-4">
                
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Assign Graduate to Corporate Employer</h3>
                    <button wire:click="$set('showPlacementModal', false)" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="assignStudentToEmployer" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Select Certified Graduate / Student</label>
                        <select wire:model="selected_student_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                            <option value="">Select Student</option>
                            @foreach($certifiedStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        @error('selected_student_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Assigned Job Title / Role</label>
                        <input type="text" wire:model="job_title" placeholder="e.g. Junior Web Developer, UI Designer" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-ink focus:outline-none focus:border-ochre">
                        @error('job_title') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Placement Date</label>
                        <input type="date" wire:model="placement_date" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-ink focus:outline-none focus:border-ochre">
                        @error('placement_date') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="$set('showPlacementModal', false)" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg shadow">Confirm Placement</button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>