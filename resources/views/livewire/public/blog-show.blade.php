<div class="bg-canvas text-ink min-h-screen font-sans selection:bg-ochre selection:text-canvas">

    <x-public.site-header />

    <section class="py-20">
        <div class="max-w-3xl mx-auto px-6">
            <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Blog</span>
            <h1 class="text-3xl md:text-4xl font-display font-bold text-ink tracking-tight mt-3 mb-4 leading-tight">
                {{ $post->title }}
            </h1>
            <p class="text-xs font-mono text-ink-muted uppercase tracking-wide mb-10">
                {{ $post->published_at?->format('F j, Y') }}
                @if ($post->creator)
                    &middot; By {{ $post->creator->name }}
                @endif
            </p>

            @if ($post->featured_image_url)
                <img src="{{ $post->featured_image_url }}" class="w-full aspect-video object-cover rounded-xl mb-10" alt="{{ $post->title }}">
            @endif

            <div class="post-content text-ink-muted leading-relaxed">
                {!! $post->content !!}
            </div>

            <style>
                .post-content h2 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.4rem; color: #f3eee3; margin: 1.75rem 0 0.75rem; }
                .post-content h3 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.15rem; color: #f3eee3; margin: 1.5rem 0 0.5rem; }
                .post-content p { margin: 0 0 1rem; }
                .post-content ul, .post-content ol { margin: 0 0 1rem; padding-left: 1.5rem; }
                .post-content li { margin-bottom: 0.4rem; }
                .post-content a { color: #db8a2e; text-decoration: underline; text-underline-offset: 2px; }
                .post-content strong { color: #f3eee3; font-weight: 700; }
                .post-content img { border-radius: 0.75rem; margin: 1rem 0; }
                .post-content blockquote { border-left: 2px solid #35302a; padding-left: 1rem; color: #a79a85; font-style: italic; margin: 1rem 0; }
            </style>

            <div class="mt-14 pt-6 border-t border-line">
                <a href="{{ route('public.blog') }}" class="text-xs font-bold text-ochre hover:text-ochre/80">&larr; Back to Blog</a>
            </div>

            @if ($relatedPosts->isNotEmpty())
                <div class="mt-14 pt-10 border-t border-line">
                    <h2 class="text-sm font-mono font-semibold uppercase tracking-widest text-ink-muted mb-6">More from the Blog</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        @foreach ($relatedPosts as $related)
                            <a href="{{ route('public.blog.show', $related->slug) }}" class="group">
                                <div class="aspect-video bg-surface border border-line rounded-lg overflow-hidden mb-2">
                                    @if ($related->featured_image_url)
                                        <img src="{{ $related->featured_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $related->title }}">
                                    @endif
                                </div>
                                <p class="text-xs font-bold text-ink group-hover:text-ochre transition leading-snug">{{ $related->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-public.site-footer />
</div>
