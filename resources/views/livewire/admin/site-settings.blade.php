<div class="max-w-4xl mx-auto space-y-8">

    <div>
        <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Configuration</span>
        <h1 class="text-2xl font-display font-bold text-ink tracking-tight mt-2">Site Settings</h1>
        <p class="text-sm text-ink-muted mt-1">Branding, contact details, and social links used across the public site and dashboard.</p>
    </div>

    @if (session('message'))
        <div class="bg-teal-soft border border-teal-dim rounded-lg px-4 py-3 flex items-center space-x-2.5">
            <svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-teal font-medium">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-8">

        <!-- Branding -->
        <div class="bg-surface border border-line rounded-xl p-6 space-y-6">
            <h2 class="text-sm font-bold text-ink uppercase tracking-wider">Branding</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Logo -->
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-2">Site Logo</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-lg bg-canvas border border-line flex items-center justify-center overflow-hidden shrink-0">
                            @if ($newLogo)
                                <img src="{{ $newLogo->temporaryUrl() }}" class="w-full h-full object-cover" alt="New logo preview">
                            @elseif ($settings->logo_url)
                                <img src="{{ $settings->logo_url }}" class="w-full h-full object-cover" alt="Current logo">
                            @else
                                <span class="text-xl font-display font-bold text-ochre">{{ substr($site_name ?: 'I', 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="newLogo" accept="image/*" class="w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-surface-raised file:text-ink file:text-xs file:font-bold">
                            <p class="text-[10px] text-ink-muted/70 mt-1">PNG or JPG, up to 2MB. Square works best.</p>
                            @error('newLogo') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-2">Favicon</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-lg bg-canvas border border-line flex items-center justify-center overflow-hidden shrink-0">
                            @if ($newFavicon)
                                <img src="{{ $newFavicon->temporaryUrl() }}" class="w-8 h-8 object-contain" alt="New favicon preview">
                            @elseif ($settings->favicon_url)
                                <img src="{{ $settings->favicon_url }}" class="w-8 h-8 object-contain" alt="Current favicon">
                            @else
                                <span class="text-[10px] text-ink-muted/60 text-center px-1">None set</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" wire:model="newFavicon" accept="image/png,image/x-icon,.ico" class="w-full text-xs text-ink-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-surface-raised file:text-ink file:text-xs file:font-bold">
                            <p class="text-[10px] text-ink-muted/70 mt-1">.ico or .png, up to 512KB. 32×32 or 64×64 recommended.</p>
                            @error('newFavicon') <span class="text-red-400 text-[10px] block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Site Name</label>
                    <input type="text" wire:model="site_name" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('site_name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Tagline</label>
                    <input type="text" wire:model="tagline" placeholder="e.g. Digital Skills, Regional Command, Verified Impact" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('tagline') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-surface border border-line rounded-xl p-6 space-y-4">
            <h2 class="text-sm font-bold text-ink uppercase tracking-wider">Contact Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Contact Email</label>
                    <input type="email" wire:model="contact_email" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('contact_email') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Contact Phone</label>
                    <input type="text" wire:model="contact_phone" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre font-mono">
                    @error('contact_phone') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Address</label>
                    <input type="text" wire:model="address" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('address') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Social -->
        <div class="bg-surface border border-line rounded-xl p-6 space-y-4">
            <h2 class="text-sm font-bold text-ink uppercase tracking-wider">Social Links</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Facebook URL</label>
                    <input type="url" wire:model="facebook_url" placeholder="https://facebook.com/..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('facebook_url') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Twitter / X URL</label>
                    <input type="url" wire:model="twitter_url" placeholder="https://x.com/..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('twitter_url') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">Instagram URL</label>
                    <input type="url" wire:model="instagram_url" placeholder="https://instagram.com/..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('instagram_url') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">LinkedIn URL</label>
                    <input type="url" wire:model="linkedin_url" placeholder="https://linkedin.com/..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('linkedin_url') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- WhatsApp Groups -->
        <div class="bg-surface border border-line rounded-xl p-6 space-y-5">
            <div>
                <h2 class="text-sm font-bold text-ink uppercase tracking-wider">WhatsApp Role Groups</h2>
                <p class="text-[11px] text-ink-muted mt-1">Add a group link for each role. Matching users will see it on their dashboard.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($roles as $role)
                    <div>
                        <label class="block text-[11px] font-bold text-ink-muted uppercase mb-1">{{ $role->name }}</label>
                        <input type="url" wire:model="whatsapp_group_links.{{ $role->name }}" placeholder="https://chat.whatsapp.com/..." class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                        @error('whatsapp_group_links.' . $role->name) <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-xl text-xs shadow-lg shadow-ochre/10 transition" wire:loading.attr="disabled" wire:target="save,newLogo,newFavicon">
                <span wire:loading.remove wire:target="save">Save Settings</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>
