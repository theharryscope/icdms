<div class="space-y-8">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-teal-soft via-surface to-surface border border-teal-dim rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-teal-soft text-teal border border-teal-dim rounded-lg text-xs font-bold uppercase tracking-wider">
                    Employer & Corporate Portal
                </span>
                <span class="text-xs text-ink-muted font-mono">{{ $employer->industry_sector ?? 'Corporate Partner' }}</span>
            </div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">{{ $employer->company_name ?? 'Corporate Partner' }}</h2>
            <p class="text-xs text-ink-muted mt-1">
                Contact Person: <strong class="text-ink">{{ $employer->contact_person_name ?? auth()->user()->name }}</strong>
            </p>
        </div>

        <div class="flex items-center space-x-6">
            <div class="text-center">
                <span class="text-3xl font-display font-bold text-ink font-mono block">{{ $totalPlaced }}</span>
                <span class="text-[10px] text-ink-muted uppercase font-bold">Assigned Graduates</span>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Assigned Students Directory -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Assigned InnoTech Graduates</h3>
            <span class="text-xs text-ink-muted">Evaluate performance and submit feedback to the academy.</span>
        </div>

        <div class="divide-y divide-line text-xs">
            @forelse($placements as $placement)
                <div class="p-5 hover:bg-surface-raised/40 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <div class="space-y-1">
                        <span class="font-bold text-ink text-sm block">{{ $placement->student->name ?? 'Student' }}</span>
                        <div class="flex items-center space-x-3 text-ink-muted text-[11px]">
                            <span class="text-teal font-mono font-semibold">{{ $placement->job_title }}</span>
                            <span>&bull;</span>
                            <span>Placed: <strong>{{ date('M d, Y', strtotime($placement->placement_date)) }}</strong></span>
                            <span>&bull;</span>
                            <span class="font-mono text-ink-muted">{{ $placement->student->email }}</span>
                        </div>

                        @if($placement->employer_feedback)
                            <div class="mt-2 p-3 bg-canvas rounded-xl border border-line/80 text-ink-muted">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-ochre font-bold">Rating: {{ $placement->performance_rating }}/5 Stars</span>
                                    <span class="text-[10px] text-ink-muted font-mono">&bull; Submitted {{ $placement->updated_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-ink-muted text-[11px] leading-relaxed">"{{ $placement->employer_feedback }}"</p>
                            </div>
                        @endif
                    </div>

                    <div class="shrink-0">
                        <button wire:click="openFeedbackModal({{ $placement->id }})" class="px-4 py-2 bg-teal hover:bg-teal/90 text-canvas rounded-xl text-xs font-bold shadow transition">
                            {{ $placement->employer_feedback ? 'Update Evaluation' : 'Submit Performance Feedback' }}
                        </button>
                    </div>

                </div>
            @empty
                <div class="p-8 text-center text-ink-muted">
                    No students have been assigned to your corporate workspace yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Evaluation Feedback Modal -->
    @if($showFeedbackModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl p-6 space-y-4">
                
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Submit Student Performance Evaluation</h3>
                    <button wire:click="$set('showFeedbackModal', false)" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitFeedback" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Performance Rating (1 to 5 Stars)</label>
                        <select wire:model="performance_rating" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal">
                            <option value="5">5 Stars - Outstanding Performance</option>
                            <option value="4">4 Stars - Exceeds Expectations</option>
                            <option value="3">3 Stars - Satisfactory</option>
                            <option value="2">2 Stars - Needs Improvement</option>
                            <option value="1">1 Star - Unsatisfactory</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Corporate Feedback & Remarks</label>
                        <textarea wire:model="employer_feedback" rows="4" placeholder="Detail the student's technical performance, punctuality, and work ethics..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-teal"></textarea>
                        @error('employer_feedback') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="$set('showFeedbackModal', false)" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-teal hover:bg-teal/90 text-canvas font-bold rounded-lg shadow">Submit Feedback</button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>