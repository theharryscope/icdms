<div class="bg-canvas text-ink min-h-screen font-sans selection:bg-ochre selection:text-canvas">

    <!-- Live Ops Ticker -->
    <div class="bg-ochre text-canvas overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 py-2 flex items-center gap-3 text-[11px] font-mono font-semibold uppercase tracking-wider">
            <span class="relative flex h-2 w-2 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-canvas/60"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-canvas"></span>
            </span>
            <span class="truncate">
                Live — {{ number_format($totalBeneficiariesCount) }} beneficiaries on file
                &middot; {{ $activeProjectsCount }} project{{ $activeProjectsCount === 1 ? '' : 's' }} active in field
                &middot; {{ $statesCount }} state jurisdiction{{ $statesCount === 1 ? '' : 's' }} under Regional Command
            </span>
        </div>
    </div>

    <x-public.site-header />

    <!-- HERO -->
    <section class="relative pt-16 pb-24 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_70%_60%_at_15%_0%,rgba(219,138,46,0.12),rgba(0,0,0,0))]"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left: Thesis -->
                <div class="lg:col-span-6 space-y-7">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-mono font-semibold uppercase tracking-widest bg-ochre-soft text-ochre border border-ochre-dim">
                        ICDMS &middot; Integrated Community Development Management System
                    </span>
                    <h1 class="text-4xl md:text-5xl font-display font-bold text-ink tracking-tight leading-[1.08]">
                        Community development, run like an operation — not a mailing list.
                    </h1>
                    <p class="text-base md:text-lg text-ink-muted max-w-xl leading-relaxed">
                        InnoTech Future Foundation trains developers, funds field projects, and deploys
                        coordinators across a real chain of command — zone, state, LGA, community. Every
                        project has a file. Every naira is logged. Every KPI is public.
                    </p>

                    <div class="flex flex-col sm:flex-row items-start gap-4 pt-2">
                        <a href="{{ route('public.register') }}" class="w-full sm:w-auto px-7 py-3.5 bg-ochre hover:bg-ochre/90 text-canvas font-bold rounded-lg text-sm shadow-xl shadow-ochre/10 transition text-center">
                            Join the Command &rarr;
                        </a>
                        <a href="{{ route('public.donate') }}" class="w-full sm:w-auto px-7 py-3.5 bg-transparent border border-teal-dim hover:border-teal text-teal font-bold rounded-lg text-sm transition text-center">
                            Fund a Field Project
                        </a>
                    </div>

                    <!-- Quick trust strip -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-2 pt-4 text-[11px] font-mono text-ink-muted uppercase tracking-wide">
                        <span>{{ $totalProjectsCount }} project file{{ $totalProjectsCount === 1 ? '' : 's' }} opened</span>
                        <span class="text-line">|</span>
                        <span>{{ $totalProgramsCount }} program{{ $totalProgramsCount === 1 ? '' : 's' }} running</span>
                        <span class="text-line">|</span>
                        <span>{{ number_format($totalCommunitiesCount) }} communit{{ $totalCommunitiesCount === 1 ? 'y' : 'ies' }} reached</span>
                    </div>
                </div>

                <!-- Right: Live Case File -->
                <div class="lg:col-span-6">
                    @if($caseFileProject)
                        @php
                            $kpiPct = $caseFileKpi && $caseFileKpi->target > 0
                                ? min(100, round(($caseFileKpi->current / $caseFileKpi->target) * 100))
                                : null;
                        @endphp
                        <div class="relative max-w-md mx-auto lg:ml-auto">
                            <div class="absolute -top-3 left-10 w-8 h-14 border-4 border-line rounded-full rotate-12 hidden sm:block"></div>

                            <div class="bg-paper text-canvas rounded-xl shadow-2xl shadow-black/40 rotate-1 hover:rotate-0 transition-transform duration-300 border border-paper-dim">
                                <div class="px-6 pt-6 pb-4 border-b-2 border-dashed border-paper-dim/80 flex items-start justify-between">
                                    <div>
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-canvas/50">Field Case File</span>
                                        <h3 class="text-lg font-display font-bold leading-snug mt-1">{{ $caseFileProject->title }}</h3>
                                    </div>
                                    <span class="shrink-0 ml-3 px-2 py-1 rounded text-[10px] font-mono font-bold uppercase bg-teal-soft text-teal-dim border border-teal">
                                        {{ str_replace('_', ' ', $caseFileProject->status) }}
                                    </span>
                                </div>

                                <div class="px-6 py-4 space-y-3 font-mono text-[12px]">
                                    <div class="flex justify-between">
                                        <span class="text-canvas/50 uppercase tracking-wide">File Ref</span>
                                        <span class="font-semibold">{{ $caseFileProject->project_code }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-canvas/50 uppercase tracking-wide">Program</span>
                                        <span class="font-semibold text-right">{{ $caseFileProject->program->title ?? 'Unassigned' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-canvas/50 uppercase tracking-wide">Location</span>
                                        <span class="font-semibold text-right">
                                            {{ $caseFileProject->community->name ?? 'N/A' }}{{ $caseFileProject->community?->lga ? ', ' . $caseFileProject->community->lga : '' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-canvas/50 uppercase tracking-wide">Coordinates</span>
                                        <span class="font-semibold">
                                            @if($caseFileProject->community?->latitude)
                                                {{ number_format($caseFileProject->community->latitude, 4) }}&deg;N, {{ number_format($caseFileProject->community->longitude, 4) }}&deg;E
                                            @else
                                                Pending GPS log
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-canvas/50 uppercase tracking-wide">Budget Deployed</span>
                                        <span class="font-semibold">&#8358;{{ number_format($caseFileProject->expenditure, 0) }} / &#8358;{{ number_format($caseFileProject->budget, 0) }}</span>
                                    </div>
                                </div>

                                @if($caseFileKpi)
                                    <div class="px-6 pb-6 pt-1">
                                        <div class="flex justify-between text-[11px] font-mono mb-1.5">
                                            <span class="uppercase tracking-wide text-canvas/60">{{ $caseFileKpi->title }}</span>
                                            <span class="font-bold text-teal-dim">{{ $kpiPct }}%</span>
                                        </div>
                                        <div class="w-full bg-canvas/10 rounded-full h-2 overflow-hidden">
                                            <div class="bg-teal-dim h-2 rounded-full" style="width: {{ $kpiPct }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-mono text-canvas/50 mt-1">
                                            <span>{{ number_format($caseFileKpi->current) }} logged</span>
                                            <span>Target {{ number_format($caseFileKpi->target) }} {{ $caseFileKpi->unit }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="px-6 py-3 bg-canvas/5 border-t border-paper-dim/80 rounded-b-xl">
                                    <span class="text-[10px] font-mono text-canvas/40 uppercase tracking-widest">
                                        Pulled live from the ICDMS registry — not a stock photo
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="max-w-md mx-auto lg:ml-auto bg-surface border border-line border-dashed rounded-xl p-10 text-center">
                            <p class="text-sm text-ink-muted font-mono">No project files opened yet.<br>The first one will appear here automatically.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="py-24 border-t border-line">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-4">
                    <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">About the Foundation</span>
                    <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3 leading-tight">
                        A tech-skills and development NGO that files paperwork like an audit team.
                    </h2>
                    <p class="text-sm text-ink-muted leading-relaxed mt-4">
                        InnoTech Future Foundation was built on a simple premise: development work in Nigeria
                        earns trust when it's traceable — down to the project code, the field officer, and the
                        GPS coordinate. The ICDMS platform is that paper trail, made public.
                    </p>
                </div>

                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="bg-surface border border-line rounded-xl p-6 space-y-3">
                        <div class="w-9 h-9 rounded-lg bg-ochre-soft text-ochre flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-display font-bold text-ink">Digital Capacity</h3>
                        <p class="text-xs text-ink-muted leading-relaxed">
                            Web development bootcamps under the Innotech Digital Academy — HTML/CSS to Laravel,
                            with a graded curriculum and real placements, not certificates for attendance.
                        </p>
                    </div>
                    <div class="bg-surface border border-line rounded-xl p-6 space-y-3">
                        <div class="w-9 h-9 rounded-lg bg-teal-soft text-teal flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5L14 5h4a2 2 0 012 2v6m-6 8h6a2 2 0 002-2v-2a2 2 0 00-2-2h-2.5"/></svg>
                        </div>
                        <h3 class="text-sm font-display font-bold text-ink">Community Infrastructure</h3>
                        <p class="text-xs text-ink-muted leading-relaxed">
                            Field-monitored projects — from skills centres to water access — logged against a
                            budget, an expenditure line, and a set of KPIs, not a press release.
                        </p>
                    </div>
                    <div class="bg-surface border border-line rounded-xl p-6 space-y-3">
                        <div class="w-9 h-9 rounded-lg bg-ochre-soft text-ochre flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-display font-bold text-ink">Regional Leadership</h3>
                        <p class="text-xs text-ink-muted leading-relaxed">
                            Zonal, state and LGA coordinators own their patch — with dashboards scoped to their
                            exact jurisdiction, so accountability doesn't get lost above the LGA level.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COMMAND STRUCTURE -->
    <section id="command" class="py-24 border-t border-line bg-surface/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mb-14">
                <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-teal">How We're Organized</span>
                <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">The Regional Command</h2>
                <p class="text-sm text-ink-muted leading-relaxed mt-4">
                    Every user in ICDMS sits at a level of this chain — and every dashboard is scoped to exactly
                    what that level is accountable for. Nobody sees more territory than their post covers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-0 relative">
                <div class="hidden md:block absolute top-8 left-[12.5%] right-[12.5%] h-px bg-line"></div>

                @php
                    $commandLevels = [
                        ['label' => 'Zonal Command', 'count' => $zonesCount, 'unit' => 'Zone', 'desc' => 'Geopolitical zone oversight'],
                        ['label' => 'State Command', 'count' => $statesCount, 'unit' => 'State', 'desc' => 'State-level coordination'],
                        ['label' => 'LGA Command', 'count' => $lgasCount, 'unit' => 'LGA', 'desc' => 'Local government execution'],
                        ['label' => 'Community', 'count' => $totalCommunitiesCount, 'unit' => 'Community', 'desc' => 'Where the field work happens'],
                    ];
                @endphp

                @foreach($commandLevels as $i => $level)
                    <div class="relative px-4 {{ $i > 0 ? 'md:border-l md:border-line' : '' }} text-center md:text-left">
                        <div class="w-16 h-16 rounded-full bg-canvas border-2 border-ochre flex flex-col items-center justify-center mx-auto md:mx-0 relative z-10">
                            <span class="font-display font-bold text-lg text-ink leading-none">{{ $level['count'] }}</span>
                        </div>
                        <h3 class="text-sm font-display font-bold text-ink mt-4">{{ $level['label'] }}</h3>
                        <p class="text-xs text-ink-muted mt-1">{{ $level['desc'] }}</p>
                        <span class="inline-block mt-2 text-[10px] font-mono uppercase tracking-widest text-ink-muted/70">
                            {{ $level['count'] }} {{ $level['unit'] }}{{ $level['count'] === 1 ? '' : 's' }} on record
                        </span>
                    </div>
                @endforeach
            </div>

            @if($zonesCount === 0)
                <p class="text-[11px] font-mono text-ink-muted/60 mt-10 uppercase tracking-wide">
                    Regional Command is standing by — zones are provisioned from the admin console as coordinators come on board.
                </p>
            @endif
        </div>
    </section>

    <!-- FEATURED PROGRAMS -->
    <section id="programs" class="py-24 border-t border-line">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                <div>
                    <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Active Programs</span>
                    <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">What's currently running</h2>
                </div>
                @guest
                    <a href="{{ route('public.register') }}" class="text-xs font-bold text-teal hover:text-teal/80">
                        Apply to a program &rarr;
                    </a>
                @endguest
            </div>

            @if($featuredPrograms->isEmpty())
                <div class="border border-line border-dashed rounded-xl p-12 text-center">
                    <p class="text-sm text-ink-muted font-mono">No programs published yet — check back soon.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featuredPrograms as $program)
                        <div class="bg-surface border border-line rounded-xl overflow-hidden hover:border-ochre-dim transition group">
                            <div class="px-6 py-4 border-b border-line flex items-center justify-between">
                                <span class="text-[10px] font-mono font-semibold text-ink-muted uppercase tracking-widest">{{ $program->program_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase
                                    {{ $program->status === 'active' ? 'bg-teal-soft text-teal border border-teal-dim' : 'bg-ochre-soft text-ochre border border-ochre-dim' }}">
                                    {{ $program->status }}
                                </span>
                            </div>
                            <div class="px-6 py-5 space-y-3">
                                <h3 class="text-base font-display font-bold text-ink group-hover:text-ochre transition">{{ $program->title }}</h3>
                                <p class="text-xs text-ink-muted leading-relaxed line-clamp-3">{{ $program->description }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-line text-[11px] font-mono text-ink-muted">
                                    <span>{{ $program->projects_count }} project{{ $program->projects_count === 1 ? '' : 's' }}</span>
                                    <span>Since {{ $program->start_date?->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- IMPACT METRICS -->
    <section id="impact" class="py-16 border-y border-line bg-surface/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-canvas border border-line rounded-xl py-6 px-3">
                    <span class="text-3xl md:text-4xl font-display font-bold text-ink block">{{ number_format($totalBeneficiariesCount) }}</span>
                    <span class="text-[11px] text-ochre font-mono font-semibold uppercase tracking-wider mt-2 block">Beneficiaries Enrolled</span>
                </div>
                <div class="bg-canvas border border-line rounded-xl py-6 px-3">
                    <span class="text-3xl md:text-4xl font-display font-bold text-ink block">{{ $activeProjectsCount }}</span>
                    <span class="text-[11px] text-teal font-mono font-semibold uppercase tracking-wider mt-2 block">Projects Active Now</span>
                </div>
                <div class="bg-canvas border border-line rounded-xl py-6 px-3">
                    <span class="text-3xl md:text-4xl font-display font-bold text-ink block">{{ $totalProjectsCount }}</span>
                    <span class="text-[11px] text-ochre font-mono font-semibold uppercase tracking-wider mt-2 block">Total Project Files</span>
                </div>
                <div class="bg-canvas border border-line rounded-xl py-6 px-3">
                    <span class="text-3xl md:text-4xl font-display font-bold text-ink block">{{ $statesCount }}</span>
                    <span class="text-[11px] text-teal font-mono font-semibold uppercase tracking-wider mt-2 block">State Jurisdictions</span>
                </div>
            </div>
        </div>
    </section>

    <!-- RECENT FIELD ACTIVITY LOG -->
    @if($recentProjects->isNotEmpty())
        <section class="py-24 border-b border-line">
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-10">
                    <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Field Log</span>
                    <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">Latest project entries</h2>
                </div>

                <div class="bg-surface border border-line rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-canvas text-ink-muted border-b border-line uppercase font-mono font-semibold tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">File Ref</th>
                                    <th class="px-6 py-3">Project</th>
                                    <th class="px-6 py-3">Community</th>
                                    <th class="px-6 py-3">Logged</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach($recentProjects as $project)
                                    <tr class="hover:bg-canvas/40 transition">
                                        <td class="px-6 py-4 font-mono text-ink-muted">{{ $project->project_code }}</td>
                                        <td class="px-6 py-4 font-semibold text-ink">{{ $project->title }}</td>
                                        <td class="px-6 py-4 text-ink-muted">{{ $project->community->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 font-mono text-ink-muted">{{ $project->created_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-teal-soft text-teal border border-teal-dim">
                                                {{ str_replace('_', ' ', $project->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- LATEST FROM THE BLOG -->
    @if($latestPosts->isNotEmpty())
        <section class="py-24 border-b border-line bg-surface/40">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                    <div>
                        <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-ochre">Blog</span>
                        <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">News &amp; stories from the field</h2>
                    </div>
                    <a href="{{ route('public.blog') }}" class="text-xs font-bold text-teal hover:text-teal/80">
                        View all posts &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($latestPosts as $post)
                        <a href="{{ route('public.blog.show', $post->slug) }}" class="group bg-surface border border-line rounded-xl overflow-hidden hover:border-ochre-dim transition flex flex-col">
                            <div class="aspect-video bg-canvas overflow-hidden">
                                @if($post->featured_image_url)
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
                                <h3 class="text-base font-display font-bold text-ink tracking-tight mt-2 leading-snug group-hover:text-ochre transition">
                                    {{ $post->title }}
                                </h3>
                                @if($post->excerpt)
                                    <p class="text-xs text-ink-muted leading-relaxed mt-2 flex-1">{{ $post->excerpt }}</p>
                                @endif
                                <span class="text-xs font-bold text-teal mt-4">Read more &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- LATEST GALLERY -->
    @if($latestGalleries->isNotEmpty())
        <section class="py-24 border-b border-line">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                    <div>
                        <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-teal">Gallery</span>
                        <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">Moments from the work</h2>
                    </div>
                    <a href="{{ route('public.gallery') }}" class="text-xs font-bold text-teal hover:text-teal/80">View full gallery &rarr;</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($latestGalleries as $gallery)
                        <a href="{{ route('public.gallery.show', $gallery->slug) }}" class="group bg-surface border border-line rounded-xl overflow-hidden hover:border-teal-dim transition">
                            <div class="aspect-[4/3] bg-canvas grid grid-cols-2 gap-0.5 overflow-hidden">
                                @foreach($gallery->images->take(4) as $image)
                                    <img src="{{ $image->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $image->caption ?: $gallery->title }}">
                                @endforeach
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-mono font-semibold uppercase tracking-widest text-teal">{{ $gallery->category }}</span><span class="text-[10px] text-ink-muted font-mono">{{ $gallery->images->count() }} photos</span></div>
                                <h3 class="text-base font-display font-bold text-ink mt-2 group-hover:text-teal transition">{{ $gallery->title }}</h3>
                                @if($gallery->description)<p class="text-xs text-ink-muted leading-relaxed mt-2 line-clamp-2">{{ $gallery->description }}</p>@endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ROLES / JOIN -->
    <section id="roles" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-xl mx-auto mb-14">
                <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-teal">Join The Ecosystem</span>
                <h2 class="text-3xl font-display font-bold text-ink tracking-tight mt-3">Pick a post, not just a role</h2>
                <p class="text-sm text-ink-muted mt-4">Every application goes to an administrator for verification before it's activated — no exceptions.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-surface border border-line rounded-xl p-6 space-y-4 hover:border-ochre-dim transition">
                    <div class="w-10 h-10 rounded-lg bg-ochre-soft text-ochre flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-base font-display font-bold text-ink">Volunteers</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Field visits, monitoring, teaching sessions, and direct community interventions — logged after every outing.</p>
                    <a href="{{ route('public.register', ['role' => 'volunteer']) }}" class="inline-block text-xs font-bold text-ochre hover:text-ochre/80">Apply as Volunteer &rarr;</a>
                </div>

                <div class="bg-surface border border-line rounded-xl p-6 space-y-4 hover:border-teal-dim transition">
                    <div class="w-10 h-10 rounded-lg bg-teal-soft text-teal flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <h3 class="text-base font-display font-bold text-ink">Coordinators</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Lead a zonal, state, or LGA command — verify volunteer reports and own the numbers for your jurisdiction.</p>
                    <a href="{{ route('public.register', ['role' => 'coordinator']) }}" class="inline-block text-xs font-bold text-teal hover:text-teal/80">Apply as Coordinator &rarr;</a>
                </div>

                <div class="bg-surface border border-line rounded-xl p-6 space-y-4 hover:border-ochre-dim transition">
                    <div class="w-10 h-10 rounded-lg bg-ochre-soft text-ochre flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-display font-bold text-ink">Partners &amp; Donors</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Fund a specific project file directly, or bring technical expertise and corporate sponsorship.</p>
                    <a href="{{ route('public.donate') }}" class="inline-block text-xs font-bold text-ochre hover:text-ochre/80">Make a Donation &rarr;</a>
                </div>

                <div class="bg-surface border border-line rounded-xl p-6 space-y-4 hover:border-teal-dim transition">
                    <div class="w-10 h-10 rounded-lg bg-teal-soft text-teal flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <h3 class="text-base font-display font-bold text-ink">Students &amp; Trainees</h3>
                    <p class="text-xs text-ink-muted leading-relaxed">Enroll in Innotech Digital Academy bootcamps — HTML/CSS through to Laravel, with real capstone placements.</p>
                    <a href="{{ route('public.register', ['role' => 'student']) }}" class="inline-block text-xs font-bold text-teal hover:text-teal/80">Enroll as Student &rarr;</a>
                </div>

            </div>
        </div>
    </section>

    <!-- DONOR TRANSPARENCY STRIP -->
    <section class="py-16 border-t border-line bg-surface/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-canvas border border-line rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <span class="text-[11px] font-mono font-semibold uppercase tracking-widest text-teal">Verified, Not Claimed</span>
                    <h3 class="text-2xl font-display font-bold text-ink mt-2">
                        &#8358;{{ number_format($verifiedDonationsTotal, 0) }} confirmed by Paystack
                    </h3>
                    <p class="text-xs text-ink-muted mt-2 max-w-md">
                        This figure only counts donations independently verified against the payment
                        processor's own record — not what a donor's browser reported.
                        @if($verifiedDonorsCount > 0)
                            From {{ $verifiedDonorsCount }} verified donor{{ $verifiedDonorsCount === 1 ? '' : 's' }} so far.
                        @endif
                    </p>
                </div>
                <a href="{{ route('public.donate') }}" class="shrink-0 px-8 py-3.5 bg-teal hover:bg-teal/90 text-canvas font-bold rounded-lg text-sm shadow-lg shadow-teal/10 transition text-center">
                    Add to the Total
                </a>
            </div>
        </div>
    </section>

    <x-public.site-footer />
</div>
