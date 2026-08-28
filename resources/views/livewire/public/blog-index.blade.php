<div class="bg-canvas text-ink min-h-screen font-sans selection:bg-ochre selection:text-canvas">

    <x-public.site-header />

    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-2xl mb-14">
                <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Blog</span>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-ink tracking-tight mt-3 leading-tight">
                    News &amp; Stories from the Field
                </h1>
                <p class="text-sm text-ink-muted leading-relaxed mt-4">
                    Updates on programs, projects and community impact — straight from the ICDMS registry.
                </p>
            </div>

            @if ($posts->isEmpty())
                <div class="border border-line border-dashed rounded-xl p-12 text-center">
                    <p class="text-sm text-ink-muted font-mono">No posts published yet — check back soon.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($posts as $post)
                        <a href="{{ route('public.blog.show', $post->slug) }}" class="group bg-surface border border-line rounded-xl overflow-hidden hover:border-ochre-dim transition flex flex-col">
                            <div class="aspect-video bg-canvas overflow-hidden">
                                @if ($post->featured_image_url)
                                    <img src="{{ $post->featured_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $post->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-ochre/40">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                <span class="text-[10px] font-mono font-semibold uppercase tracking-widest text-ochre/80">
                                    {{ $post->published_at?->format('M j, Y') }}
                                </span>
                                <h2 class="text-base font-display font-bold text-ink tracking-tight mt-2 leading-snug group-hover:text-ochre transition">
                                    {{ $post->title }}
                                </h2>
                                @if ($post->excerpt)
                                    <p class="text-xs text-ink-muted leading-relaxed mt-2 flex-1">{{ $post->excerpt }}</p>
                                @endif
                                <span class="text-xs font-bold text-teal mt-4">Read more &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>

    <x-public.site-footer />
</div>
