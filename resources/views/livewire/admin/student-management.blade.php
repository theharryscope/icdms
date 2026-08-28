<div class="space-y-8">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Student Registration & Approvals</h2>
            <p class="text-sm text-ink-muted mt-1">Review applicant registrations, verify details, and issue official Student IDs.</p>
        </div>

        <!-- Filter Pills -->
        <div class="flex items-center space-x-2 bg-surface border border-line p-1.5 rounded-xl text-xs">
            <button wire:click="$set('statusFilter', 'pending')" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'pending' ? 'bg-teal text-canvas shadow' : 'text-ink-muted hover:text-ink' }}">
                Pending Applications ({{ $pendingCount }})
            </button>
            <button wire:click="$set('statusFilter', 'approved')" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'approved' ? 'bg-teal text-canvas shadow' : 'text-ink-muted hover:text-ink' }}">
                Enrolled Students ({{ $approvedCount }})
            </button>
            <button wire:click="$set('statusFilter', 'all')" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'all' ? 'bg-teal text-canvas shadow' : 'text-ink-muted hover:text-ink' }}">
                All Records
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Students Directory Table -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Registered Student Applicants</h3>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, email or Student ID..." class="bg-canvas border border-line rounded-lg px-3 py-1.5 text-xs text-ink focus:outline-none focus:border-teal">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Student Info</th>
                        <th class="px-6 py-3.5">Track / Cohort</th>
                        <th class="px-6 py-3.5">Location / LGA</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($students as $student)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block text-sm">{{ $student->name }}</span>
                                <span class="text-[10px] text-teal font-mono">{{ $student->email }}</span>
                                @if($student->student_id_number)
                                    <span class="text-[10px] text-ink-muted font-mono block mt-0.5">ID: {{ $student->student_id_number }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-ink font-medium block">{{ $student->enrolled_course_title ?? 'General Tech Track' }}</span>
                                <span class="text-[10px] text-ink-muted font-mono">{{ $student->cohort_batch ?? 'Cohort 2026' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-ink-muted font-medium">{{ $student->localGovernment->name ?? 'Unassigned LGA' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($student->application_status === 'pending')
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim uppercase">Pending Verification</span>
                                @elseif($student->application_status === 'approved')
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">Approved & Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 uppercase">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($student->application_status === 'pending')
                                    <button wire:click="openApprovalModal({{ $student->id }})" class="px-3 py-1.5 bg-teal hover:bg-teal/90 text-canvas rounded-lg text-xs font-bold transition shadow">
                                        Approve Registration
                                    </button>
                                    <button wire:click="rejectStudent({{ $student->id }})" class="px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white rounded-lg text-xs font-bold transition">
                                        Reject
                                    </button>
                                @else
                                    <button wire:click="openApprovalModal({{ $student->id }})" class="px-3 py-1.5 bg-surface-raised text-ink-muted hover:bg-surface-raised rounded-lg text-xs font-bold transition">
                                        Edit Details
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-muted">
                                No student application records found matching the criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-line">
            {{ $students->links() }}
        </div>
    </div>

    <!-- Student Registration Approval Modal -->
    @if($showApprovalModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-md overflow-hidden shadow-2xl p-6 space-y-4">
                
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Approve Student Enrollment</h3>
                    <button wire:click="$set('showApprovalModal', false)" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-3 bg-canvas rounded-xl border border-line/80 text-xs">
                    <span class="text-ink-muted font-bold uppercase block text-[10px]">Applicant Details</span>
                    <span class="text-ink font-bold block mt-0.5">{{ $student_name }}</span>
                    <span class="text-teal font-mono">{{ $student_email }}</span>
                </div>

                <form wire:submit.prevent="approveStudent" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Enrolled Course / Track Title</label>
                        <input type="text" wire:model="enrolled_course_title" placeholder="e.g. Full-Stack Web Development" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-ink focus:outline-none focus:border-teal">
                        @error('enrolled_course_title') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Cohort Batch</label>
                        <input type="text" wire:model="cohort_batch" placeholder="e.g. Cohort 2026" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-ink focus:outline-none focus:border-teal">
                        @error('cohort_batch') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Assigned Local Government (LGA)</label>
                        <select wire:model="local_government_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal">
                            <option value="">Select LGA Location</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="$set('showApprovalModal', false)" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-teal hover:bg-teal/90 text-canvas font-bold rounded-lg shadow">Confirm Approval & Generate ID</button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>