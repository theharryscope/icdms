<div class="space-y-8">
    <div class="bg-gradient-to-r from-teal-soft via-surface to-surface border border-teal-dim rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="inline-flex px-3 py-1 bg-teal-soft text-teal border border-teal-dim rounded-lg text-xs font-bold uppercase tracking-wider">Donor Portal</span>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Your Donation History</h2>
            <p class="text-xs text-ink-muted mt-1">Track every contribution made with {{ auth()->user()->email }}.</p>
        </div>
        <a href="{{ route('public.donate') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas rounded-xl text-xs font-bold shadow-lg shadow-ochre/10 transition">Make Another Donation</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Verified Giving</span>
            <span class="block mt-3 text-2xl font-display font-bold text-teal font-mono">₦{{ number_format($totalDonated, 2) }}</span>
        </div>
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Successful Donations</span>
            <span class="block mt-3 text-2xl font-display font-bold text-ink font-mono">{{ $successfulCount }}</span>
        </div>
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider">Awaiting Review</span>
            <span class="block mt-3 text-2xl font-display font-bold text-ochre font-mono">{{ $pendingCount }}</span>
        </div>
    </div>

    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line">
            <h3 class="text-sm font-bold text-ink">Your Contributions</h3>
            <p class="text-[11px] text-ink-muted mt-1">Payment status updates appear here after review or verification.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Reference</th>
                        <th class="px-6 py-3.5">Date</th>
                        <th class="px-6 py-3.5">Method</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-teal">{{ $donation->reference_code }}</td>
                            <td class="px-6 py-4 font-mono">{{ $donation->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 uppercase">{{ str_replace('_', ' ', $donation->payment_method) }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-ink">₦{{ number_format($donation->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($donation->payment_status === 'successful')
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">Successful</span>
                                @elseif(in_array($donation->payment_status, ['rejected', 'failed']))
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 uppercase">Rejected</span>
                                @else
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim uppercase">Pending Review</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-ink-muted">No donations are linked to this account yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>