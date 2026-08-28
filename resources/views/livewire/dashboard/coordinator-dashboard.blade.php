<div class="space-y-8">
    
    <!-- Jurisdiction Header Banner -->
    <div class="bg-gradient-to-r from-ochre-soft via-surface to-surface border border-ochre-dim rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-ochre-soft text-ochre border border-ochre-dim rounded-lg text-xs font-bold uppercase tracking-wider">
                    Coordinator Command Center
                </span>
                <span class="text-xs text-ink-muted font-mono">Scoped Session</span>
            </div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">{{ $jurisdictionTitle }}</h2>
            <p class="text-xs text-ink-muted mt-1">
                Assigned Region: <strong class="text-ink">{{ $assignedState }}</strong> State / <strong class="text-ink">{{ $assignedLga }}</strong> LGA
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('beneficiaries.create') }}" class="px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-xl text-xs font-bold shadow-lg shadow-ochre/10 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>Enroll Beneficiary</span>
            </a>
            <a href="{{ route('projects.create') }}" class="px-4 py-2.5 bg-surface-raised hover:bg-line text-ink rounded-xl text-xs font-bold transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>New Project</span>
            </a>
        </div>
    </div>


    

    <!-- Scoped Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Active Projects -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Active Projects</span>
                <span class="p-2 rounded-xl bg-teal-soft text-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ $activeProjects }}</span>
                <span class="text-xs text-ink-muted">of {{ $totalProjects }} Total</span>
            </div>
        </div>

        <!-- Enrolled Beneficiaries -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Enrolled Beneficiaries</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ number_format($totalBeneficiaries) }}</span>
                <span class="text-xs text-ochre font-bold">Region Scoped</span>
            </div>
        </div>

        <!-- Communities Covered -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Communities</span>
                <span class="p-2 rounded-xl bg-ochre-soft text-ochre">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">{{ $totalCommunities }}</span>
                <span class="text-xs text-ink-muted">Locations</span>
            </div>
        </div>

        <!-- Allocated Budget -->
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Deployed Funds</span>
                <span class="p-2 rounded-xl bg-teal-soft text-teal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink font-mono">₦{{ number_format($totalBudget, 0) }}</span>
                <span class="text-xs text-ink-muted">Allocated</span>
            </div>
        </div>
    </div>
    <!-- Charts: Scoped Project Status + Volunteer Report Verification -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold text-ink mb-4">Project Status — {{ $jurisdictionTitle }}</h3>
            <div class="h-56">
                <canvas id="coordProjectStatusChart"></canvas>
            </div>
        </div>
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <h3 class="text-sm font-bold text-ink mb-4">Volunteer Report Verification</h3>
            <div class="h-56">
                <canvas id="coordReportStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- VOLUNTEER FIELD REPORTS FEED -->
