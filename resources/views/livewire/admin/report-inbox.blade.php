<div class="space-y-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <span class="inline-flex rounded-lg border border-ochre-dim bg-ochre-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-ochre">Admin Notifications</span>
            <h2 class="mt-2 text-2xl font-display font-bold tracking-tight text-ink">Report Inbox</h2>
            <p class="mt-1 text-sm text-ink-muted">Reports sent by coordinators, volunteers, and other authenticated roles.</p>
        </div>
        <div class="rounded-xl border border-ochre-dim bg-ochre-soft px-4 py-3 text-center">
            <span class="block text-2xl font-display font-bold text-ochre">{{ $pendingCount }}</span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-ink-muted">New Reports</span>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-xl border border-teal-dim bg-teal-soft p-4 text-xs font-bold text-teal">{{ session('message') }}</div>
    @endif

    <div class="space-y-4">
        @forelse($reports as $report)
            <article class="rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-6">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md bg-teal-soft px-2 py-1 text-[10px] font-bold uppercase text-teal">{{ str_replace('_', ' ', $report->report_type) }}</span>
                            <span class="text-[11px] font-mono text-ink-muted">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="mt-2 text-base font-bold text-ink">{{ $report->subject }}</h3>
                        <p class="mt-1 text-xs text-ink-muted">
                            From {{ $report->author->name ?? 'Unknown user' }} · {{ $report->author->email ?? 'No email' }}
                            <span class="font-semibold text-ochre">· {{ $report->author?->getRoleNames()->join(', ') ?: 'No role assigned' }}</span>
                        </p>
                    </div>
                    @if($report->status === 'pending')
                        <button wire:click="markReviewed({{ $report->id }})" class="shrink-0 rounded-lg bg-teal px-3 py-2 text-xs font-bold text-canvas transition hover:bg-teal/90">Mark Reviewed</button>
                    @else
                        <span class="shrink-0 rounded-md border border-teal-dim bg-teal-soft px-2 py-1 text-[10px] font-bold uppercase text-teal">Reviewed</span>
                    @endif
                </div>
                <p class="mt-4 whitespace-pre-line border-t border-line pt-4 text-sm leading-relaxed text-ink-muted">{{ $report->details }}</p>
            </article>
        @empty
            <div class="rounded-2xl border border-line bg-surface p-10 text-center text-sm text-ink-muted">No reports have been submitted yet.</div>
        @endforelse
    </div>

    {{ $reports->links() }}
</div>