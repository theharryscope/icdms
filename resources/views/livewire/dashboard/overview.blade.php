<div>
    <div class="space-y-8">
        
        <!-- Role & Jurisdiction Scope Banner -->
        <div class="bg-surface border border-line rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-1 bg-ochre-soft text-ochre border border-ochre-dim rounded-lg text-xs font-bold uppercase">
                    {{ $userRole }}
                </span>
                <span class="text-xs text-ink-muted">
                    Jurisdiction: <strong class="text-ink">{{ $assignedZone }}</strong> / <strong class="text-ink">{{ $assignedLga }}</strong>
                </span>
            </div>
            <span class="text-[11px] text-ink-muted font-mono">Data dynamically scoped to user authorization</span>
        </div>

        <!-- Hero / Metrics Overview Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Organization Overview</h2>
                <p class="text-sm text-ink-muted mt-1">Real-time status tracking for InnoTech Future Foundation.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Active Projects</span>
                    <span class="p-2 rounded-lg bg-teal-soft text-teal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-display font-bold text-ink">{{ $activeProjects }}</span>
                    <span class="text-xs text-ink-muted">of {{ $totalProjects }} Total</span>
                </div>
            </div>

            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Beneficiaries</span>
                    <span class="p-2 rounded-lg bg-ochre-soft text-ochre">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-display font-bold text-ink">{{ number_format($totalBeneficiaries) }}</span>
                    <span class="text-xs text-teal font-semibold">+Live Tracked</span>
                </div>
            </div>

            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Total Deployed Budget</span>
                    <span class="p-2 rounded-lg bg-teal-soft text-teal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-display font-bold text-ink">₦{{ number_format($totalBudget, 0) }}</span>
                    <span class="text-xs text-ink-muted">Allocated</span>
                </div>
            </div>

            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Communities Reached</span>
                    <span class="p-2 rounded-lg bg-ochre-soft text-ochre">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 00-2-2h-1c-1 0-1.5-.5-1.5-1V5a2 2 0 00-2-2h-2.335"/></svg>
                    </span>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <span class="text-3xl font-display font-bold text-ink">{{ $totalCommunities }}</span>
                    <span class="text-xs text-ink-muted">Locations</span>
                </div>
            </div>
        </div>

        <!-- Charts Row 1: Donations Trend + Project Status Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-surface border border-line rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-ink">Verified Donations — Last 6 Months</h3>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 bg-teal-soft text-teal rounded border border-teal-dim font-mono">Paystack-Verified Only</span>
                </div>
                <div class="h-64">
                    <canvas id="donationsTrendChart"></canvas>
                </div>
            </div>

            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-ink mb-4">Project Status Breakdown</h3>
                <div class="h-64">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Row 2: Budget vs Expenditure + Beneficiaries by Category -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-surface border border-line rounded-xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-ink mb-4">Budget vs Expenditure — Top 5 Programs</h3>
                <div class="h-64">
                    <canvas id="programBudgetChart"></canvas>
                </div>
            </div>

            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-ink mb-4">Beneficiaries by Category</h3>
                <div class="h-64">
                    @if($categoryBreakdown->isEmpty())
                        <div class="h-full flex items-center justify-center">
                            <p class="text-xs text-ink-muted text-center">No beneficiaries enrolled yet.</p>
                        </div>
                    @else
                        <canvas id="beneficiaryCategoryChart"></canvas>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Projects & Monitoring KPIs Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-surface border border-line rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <h3 class="text-sm font-bold text-ink">Recent Projects Workspace</h3>
                    <span class="text-xs text-ink-muted">Latest Project Activity</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Code / Project</th>
                                <th class="px-6 py-3">Program</th>
                                <th class="px-6 py-3">Budget</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line text-ink-muted">
                            @forelse($recentProjects as $project)
                                <tr class="hover:bg-surface-raised/50 transition">
                                    <td class="px-6 py-4 font-semibold text-ink">
                                        {{ $project->project_code }}
                                        <span class="block text-[11px] text-ink-muted font-normal">{{ $project->title }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ $project->program->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-mono">₦{{ number_format($project->budget, 0) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-teal-soft text-teal border border-teal-dim">
                                            {{ $project->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-ink-muted">
                                        No project records available yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- M&E KPI Progress -->
            <div class="bg-surface border border-line rounded-xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">M&E KPI Progress</h3>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 bg-teal-soft text-teal rounded border border-teal-dim">v9 Active</span>
                </div>

                <div class="space-y-4">
                    @forelse($kpis as $kpi)
                        @php
                            $percentage = $kpi->target > 0 ? min(100, round(($kpi->current / $kpi->target) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-ink truncate">{{ $kpi->title }}</span>
                                <span class="text-teal font-bold">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-surface-raised rounded-full h-2 overflow-hidden">
                                <div class="bg-teal h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-ink-muted mt-1 font-mono">
                                <span>Current: {{ number_format($kpi->current) }}</span>
                                <span>Target: {{ number_format($kpi->target) }} {{ $kpi->unit }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-ink-muted text-center py-6">No active KPIs configured.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Public Online Donations Feed -->
        <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-line flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-ink">Recent Public Donations</h3>
                    <p class="text-[11px] text-ink-muted">Online card payments and bank transfer receipts submitted via public portal.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-2.5 py-1 bg-teal-soft text-teal border border-teal-dim text-xs font-bold font-mono rounded-lg">
                        Verified Total: ₦{{ number_format($totalPublicDonations ?? 0, 2) }}
                    </span>
                    <a href="{{ route('admin.grants') }}" class="text-xs text-teal font-bold hover:underline">
                        View All Funding &rarr;
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Reference / Donor</th>
                            <th class="px-6 py-3.5">Method</th>
                            <th class="px-6 py-3.5">Amount</th>
                            <th class="px-6 py-3.5">Date</th>
                            <th class="px-6 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line text-ink-muted">
                        @forelse($recentDonations ?? [] as $donation)
                            <tr class="hover:bg-surface-raised/50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-ink block">{{ $donation->donor_name }}</span>
                                    <span class="text-[10px] text-teal font-mono">{{ $donation->reference_code }}</span>
                                    <span class="text-[10px] text-ink-muted block">{{ $donation->donor_email }}</span>
                                </td>
                                <td class="px-6 py-4 font-mono uppercase text-ink">
                                    {{ str_replace('_', ' ', $donation->payment_method) }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-teal">
                                    ₦{{ number_format($donation->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-ink-muted font-mono">
                                    {{ $donation->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->payment_status === 'successful')
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">Successful</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim uppercase">Pending Review</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-ink-muted">
                                    No public online donations recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @php
        $categoryLabels = $categoryBreakdown->pluck('category');
        $categoryData = $categoryBreakdown->pluck('total');
        $chartPalette = ['#DB8A2E', '#2F9E8F', '#E0B25C', '#4CC2B0', '#7A4A18', '#1B5850', '#A79A85'];
    @endphp

    <script>
        (function () {
            const gridColor = '#35302A';
            const tickColor = '#A79A85';
            const fontFamily = "'IBM Plex Sans', sans-serif";

            Chart.defaults.font.family = fontFamily;
            Chart.defaults.color = tickColor;

            const commonScales = {
                x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10 } } },
                y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10 } }, beginAtZero: true },
            };

            // --- Donations Trend (line) ---
            new Chart(document.getElementById('donationsTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($donationsTrendLabels),
                    datasets: [{
                        label: 'Verified Donations (₦)',
                        data: @json($donationsTrendData),
                        borderColor: '#2F9E8F',
                        backgroundColor: 'rgba(47, 158, 143, 0.15)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#2F9E8F',
                        pointRadius: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: commonScales,
                },
            });

            // --- Project Status Breakdown (doughnut) ---
            new Chart(document.getElementById('projectStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($projectStatusLabels),
                    datasets: [{
                        data: @json($projectStatusData),
                        backgroundColor: @json($chartPalette),
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

            // --- Budget vs Expenditure by Program (bar) ---
            new Chart(document.getElementById('programBudgetChart'), {
                type: 'bar',
                data: {
                    labels: @json($programLabels),
                    datasets: [
                        {
                            label: 'Budget (₦)',
                            data: @json($programBudgetData),
                            backgroundColor: 'rgba(219, 138, 46, 0.7)',
                            borderRadius: 4,
                        },
                        {
                            label: 'Expenditure (₦)',
                            data: @json($programExpenditureData),
                            backgroundColor: 'rgba(47, 158, 143, 0.7)',
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: tickColor, font: { size: 10 }, boxWidth: 10, padding: 10 } },
                    },
                    scales: commonScales,
                },
            });

            // --- Beneficiaries by Category (doughnut) ---
            const categoryCanvas = document.getElementById('beneficiaryCategoryChart');
            if (categoryCanvas) {
                new Chart(categoryCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json($categoryLabels),
                        datasets: [{
                            data: @json($categoryData),
                            backgroundColor: @json($chartPalette),
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
            }
        })();
    </script>
</div>