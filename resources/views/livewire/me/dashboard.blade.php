<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <h2 class="text-2xl font-display font-bold text-ink tracking-tight">M&E Central Command</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-teal-soft text-teal border border-teal-dim">ICDMS v9 Engine</span>
            </div>
            <p class="text-sm text-ink-muted mt-1">Real-time KPI performance analytics, impact tracking, and field oversight.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('me.field-visits.create') }}" class="px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Log Monitoring Visit</span>
            </a>
        </div>
    </div>

    <!-- M&E Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Total Indicators Monitored</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ink">{{ $totalKpis }}</span>
                <span class="text-xs text-ink-muted">KPI Parameters</span>
            </div>
        </div>

        <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">KPI Target Achieved</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-teal">{{ $achievedKpis }}</span>
                <span class="text-xs text-teal/80 font-medium">100%+ Met</span>
            </div>
        </div>

        <div class="bg-surface border border-line rounded-xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">KPIs At Risk</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-display font-bold text-ochre">{{ $atRiskKpis }}</span>
                <span class="text-xs text-ochre/80 font-medium">&lt; 50% Target</span>
            </div>
        </div>
    </div>

    <!-- Live KPI Progress Grid -->
    <div class="bg-surface border border-line rounded-xl p-6 space-y-6">
        <h3 class="text-sm font-bold text-ink uppercase tracking-wider">Active KPI Performance Tracker</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($kpis as $kpi)
                @php
                    $percentage = $kpi->target > 0 ? min(100, round(($kpi->current / $kpi->target) * 100)) : 0;
                    $textColor = $percentage >= 75 ? 'text-teal' : ($percentage >= 40 ? 'text-ochre' : 'text-red-400');
                    $barColor = $percentage >= 75 ? 'bg-teal' : ($percentage >= 40 ? 'bg-ochre' : 'bg-red-500');
                @endphp
                <div class="p-4 rounded-xl bg-canvas border border-line space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] text-ink-muted font-bold uppercase tracking-wider block">{{ $kpi->project->title ?? 'General Project' }}</span>
                            <h4 class="text-xs font-bold text-ink mt-0.5">{{ $kpi->title }}</h4>
                        </div>
                        <span class="text-xs font-extrabold {{ $textColor }} font-mono">{{ $percentage }}%</span>
                    </div>

                    <div class="w-full bg-surface rounded-full h-2.5 overflow-hidden border border-line">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="flex justify-between text-[11px] text-ink-muted font-mono">
                        <span>Baseline: {{ number_format($kpi->baseline) }}</span>
                        <span class="font-bold text-ink">Current: {{ number_format($kpi->current) }}</span>
                        <span>Target: {{ number_format($kpi->target) }} {{ $kpi->unit }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-ink-muted col-span-2 text-center py-6">No KPI indicators set up yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Field Monitoring Visits -->
    <div class="bg-surface border border-line rounded-xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Recent Field Officer Monitoring Visits</h3>
            <span class="text-xs text-ink-muted">On-Site Verification</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Visit Date</th>
                        <th class="px-6 py-3.5">Project</th>
                        <th class="px-6 py-3.5">Field Officer</th>
                        <th class="px-6 py-3.5">GPS Tag</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($recentVisits as $visit)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-mono text-ink">{{ $visit->visit_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 font-semibold text-ink">{{ $visit->project->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $visit->officer->name ?? 'Field Staff' }}</td>
                            <td class="px-6 py-4 font-mono text-[11px] text-ink-muted">
                                @if($visit->latitude && $visit->longitude)
                                    {{ $visit->latitude }}, {{ $visit->longitude }}
                                @else
                                    <span class="text-ink-muted">No GPS</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-teal-soft text-teal border border-teal-dim">
                                    {{ $visit->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-muted">
                                No field visits recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>