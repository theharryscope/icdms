<div class="mx-auto max-w-3xl space-y-8">
    <div>
        <span class="inline-flex rounded-lg border border-ochre-dim bg-ochre-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-ochre">Admin Communications</span>
        <h2 class="mt-2 text-2xl font-display font-bold tracking-tight text-ink">Send Notification</h2>
        <p class="mt-1 text-sm text-ink-muted">Send an in-dashboard and email notification to selected recipients.</p>
    </div>

    @if (session()->has('message'))
        <div class="rounded-xl border border-teal-dim bg-teal-soft p-4 text-xs font-bold text-teal">{{ session('message') }}</div>
    @endif

    <form wire:submit="send" class="space-y-6 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-8">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Recipients</label>
            <select wire:model.live="recipient_type" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
                <option value="all">All Users</option>
                <option value="user">Specific User</option>
                <option value="role">Specific Role</option>
            </select>
            @error('recipient_type') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>

        @if($recipient_type === 'user')
            <select wire:model="recipient_id" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
                <option value="">Choose a user</option>
                @foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }} · {{ $user->email }}</option>@endforeach
            </select>
            @error('recipient_id') <span class="block text-xs text-red-400">{{ $message }}</span> @enderror
        @elseif($recipient_type === 'role')
            <select wire:model.live="role_name" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
                <option value="">Choose a role</option>
                @foreach($roles as $role)<option value="{{ $role->name }}">{{ $role->name }}</option>@endforeach
            </select>
            @error('role_name') <span class="block text-xs text-red-400">{{ $message }}</span> @enderror
        @endif

        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Subject</label>
            <input wire:model="subject" type="text" placeholder="Notification subject" class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm text-ink focus:border-ochre focus:outline-none">
            @error('subject') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-ink-muted">Message</label>
            <textarea wire:model="message" rows="7" placeholder="Write the notification message..." class="w-full rounded-xl border border-line bg-canvas px-4 py-3 text-sm leading-relaxed text-ink focus:border-ochre focus:outline-none"></textarea>
            @error('message') <span class="mt-1 block text-xs text-red-400">{{ $message }}</span> @enderror
        </div>
        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-xl bg-ochre px-5 py-3 text-sm font-bold text-canvas transition hover:bg-ochre/90 disabled:opacity-60 sm:w-auto">Send Notification</button>
    </form>
</div>