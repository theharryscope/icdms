<div class="space-y-8">
    
    <!-- Volunteer Command Banner -->
    <div class="bg-gradient-to-r from-teal-soft via-surface to-surface border border-teal-dim rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-teal-soft text-teal border border-teal-dim rounded-lg text-xs font-bold uppercase tracking-wider">
                    Volunteer Tech & Community Portal
                </span>
                <span class="text-xs text-ink-muted font-mono">Field Station</span>
            </div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Welcome back, {{ $volunteer->name }}!</h2>
            <p class="text-xs text-ink-muted mt-1">
                Assigned Teaching Command: <strong class="text-ink">{{ $assignedLga }} LGA</strong>, {{ $assignedState }} State
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button wire:click="openLogModal" class="px-4 py-2.5 bg-teal hover:bg-teal/90 text-canvas rounded-xl text-xs font-bold shadow-lg shadow-teal/10 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Log Teaching / Service Session</span>
            </button>
            <a href="{{ route('beneficiaries.create') }}" class="px-4 py-2.5 bg-surface-raised hover:bg-line text-ink rounded-xl text-xs font-bold transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>Register Student</span>
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Students Enrolled -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Students & Trainees</span>
                <span class="p-2 rounded-xl bg-teal-soft text-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ number_format($totalStudentsTaught) }}</span>
                <span class="text-xs text-teal font-bold">Community Reach</span>
            </div>
        </div>

        <!-- Tech Tutorials Conducted -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Tutorial Sessions</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ $totalSessionsCount }}</span>
                <span class="text-xs text-ochre font-bold">Classes Delivered</span>
            </div>
        </div>

        <!-- Active Tech Projects -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Local Deployments</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ $activeTechProjects->count() }}</span>
                <span class="text-xs text-ink-muted">Field Initiatives</span>
            </div>
        </div>

        <!-- Volunteer Hours -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Service Hours</span>
                <span class="p-2 rounded-xl bg-teal-soft text-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ number_format($totalLoggedHours, 1) }} hrs</span>
                <span class="text-xs text-teal font-bold">Logged</span>
            </div>
        </div>
    </div>

    <!-- Active Field Initiatives & Tutorial Schedule Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Local Tech Teaching Initiatives -->
        <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-line flex items-center justify-between">
                <h3 class="text-sm font-bold text-ink">Assigned Community Projects</h3>
                <span class="text-xs text-teal font-mono">{{ $assignedLga }} LGA</span>
            </div>
            <div class="divide-y divide-line text-xs">
                @forelse($activeTechProjects as $project)
                    <div class="p-4 hover:bg-surface-raised/50 transition flex items-center justify-between">
                        <div>
                            <span class="font-bold text-ink block">{{ $project->title }}</span>
                            <span class="text-[10px] text-teal font-mono">{{ $project->project_code }}</span>
                            <span class="text-[10px] text-ink-muted block mt-1">Target Community: {{ $project->community->name ?? 'General' }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">
                            {{ str_replace('_', ' ', $project->status) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-ink-muted">
                        No active field projects currently assigned to this local command.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Weekly Tutorial Schedule & Modules -->
        <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-line flex items-center justify-between">
                <h3 class="text-sm font-bold text-ink">Teaching Schedule & Modules</h3>
                <span class="text-[10px] bg-ochre-soft text-ochre px-2 py-0.5 rounded font-bold">Active Bootcamps</span>
            </div>
            <div class="divide-y divide-line text-xs">
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-ink block">Web Development Essentials (HTML/CSS)</span>
                        <span class="text-[10px] text-ink-muted">Community Tech Hub &bull; Tue & Thu (10:00 AM)</span>
                    </div>
                    <span class="px-2 py-1 rounded bg-surface-raised text-ink-muted text-[10px] font-bold">24 Students</span>
                </div>

                <div class="p-4 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-ink block">Digital Literacy & Computer Basics</span>
                        <span class="text-[10px] text-ink-muted">Community Secondary School &bull; Mon & Wed (2:00 PM)</span>
                    </div>
                    <span class="px-2 py-1 rounded bg-surface-raised text-ink-muted text-[10px] font-bold">38 Students</span>
                </div>

                <div class="p-4 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-ink block">Introduction to Python & Data Analysis</span>
                        <span class="text-[10px] text-ink-muted">Innovation Center &bull; Saturday (11:00 AM)</span>
                    </div>
                    <span class="px-2 py-1 rounded bg-surface-raised text-ink-muted text-[10px] font-bold">15 Students</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Activity Logging Modal -->
    @if($showLogModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl p-6 space-y-4">
                
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Log Teaching / Community Service Session</h3>
                    <button wire:click="closeLogModal" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitActivityLog" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Activity Title</label>
                        <input type="text" wire:model="activity_title" placeholder="e.g. Conducted Web Dev Class Module 2" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Community</label>
                            <select wire:model="community_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal">
                                <option value="">Select Location</option>
                                @foreach($assignedCommunities as $community)
                                    <option value="{{ $community->id }}">{{ $community->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Hours Spent</label>
                            <input type="number" step="0.5" wire:model="hours_spent" placeholder="e.g. 2.5" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Teaching Topic (If Tech)</label>
                            <input type="text" wire:model="teaching_topic" placeholder="e.g. CSS Flexbox Layouts" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal">
                        </div>

                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Attendees / Beneficiaries</label>
                            <input type="number" wire:model="attendees_count" placeholder="e.g. 25" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Service Summary & Notes</label>
                        <textarea wire:model="activity_notes" rows="3" placeholder="Describe outcomes, challenges, or student feedback..." class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-teal"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="closeLogModal" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-teal hover:bg-teal/90 text-canvas font-bold rounded-lg shadow">Submit Field Log</button>
                    </div>
                </form>

            </div>
        </div>
    @endif

</div>