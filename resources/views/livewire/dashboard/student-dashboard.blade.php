<div class="space-y-8">
    
    <!-- Student Header Banner -->
    <div class="bg-gradient-to-r from-ochre-soft via-surface to-surface border border-ochre-dim rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-ochre-soft text-ochre border border-ochre-dim rounded-lg text-xs font-bold uppercase tracking-wider">
                    Student Trainee Workspace
                </span>
                <span class="text-xs text-ink-muted font-mono">
                    ID: {{ $student->student_id_number ?? 'ST-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
                </span>
            </div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Welcome back, {{ $student->name }}!</h2>
            <p class="text-xs text-ink-muted mt-1">
                Enrolled Track: <strong class="text-ink">{{ $student->enrolled_course_title ?? 'Software Engineering & Tech Skills BootCamp' }}</strong>
                &bull; <span class="text-ochre font-semibold">{{ $student->cohort_batch ?? 'Cohort 2026' }}</span>
            </p>
        </div>

        <div class="flex items-center space-x-6">
            <div class="text-right">
                <span class="text-[10px] text-ink-muted font-bold uppercase block">Course Completion</span>
                <div class="w-36 bg-canvas border border-line rounded-full h-3 overflow-hidden mt-1 p-0.5">
                    <div class="bg-ochre h-full rounded-full transition-all duration-500" style="width: {{ $student->course_progress ?? 35 }}%"></div>
                </div>
                <span class="text-xs text-ochre font-mono font-bold block mt-1">{{ $student->course_progress ?? 35 }}% Completed</span>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-ochre-soft border border-ochre-dim text-ochre text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Attendance Score</span>
                <span class="p-2 rounded-xl bg-teal-soft text-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ $student->attendance_percentage ?? 95 }}%</span>
                <span class="text-xs text-teal font-bold">Good Standing</span>
            </div>
        </div>

        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Academic Status</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-xl font-bold text-ink uppercase">{{ $student->student_status ?? 'Active' }}</span>
                <span class="text-xs text-ochre">Enrolled Student</span>
            </div>
        </div>

        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Assigned Community</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-lg font-bold text-ink">{{ $student->localGovernment->name ?? 'Main Hub' }}</span>
                <span class="text-xs text-ink-muted">Local Center</span>
            </div>
        </div>

    </div>

    <!-- Curriculum Modules & Project Submission Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Course Modules -->
        <div class="lg:col-span-2 bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-line flex items-center justify-between">
                <h3 class="text-sm font-bold text-ink">Course Syllabus & Curriculum Modules</h3>
                <span class="text-xs text-ochre font-mono font-bold">Active Track</span>
            </div>

            <div class="divide-y divide-line text-xs">
                @foreach($curriculumModules as $module)
                    <div class="p-4 hover:bg-surface-raised/40 transition flex items-center justify-between">
                        <div>
                            <span class="font-bold text-ink block">{{ $module['title'] }}</span>
                            <span class="text-[10px] text-ink-muted font-mono">{{ $module['duration'] }}</span>
                        </div>

                        @if($module['status'] === 'Completed')
                            <span class="px-2.5 py-1 bg-teal-soft text-teal border border-teal-dim text-[10px] font-bold rounded">Completed</span>
                        @elseif($module['status'] === 'In Progress')
                            <span class="px-2.5 py-1 bg-ochre-soft text-ochre border border-ochre-dim text-[10px] font-bold rounded">Active Unit</span>
                        @else
                            <span class="px-2.5 py-1 bg-surface-raised text-ink-muted text-[10px] font-bold rounded">Upcoming</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Assignment Submission Form -->
        <div class="bg-surface border border-line rounded-2xl p-6 space-y-4 shadow-sm">
            <div class="border-b border-line pb-3">
                <h3 class="text-sm font-bold text-ink">Submit Practical Assignment</h3>
                <p class="text-[11px] text-ink-muted">Paste your GitHub or Live Demo URL for evaluation.</p>
            </div>

            <form wire:submit.prevent="submitAssignment" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Assignment Title</label>
                    <input type="text" wire:model="assignment_title" placeholder="e.g. Responsive Portfolio Website" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                    @error('assignment_title') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">GitHub / Live Link</label>
                    <input type="url" wire:model="submission_link" placeholder="https://github.com/..." class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre font-mono">
                    @error('submission_link') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Submission Notes</label>
                    <textarea wire:model="submission_notes" rows="3" placeholder="Brief explanation or feedback for instructor..." class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-xl text-xs shadow-lg shadow-ochre/10 transition">
                    Submit Project
                </button>
            </form>
        </div>

    </div>

</div>