<div class="min-h-screen flex flex-col bg-canvas">

    <x-public.site-header />

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-surface border border-line rounded-2xl p-8 shadow-2xl space-y-6">

            <div class="text-center space-y-2">
                <h2 class="text-2xl font-display font-bold text-ink tracking-tight">ICDMS Portal Login</h2>
                <p class="text-xs text-ink-muted">Sign in to access your dashboard</p>
            </div>

            @if (session('message'))
                <div class="bg-teal-soft border border-teal-dim rounded-lg px-4 py-3 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-teal font-medium leading-relaxed">{{ session('message') }}</p>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-teal-soft border border-teal-dim rounded-lg px-4 py-3">
                    <p class="text-xs text-teal font-medium">{{ session('status') }}</p>
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" wire:model="email" placeholder="you@innotech.org" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ochre">
                    @error('email') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink-muted uppercase tracking-wider mb-2">Password</label>
                    <input type="password" wire:model="password" placeholder="••••••••" class="w-full bg-canvas border border-line rounded-lg px-3.5 py-2.5 text-[16px] sm:text-xs text-ink focus:outline-none focus:border-ochre">
                    @error('password') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between text-xs text-ink-muted pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model="remember" class="rounded bg-canvas border-line text-ochre focus:ring-ochre">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Google reCAPTCHA Container -->
                <div class="space-y-2 pt-2 border-t border-line">
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div wire:ignore class="flex justify-center">
                        <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}" data-theme="dark" data-callback="onLoginCaptchaSuccess"></div>
                    </div>
                    @error('recaptcha_token')
                        <span class="text-red-400 text-[10px] font-bold block text-center mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow-lg shadow-ochre/10 transition">
                    Sign In to ICDMS
                </button>
            </form>

            <div class="text-center pt-2">
                <p class="text-xs text-ink-muted">Not on the roster yet? <a href="{{ route('public.register') }}" class="text-ochre font-semibold hover:text-ochre/80">Apply here</a></p>
            </div>
        </div>
    </div>

    <x-public.site-footer />
</div>

<script>
    function onLoginCaptchaSuccess(token) {
        @this.set('recaptcha_token', token);
    }
</script>