<div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm space-y-4">
    <div class="p-5 border-b border-line flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-ink">Volunteer Field & Teaching Reports</h3>
            <p class="text-[11px] text-ink-muted">Reports submitted by active volunteers within your region.</p>
        </div>
        <span class="px-2.5 py-1 bg-teal-soft text-teal border border-teal-dim text-xs font-bold rounded-lg font-mono">
            {{ $volunteerReports->where('status', 'pending')->count() }} Pending Review
        </span>
    </div>

    @if (session()->has('message'))
        <div class="mx-5 p-3 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <div class="divide-y divide-line text-xs">
        @forelse($volunteerReports as $report)
            <div class="p-5 hover:bg-surface-raised/40 transition space-y-3">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-ink text-sm block">{{ $report->activity_title }}</span>
                        <div class="flex items-center space-x-2 text-[11px] text-ink-muted mt-0.5">
                            <span class="text-teal font-semibold">{{ $report->volunteer->name ?? 'Volunteer' }}</span>
                            <span>&bull;</span>
                            <span>Location: <strong>{{ $report->community->name ?? 'N/A' }}</strong></span>
                            <span>&bull;</span>
                            <span class="font-mono text-ink-muted">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($report->status === 'pending')
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim uppercase">Pending Review</span>
                        @elseif($report->status === 'verified')
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">Verified</span>
                        @else
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 uppercase">Rejected</span>
                        @endif
                    </div>
                </div>

                <!-- Session Meta -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 p-3 bg-canvas rounded-xl border border-line/80 text-[11px]">
                    <div>
                        <span class="text-ink-muted uppercase font-bold block text-[9px]">Topic Taught</span>
                        <span class="text-ink font-medium">{{ $report->teaching_topic ?? 'General Community Service' }}</span>
                    </div>
                    <div>
                        <span class="text-ink-muted uppercase font-bold block text-[9px]">Hours Spent</span>
                        <span class="text-ink font-mono">{{ $report->hours_spent }} Hours</span>
                    </div>
                    <div>
                        <span class="text-ink-muted uppercase font-bold block text-[9px]">Beneficiaries / Attendees</span>
                        <span class="text-ink font-mono">{{ $report->attendees_count }} Attendees</span>
                    </div>
                </div>

                <p class="text-ink-muted leading-relaxed text-xs">{{ $report->activity_notes }}</p>

                <!-- Coordinator Verification Actions -->
                @if($report->status === 'pending')
                    <div class="pt-2 flex items-center justify-end space-x-2">
                        <button wire:click="rejectReport({{ $report->id }})" class="px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white rounded-lg text-xs font-bold transition">
                            Reject Report
                        </button>
                        <button wire:click="verifyReport({{ $report->id }})" class="px-4 py-1.5 bg-teal hover:bg-teal/90 text-canvas rounded-lg text-xs font-bold shadow-lg shadow-teal/10 transition">
                            Verify & Approve Report
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-6 text-center text-ink-muted">
                No volunteer reports submitted in your region yet.
            </div>
        @endforelse
    </div>
</div>

    <!-- Field Projects Table -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Jurisdiction Field Projects</h3>
            <a href="{{ route('projects.index') }}" class="text-xs text-teal font-bold hover:text-teal/80">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Project Code / Title</th>
                        <th class="px-6 py-3.5">Target Community</th>
                        <th class="px-6 py-3.5">Budget</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($recentProjects as $project)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-bold text-ink">
                                {{ $project->title }}
                                <span class="block text-[10px] text-teal font-mono font-normal">{{ $project->project_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $project->community->name ?? 'N/A' }}
                                <span class="block text-[10px] text-ink-muted">{{ $project->community->lga ?? 'N/A' }} LGA</span>
                            </td>
                            <td class="px-6 py-4 font-mono">
                                ₦{{ number_format($project->budget, 2) }}
                            </td>
                            <td class="px-6 py-4 uppercase">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim">
                                    {{ str_replace('_', ' ', $project->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink-muted">
                                No projects logged within your jurisdiction yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (function () {
            const gridColor = '#35302A';
            const tickColor = '#A79A85';
            Chart.defaults.font.family = "'IBM Plex Sans', sans-serif";
            Chart.defaults.color = tickColor;
            const chartPalette = ['#DB8A2E', '#2F9E8F', '#E0B25C', '#4CC2B0', '#7A4A18', '#1B5850'];

            new Chart(document.getElementById('coordProjectStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($projectStatusLabels),
                    datasets: [{
                        data: @json($projectStatusData),
                        backgroundColor: chartPalette,
                        borderColor: '#1E1A14',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: tickColor, font: { size: 10 }, boxWidth: 10, padding: 10 } },
                    },
                },
            });

            new Chart(document.getElementById('coordReportStatusChart'), {
                type: 'bar',
                data: {
                    labels: @json($reportStatusLabels),
                    datasets: [{
                        label: 'Reports',
                        data: @json($reportStatusData),
                        backgroundColor: ['#DB8A2E', '#2F9E8F', '#E06B5C'],
                        borderRadius: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10 } }, beginAtZero: true },
                        y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10 } } },
                    },
                },
            });
        })();
    </script>
</div>