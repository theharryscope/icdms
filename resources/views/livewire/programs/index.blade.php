<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Programs Management</h2>
            <p class="text-sm text-ink-muted mt-1">Strategic focus areas and developmental program directories.</p>
        </div>
        <a href="{{ route('programs.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-lg text-xs font-semibold shadow-lg shadow-ochre/10 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create Program</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-surface border border-line rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="w-full md:w-80">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title or code..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ochre">
        </div>
        <div class="w-full md:w-auto flex items-center space-x-3">
            <select wire:model.live="statusFilter" class="bg-canvas border border-line rounded-lg px-3.5 py-2 text-xs text-ink-muted focus:outline-none focus:border-ochre">
                <option value="">All Statuses</option>
                <option value="planning">Planning</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Programs Table -->
    <div class="bg-surface border border-line rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Code & Title</th>
                        <th class="px-6 py-3.5">Manager</th>
                        <th class="px-6 py-3.5">Budget</th>
                        <th class="px-6 py-3.5">Projects</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($programs as $program)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-semibold text-ink">
                                <span class="text-teal font-mono text-[11px] block">{{ $program->program_code }}</span>
                                {{ $program->title }}
                            </td>
                            <td class="px-6 py-4">{{ $program->manager->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-ink">₦{{ number_format($program->budget, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-surface-raised text-ink-muted border border-line">
                                    {{ $program->projects_count }} Projects
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-teal-soft text-teal border border-teal-dim">
                                    {{ $program->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-xs text-ink-muted hover:text-ink font-medium">View Details</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ink-muted">
                                No programs found. Create your first strategic program above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-line">
            {{ $programs->links() }}
        </div>
    </div>
</div>