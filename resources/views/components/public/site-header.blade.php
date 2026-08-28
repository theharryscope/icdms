@php
    $siteSettings = \App\Models\SiteSetting::current();
    $headerPages = \App\Models\Page::inHeader()->get();
@endphp

<header class="sticky top-0 z-50 bg-canvas/90 backdrop-blur-md border-b border-line" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

        <!-- Logo Brand -->
        <a href="{{ route('landing') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-ochre flex items-center justify-center font-display font-bold text-xl text-canvas overflow-hidden shrink-0">
                @if ($siteSettings->logo_url)
                    <img src="{{ $siteSettings->logo_url }}" class="w-full h-full object-cover" alt="{{ $siteSettings->site_name }} logo">
                @else
                    {{ substr($siteSettings->site_name, 0, 1) }}
                @endif
            </div>
            <div>
                <span class="text-base font-display font-bold tracking-tight text-ink block leading-tight">{{ strtoupper($siteSettings->site_name) }}</span>
                @if ($siteSettings->tagline)
                    <span class="text-[10px] text-ochre font-mono font-semibold uppercase tracking-widest block">{{ $siteSettings->tagline }}</span>
                @endif
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center space-x-8 text-xs font-semibold uppercase tracking-wider text-ink-muted">
            <a href="{{ route('landing') }}#about" class="hover:text-ochre transition">About</a>
            <a href="{{ route('landing') }}#command" class="hover:text-ochre transition">Regional Command</a>
            <a href="{{ route('landing') }}#programs" class="hover:text-ochre transition">Programs</a>
            <a href="{{ route('landing') }}#impact" class="hover:text-ochre transition">Impact</a>
            <a href="{{ route('landing') }}#roles" class="hover:text-ochre transition">Join Us</a>
            <a href="{{ route('public.blog') }}" class="hover:text-ochre transition">Blog</a>
            <a href="{{ route('public.gallery') }}" class="hover:text-ochre transition">Gallery</a>
            @foreach ($headerPages as $headerPage)
                <a href="{{ route('public.page', $headerPage->slug) }}" class="hover:text-ochre transition">{{ $headerPage->title }}</a>
            @endforeach
        </nav>

        <!-- Desktop Auth & Donate -->
        <div class="hidden lg:flex items-center space-x-3">
            <a href="{{ route('public.donate') }}" class="px-4 py-2.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-xs shadow-lg shadow-ochre/10 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Donate</span>
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-surface hover:bg-surface-raised border border-line text-ink rounded-lg text-xs font-bold transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-bold text-ink-muted hover:text-ink transition">
                    Sign In
                </a>
                <a href="{{ route('public.register') }}" class="px-4 py-2.5 bg-surface hover:bg-surface-raised border border-line text-ink rounded-lg text-xs font-bold transition">
                    Register
                </a>
            @endauth
        </div>

        <!-- Hamburger Toggle (mobile/tablet only) -->
        <button @click="mobileOpen = !mobileOpen" class="lg:hidden relative w-9 h-9 flex items-center justify-center text-ink" aria-label="Toggle menu">
            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="mobileOpen" x-cloak style="display: none;" class="w-6 h-6 text-ochre" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Mobile Menu Panel -->
    <div
        x-show="mobileOpen"
        x-cloak
        style="display: none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        @click.outside="mobileOpen = false"
        class="lg:hidden border-t border-line bg-surface"
    >
        <nav class="px-6 py-5 space-y-1 text-sm font-semibold text-ink-muted max-h-[75vh] overflow-y-auto">
            <a href="{{ route('landing') }}#about" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">About</a>
            <a href="{{ route('landing') }}#command" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Regional Command</a>
            <a href="{{ route('landing') }}#programs" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Programs</a>
            <a href="{{ route('landing') }}#impact" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Impact</a>
            <a href="{{ route('landing') }}#roles" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Join Us</a>
            <a href="{{ route('public.blog') }}" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Blog</a>
            <a href="{{ route('public.gallery') }}" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">Gallery</a>
            @foreach ($headerPages as $headerPage)
                <a href="{{ route('public.page', $headerPage->slug) }}" @click="mobileOpen = false" class="block py-2.5 hover:text-ochre transition">{{ $headerPage->title }}</a>
            @endforeach

            <div class="pt-4 mt-3 border-t border-line space-y-2.5">
                <a href="{{ route('public.donate') }}" class="block text-center px-4 py-3 bg-ochre text-canvas font-bold rounded-lg text-xs">
                    Donate
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center px-4 py-3 bg-surface-raised border border-line text-ink rounded-lg text-xs font-bold">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block text-center px-4 py-3 text-ink-muted font-bold text-xs">
                        Sign In
                    </a>
                    <a href="{{ route('public.register') }}" class="block text-center px-4 py-3 bg-surface-raised border border-line text-ink rounded-lg text-xs font-bold">
                        Register
                    </a>
                @endauth
            </div>
        </nav>
    </div>
</header>
