<div class="space-y-8 max-w-4xl mx-auto">
    
    <!-- Page Header -->
    <div class="border-b border-line pb-5">
        <h2 class="text-2xl font-display font-bold text-ink tracking-tight">Account Profile & Security</h2>
        <p class="text-xs text-ink-muted mt-1">Manage your account information, contact credentials, and authentication security.</p>
    </div>

    <!-- User Account Summary Banner -->
    <div class="bg-surface border border-line rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-surface-raised border-2 border-teal/40 flex items-center justify-center font-black text-teal text-xl shadow-lg">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-ink">{{ $user->name }}</h3>
                <p class="text-xs text-ink-muted font-mono">{{ $user->email }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-soft text-teal border border-teal-dim uppercase">
                        {{ $user->getRoleNames()->first() ?? $user->registration_role ?? 'Member' }}
                    </span>
                    @if($user->zoneState)
                        <span class="text-[10px] text-ink-muted font-medium">
                            &bull; {{ $user->zoneState->name }} State
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-right text-xs text-ink-muted">
            <span>Account ID: <strong class="font-mono text-ink-muted">#US-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Section 1: Contact Information -->
        <div class="bg-surface border border-line rounded-2xl p-6 space-y-4">
            <div class="border-b border-line pb-3">
                <h3 class="text-sm font-bold text-ink">Personal Details</h3>
                <p class="text-[11px] text-ink-muted">Update your account name and contact email.</p>
            </div>

            @if (session()->has('profile_message'))
                <div class="p-3 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
                    {{ session('profile_message') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre">
                    @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre font-mono">
                    @error('email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Phone Number</label>
                    <input type="text" wire:model="phone" placeholder="+234..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre font-mono">
                    @error('phone') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-xl text-xs shadow-lg shadow-ochre/10 transition">
                        Save Profile Details
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 2: Change Password -->
        <div class="bg-surface border border-line rounded-2xl p-6 space-y-4">
            <div class="border-b border-line pb-3">
                <h3 class="text-sm font-bold text-ink">Update Password</h3>
                <p class="text-[11px] text-ink-muted">Ensure your account is using a strong password.</p>
            </div>

            @if (session()->has('password_message'))
                <div class="p-3 bg-teal-soft border border-teal-dim text-teal text-xs rounded-xl font-bold">
                    {{ session('password_message') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Current Password</label>
                    <input type="password" wire:model="current_password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre">
                    @error('current_password') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">New Password</label>
                    <input type="password" wire:model="new_password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre">
                    @error('new_password') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-ink-muted uppercase mb-1">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirmation" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-ink focus:outline-none focus:border-ochre">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-teal hover:bg-teal/90 text-canvas font-bold rounded-xl text-xs shadow-lg shadow-teal/10 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>