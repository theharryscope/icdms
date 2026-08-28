<div class="bg-canvas text-ink min-h-screen font-sans selection:bg-ochre selection:text-canvas">

    <x-public.site-header />

    <!-- PAGE CONTENT -->
    <section class="py-20">
        <div class="max-w-3xl mx-auto px-6">
            <h1 class="text-3xl md:text-4xl font-display font-bold text-ink tracking-tight mt-3 mb-10 leading-tight">
                {{ $page->title }}
            </h1>

            <div class="page-content text-ink-muted leading-relaxed">
                {!! $page->content !!}
            </div>

            <style>
                .page-content h2 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.4rem; color: #f3eee3; margin: 1.75rem 0 0.75rem; }
                .page-content h3 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.15rem; color: #f3eee3; margin: 1.5rem 0 0.5rem; }
                .page-content p { margin: 0 0 1rem; }
                .page-content ul, .page-content ol { margin: 0 0 1rem; padding-left: 1.5rem; }
                .page-content li { margin-bottom: 0.4rem; }
                .page-content a { color: #db8a2e; text-decoration: underline; text-underline-offset: 2px; }
                .page-content strong { color: #f3eee3; font-weight: 700; }
                .page-content blockquote { border-left: 2px solid #35302a; padding-left: 1rem; color: #a79a85; font-style: italic; margin: 1rem 0; }
            </style>

            <div class="mt-14 pt-6 border-t border-line">
                <a href="{{ route('landing') }}" class="text-xs font-bold text-ochre hover:text-ochre/80">&larr; Back to Home</a>
            </div>
        </div>
    </section>

    <x-public.site-footer />
</div>
