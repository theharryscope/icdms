<div class="bg-canvas text-ink min-h-screen font-sans selection:bg-ochre selection:text-canvas">
    <x-public.site-header />
    <section class="py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-2xl mb-14"><span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Gallery</span><h1 class="text-3xl md:text-4xl font-display font-bold text-ink tracking-tight mt-3">Moments from the work</h1><p class="text-sm text-ink-muted leading-relaxed mt-4">Events, meetings and academy activities from across the foundation.</p></div>
            @if($albums->isEmpty())
                <div class="border border-line border-dashed rounded-xl p-12 text-center"><p class="text-sm text-ink-muted font-mono">No gallery albums published yet — check back soon.</p></div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($albums as $album)
                        <a href="{{ route('public.gallery.show', $album->slug) }}" class="group bg-surface border border-line rounded-xl overflow-hidden hover:border-ochre-dim transition">
                            <div class="aspect-[4/3] bg-canvas grid grid-cols-2 gap-0.5 overflow-hidden">
                                @foreach($album->images->take(4) as $image)<img src="{{ $image->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $image->caption ?: $album->title }}">@endforeach
                            </div>
                            <div class="p-5"><div class="flex items-center justify-between gap-3"><span class="text-[10px] font-mono font-semibold uppercase tracking-widest text-ochre">{{ $album->category }}</span><span class="text-[10px] text-ink-muted font-mono">{{ $album->images->count() }} photos</span></div><h2 class="text-base font-display font-bold text-ink mt-2 group-hover:text-ochre transition">{{ $album->title }}</h2>@if($album->description)<p class="text-xs text-ink-muted leading-relaxed mt-2 line-clamp-2">{{ $album->description }}</p>@endif</div>
                        </a>
                    @endforeach
                </div>
                @if($albums->hasPages())<div class="mt-10">{{ $albums->links() }}</div>@endif
            @endif
        </div>
    </section>
    <x-public.site-footer />
</div>
