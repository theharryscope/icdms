@php
    $siteSettings = \App\Models\SiteSetting::current();
    $footerPages = \App\Models\Page::inFooter()->get();
@endphp

<footer class="border-t border-line bg-canvas">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">
        <div class="md:col-span-2 space-y-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-ochre flex items-center justify-center font-display font-bold text-lg text-canvas overflow-hidden shrink-0">
                    @if ($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" class="w-full h-full object-cover" alt="{{ $siteSettings->site_name }} logo">
                    @else
                        {{ substr($siteSettings->site_name, 0, 1) }}
                    @endif
                </div>
                <span class="text-sm font-display font-bold text-ink tracking-tight">{{ strtoupper($siteSettings->site_name) }}</span>
            </div>
            <p class="text-xs text-ink-muted leading-relaxed max-w-sm">
                Digital skills training, community infrastructure and regional leadership programs
                across Nigeria — coordinated through the ICDMS platform and open to public scrutiny.
            </p>
        </div>
        <div>
            <h4 class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ink-muted mb-4">Explore</h4>
            <ul class="space-y-2 text-xs text-ink-muted">
                <li><a href="{{ route('landing') }}#about" class="hover:text-ochre transition">About</a></li>
                <li><a href="{{ route('landing') }}#command" class="hover:text-ochre transition">Regional Command</a></li>
                <li><a href="{{ route('landing') }}#programs" class="hover:text-ochre transition">Programs</a></li>
                <li><a href="{{ route('landing') }}#impact" class="hover:text-ochre transition">Impact</a></li>
                <li><a href="{{ route('public.blog') }}" class="hover:text-ochre transition">Blog</a></li>
                <li><a href="{{ route('public.gallery') }}" class="hover:text-ochre transition">Gallery</a></li>
                @foreach ($footerPages as $footerPage)
                    <li><a href="{{ route('public.page', $footerPage->slug) }}" class="hover:text-ochre transition">{{ $footerPage->title }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ink-muted mb-4">Get Involved</h4>
            <ul class="space-y-2 text-xs text-ink-muted">
                <li><a href="{{ route('public.register') }}" class="hover:text-ochre transition">Register</a></li>
                <li><a href="{{ route('public.donate') }}" class="hover:text-ochre transition">Donate</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-ochre transition">Sign In</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-line">
        <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[11px] text-ink-muted font-mono">&copy; {{ date('Y') }} {{ $siteSettings->site_name }}. All rights reserved.</p>
            <p class="text-[11px] text-ink-muted/60 font-mono uppercase tracking-widest">ICDMS Platform</p>
        </div>
    </div>
</footer>
