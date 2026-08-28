<div class="mx-auto max-w-3xl space-y-8">
    <div>
        <span class="inline-flex rounded-lg border border-ochre-dim bg-ochre-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-ochre">Operations</span>
        <h2 class="mt-2 text-2xl font-display font-bold tracking-tight text-ink">Send a Report</h2>
        <p class="mt-1 text-sm text-ink-muted">Send an update, issue, or field report directly to the administrator.</p>
    </div>

    @if (session()->has('message'))
        <div class="rounded-xl border border-teal-dim bg-teal-soft p-4 text-xs font-bold text-teal">{{ session('message') }}</div>
    @endif

    <form wire:submit="submit" class="space-y-6 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-8">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Report Type</label>
            <select wire:model="report_type" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
                <option value="general">General Update</option>
                <option value="field_activity">Field Activity</option>
                <option value="beneficiary">Beneficiary Concern</option>
                <option value="project">Project Update</option>
                <option value="incident">Incident or Urgent Issue</option>
            </select>
            @error('report_type') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Subject</label>
            <input wire:model="subject" type="text" placeholder="What is this report about?" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
            @error('subject') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Report Details</label>
            <textarea wire:model="details" rows="8" placeholder="Describe what happened, where it happened, and any action required..." class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm leading-relaxed text-ink focus:border-ochre focus:outline-none"></textarea>
            @error('details') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-xl bg-ochre px-5 py-3 text-sm font-bold text-canvas transition hover:bg-ochre/90 disabled:opacity-60 sm:w-auto">Send Report</button>
    </form>
</div>