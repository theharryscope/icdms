<div class="space-y-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <span class="inline-flex rounded-lg border border-teal-dim bg-teal-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-teal">Inbox</span>
            <h2 class="mt-2 text-2xl font-display font-bold tracking-tight text-ink">Notifications</h2>
            <p class="mt-1 text-sm text-ink-muted">Updates and announcements from the administration.</p>
        </div>
        @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="rounded-lg border border-line bg-surface-raised px-4 py-2 text-xs font-bold text-ink">Mark all as read</button>
        @endif
    </div>
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <article class="rounded-2xl border {{ $notification->read_at ? 'border-line bg-surface' : 'border-ochre-dim bg-ochre-soft/40' }} p-5 shadow-sm sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-ink">{{ $notification->data['subject'] ?? 'Notification' }}</h3>
                        <p class="mt-1 text-[11px] font-mono text-ink-muted">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)<button wire:click="markAsRead('{{ $notification->id }}')" class="shrink-0 text-xs font-bold text-teal">Mark read</button>@endif
                </div>
                <p class="mt-4 whitespace-pre-line border-t border-line pt-4 text-sm leading-relaxed text-ink-muted">{{ $notification->data['message'] ?? '' }}</p>
            </article>
        @empty
            <div class="rounded-2xl border border-line bg-surface p-10 text-center text-sm text-ink-muted">You have no notifications yet.</div>
        @endforelse
    </div>
    {{ $notifications->links() }}
</div>