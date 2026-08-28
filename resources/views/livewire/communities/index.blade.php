<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Community Management</h2>
            <p class="text-sm text-ink-muted mt-1">Geographical locations, population metrics, and target needs assessment.</p>
        </div>
        <a href="{{ route('communities.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Register Community</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-surface border border-line rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-80">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search community name or LGA..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ochre">
        </div>
    </div>

    <!-- Communities Table -->
    <div class="bg-surface border border-line rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Community Name</th>
                        <th class="px-6 py-3.5">State & LGA</th>
                        <th class="px-6 py-3.5">GPS Coordinates</th>
                        <th class="px-6 py-3.5">Population</th>
                        <th class="px-6 py-3.5">Projects / Beneficiaries</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($communities as $community)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-semibold text-ink">{{ $community->name }}</td>
                            <td class="px-6 py-4">
                                <span class="text-ink block font-medium">{{ $community->lga }} LGA</span>
                                <span class="text-[10px] text-ink-muted">{{ $community->state }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-[11px] text-ink-muted">
                                @if($community->latitude && $community->longitude)
                                    Lat: {{ $community->latitude }} <br> Long: {{ $community->longitude }}
                                @else
                                    <span class="text-ink-muted">No GPS set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-ink">
                                {{ number_format($community->estimated_population) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim">
                                        {{ $community->projects_count }} Projects
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim">
                                        {{ $community->beneficiaries_count }} Beneficiaries
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-muted">
                                No communities registered yet. Click above to add your first location.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-line">
            {{ $communities->links() }}
        </div>
    </div>
</div>