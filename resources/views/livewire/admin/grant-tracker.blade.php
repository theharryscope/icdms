<div class="space-y-8" x-data="{ selectedReceipt: null }">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Donor & Grant Funding Tracker</h2>
            <p class="text-sm text-ink-muted mt-1">Manage institutional donors, track grant allocations, and review public online donations.</p>
        </div>

        <div class="flex items-center space-x-3">
            <button wire:click="$set('showDonorModal', true)" class="px-4 py-2 bg-surface-raised hover:bg-line text-ink rounded-xl text-xs font-bold transition">
                + Add Donor Partner
            </button>
            <button wire:click="$set('showGrantModal', true)" class="px-4 py-2 bg-ochre hover:bg-ochre/90 text-canvas rounded-xl text-xs font-bold shadow-lg shadow-ochre/10 transition">
                + New Grant Funding
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider block">Total Grant Commitments</span>
            <span class="text-2xl font-display font-bold text-ink font-mono mt-2 block">₦{{ number_format($totalGrantFunding, 2) }}</span>
        </div>

        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider block">Public Online Donations</span>
            <span class="text-2xl font-black text-teal font-mono mt-2 block">₦{{ number_format($totalPublicDonations ?? 0, 2) }}</span>
        </div>

        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider block">Total Disbursed Funds</span>
            <span class="text-2xl font-black text-teal font-mono mt-2 block">₦{{ number_format($totalDisbursedFunding, 2) }}</span>
        </div>

        <div class="bg-surface border border-line rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-ink-muted uppercase tracking-wider block">Active Donors</span>
            <span class="text-2xl font-black text-ochre font-mono mt-2 block">{{ $donors->count() }} Corporate Partners</span>
        </div>
    </div>

    <!-- Institutional Grants Directory Table -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <h3 class="text-sm font-bold text-ink">Grant Commitments & Disbursements</h3>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search title or grant code..." class="bg-canvas border border-line rounded-lg px-3 py-1.5 text-xs text-ink focus:outline-none focus:border-ochre">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Code / Grant Title</th>
                        <th class="px-6 py-3.5">Donor Partner</th>
                        <th class="px-6 py-3.5">Linked Project</th>
                        <th class="px-6 py-3.5">Total Grant</th>
                        <th class="px-6 py-3.5">Disbursed</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($grants as $grant)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block text-sm">{{ $grant->grant_title }}</span>
                                <span class="text-[10px] text-teal font-mono">{{ $grant->grant_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-ink font-medium block">{{ $grant->donor->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-ink-muted font-mono">{{ $grant->donor->country ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-ink-muted">{{ $grant->project->title ?? 'General Fund' }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-ink font-bold">
                                ₦{{ number_format($grant->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 font-mono text-teal font-bold">
                                ₦{{ number_format($grant->disbursed_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">
                                    {{ $grant->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ink-muted">
                                No grant funding records created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-line">
            {{ $grants->links() }}
        </div>
    </div>

    <!-- Public Online & Bank Transfer Donations Feed -->
    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-line flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-ink">Public Online & Bank Transfer Donations</h3>
                <p class="text-[11px] text-ink-muted">Review, approve, or reject incoming public contributions and payment receipts.</p>
            </div>
            <span class="px-2.5 py-1 bg-teal-soft text-teal border border-teal-dim text-xs font-bold font-mono rounded-lg">
                Verified Public Total: ₦{{ number_format($totalPublicDonations ?? 0, 2) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Reference / Donor</th>
                        <th class="px-6 py-3.5">Method</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Receipt / Proof</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Approval Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-ink-muted">
                    @forelse($publicDonations ?? [] as $donation)
                        <tr class="hover:bg-surface-raised/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-ink block text-sm">{{ $donation->donor_name }}</span>
                                <span class="text-[10px] text-teal font-mono">{{ $donation->reference_code }}</span>
                                <span class="text-[10px] text-ink-muted block">{{ $donation->donor_email }}</span>
                                @if($donation->donor_phone)
                                    <span class="text-[10px] text-ink-muted font-mono block">{{ $donation->donor_phone }}</span>
                                @endif
                                @if($donation->notes)
                                    <p class="text-[10px] text-ink-muted italic mt-1 bg-canvas p-1.5 rounded border border-line">
                                        "{{ $donation->notes }}"
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs uppercase text-ink font-semibold">
                                    {{ str_replace('_', ' ', $donation->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-teal text-sm">
                                ₦{{ number_format($donation->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($donation->proof_of_payment_path)
                                    <button @click="selectedReceipt = '{{ asset('storage/' . $donation->proof_of_payment_path) }}'" type="button" class="px-2.5 py-1 bg-surface-raised hover:bg-line border border-line text-teal rounded-lg text-xs font-semibold flex items-center space-x-1.5 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>View Receipt</span>
                                    </button>
                                @else
                                    <span class="text-ink-muted text-[10px] font-mono">Paystack Direct</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($donation->payment_status === 'successful')
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">Successful</span>
                                @elseif($donation->payment_status === 'failed' || $donation->payment_status === 'rejected')
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 uppercase">Rejected</span>
                                @else
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-ochre-soft text-ochre border border-ochre-dim uppercase">Pending Review</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($donation->payment_status !== 'successful')
                                        <button wire:click="verifyDonation({{ $donation->id }})" class="px-3 py-1.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow transition">
                                            Approve
                                        </button>
                                    @endif

                                    @if($donation->payment_status !== 'rejected' && $donation->payment_status !== 'failed')
                                        <button wire:click="rejectDonation({{ $donation->id }})" wire:confirm="Are you sure you want to reject this donation payment?" class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/30 font-bold rounded-lg text-xs transition">
                                            Reject
                                        </button>
                                    @endif

                                    @if($donation->payment_status === 'successful')
                                        <span class="text-ink-muted text-[10px] font-semibold">Verified</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ink-muted">
                                No public donations recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div x-show="selectedReceipt" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/85 backdrop-blur-sm p-4">
        <div class="bg-surface border border-line rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-line pb-3">
                <h3 class="text-sm font-bold text-ink">Payment Proof Receipt Preview</h3>
                <button @click="selectedReceipt = null" type="button" class="text-ink-muted hover:text-ink">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[70vh] overflow-y-auto flex items-center justify-center bg-canvas rounded-xl p-2 border border-line">
                <img :src="selectedReceipt" class="max-w-full h-auto rounded-lg object-contain" alt="Payment Proof Receipt">
            </div>

            <div class="flex justify-between items-center pt-2">
                <a :href="selectedReceipt" target="_blank" class="text-xs text-teal hover:underline font-bold">Open Original File &rarr;</a>
                <button @click="selectedReceipt = null" type="button" class="px-4 py-2 bg-surface-raised text-ink rounded-lg text-xs font-bold">Close Preview</button>
            </div>
        </div>
    </div>

    <!-- Create Donor Partner Modal -->
    @if($showDonorModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-md overflow-hidden shadow-2xl p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Register Donor Partner</h3>
                    <button wire:click="$set('showDonorModal', false)" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="createDonor" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Donor Name / Organization</label>
                        <input type="text" wire:model="donor_name" placeholder="e.g. Ford Foundation, UNDP" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                        @error('donor_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Donor Category</label>
                        <select wire:model="donor_type" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                            <option value="grant_body">International Grant Body / NGO</option>
                            <option value="corporate">Corporate Enterprise</option>
                            <option value="individual">Individual Philanthropist</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Email</label>
                            <input type="email" wire:model="donor_email" placeholder="contact@donor.org" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>

                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Country</label>
                            <input type="text" wire:model="donor_country" placeholder="Nigeria" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="$set('showDonorModal', false)" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg shadow">Register Donor</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Create Grant Modal -->
    @if($showGrantModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-canvas/80 backdrop-blur-sm p-4">
            <div class="bg-surface border border-line rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-line pb-3">
                    <h3 class="text-sm font-bold text-ink">Record Grant Funding</h3>
                    <button wire:click="$set('showGrantModal', false)" class="text-ink-muted hover:text-ink">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="createGrant" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Donor Partner</label>
                        <select wire:model="donor_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                            <option value="">Select Donor</option>
                            @foreach($donors as $donor)
                                <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                            @endforeach
                        </select>
                        @error('donor_id') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Grant Title</label>
                        <input type="text" wire:model="grant_title" placeholder="e.g. Grassroots Tech Education Grant 2026" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                        @error('grant_title') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Total Grant (₦)</label>
                            <input type="number" step="0.01" wire:model="total_amount" placeholder="5000000" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre font-mono">
                            @error('total_amount') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Disbursed (₦)</label>
                            <input type="number" step="0.01" wire:model="disbursed_amount" placeholder="2500000" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-ink-muted uppercase mb-1">Assign to Project (Optional)</label>
                        <select wire:model="project_id" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                            <option value="">General Organizational Fund</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                        </div>
                        <div>
                            <label class="block font-bold text-ink-muted uppercase mb-1">End Date</label>
                            <input type="date" wire:model="end_date" class="w-full bg-canvas border border-line rounded-lg px-3 py-2 text-ink focus:outline-none focus:border-ochre">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-line">
                        <button type="button" wire:click="$set('showGrantModal', false)" class="px-4 py-2 bg-surface-raised text-ink-muted rounded-lg">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg shadow">Save Grant Record</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>