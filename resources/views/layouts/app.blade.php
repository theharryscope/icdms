<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-canvas text-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#14120e">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('pwa-icon.svg') }}">

    @php
        $siteSettings = \App\Models\SiteSetting::current();
    @endphp

    <title>{{ $siteSettings->site_name }} — Admin Portal</title>
    @if ($siteSettings->favicon_url)
        <link rel="icon" href="{{ $siteSettings->favicon_url }}">
    @endif

    <!-- Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-canvas font-sans antialiased text-ink">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }" @resize.window="if (window.innerWidth >= 768) sidebarOpen = false">

        <!-- Mobile Sidebar Backdrop -->
        <div
            x-show="sidebarOpen"
            x-cloak
            style="display: none;"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-canvas/80 md:hidden"
        ></div>

        <!-- ICDMS Sidebar Navigation -->
        <aside
            :class="sidebarOpen ? '!translate-x-0' : ''"
            class="fixed md:static inset-y-0 left-0 z-50 w-[min(18rem,85vw)] md:w-64 bg-surface border-r border-line flex flex-col shrink-0 -translate-x-full md:translate-x-0 transition-transform duration-200 ease-out"
        >
            <!-- Brand Header -->
            <div class="h-16 flex items-center px-6 border-b border-line justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-ochre flex items-center justify-center text-canvas font-display font-bold text-lg shadow-sm overflow-hidden shrink-0">
                        @if ($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" class="w-full h-full object-cover" alt="{{ $siteSettings->site_name }} logo">
                        @else
                            {{ substr($siteSettings->site_name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <span class="font-display font-bold text-ink tracking-tight text-base block leading-none">ICDMS</span>
                        <span class="text-[10px] text-ink-muted font-mono font-semibold uppercase tracking-wider">InnoTech Portal</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden p-1.5 text-ink-muted hover:text-ink" aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav @click="sidebarOpen = false" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-4 space-y-1 text-sm font-medium">

                <!-- Super Admin Central Dashboard -->
                @hasrole('Super Admin')
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 00-1 1m-6 0h6"/></svg>
                        <span>Central Dashboard</span>
                    </a>
                @endhasrole


                <!-- Coordinator Dashboard Link -->
                @hasanyrole('State Coordinator|Zonal Coordinator|LGA Coordinator|Coordinator')
                    <a href="{{ route('coordinator.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('coordinator.dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('coordinator.dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Coordinator Command</span>
                    </a>
                @endhasanyrole

                <!-- Volunteer Dashboard Link -->
                @hasrole('Volunteer')
                    <a href="{{ route('volunteer.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('volunteer.dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('volunteer.dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        <span>Volunteer Command</span>
                    </a>
                @endhasrole

                <!-- Employer Dashboard Link -->
                @hasrole('Partner / Employer')
                    <a href="{{ route('employer.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('employer.dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('employer.dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        <span>Employer Portal</span>
                    </a>
                @endhasrole

                @auth
                    <a href="{{ route('donor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('donor.dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('donor.dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>My Donations</span>
                    </a>
                    <a href="{{ route('reports.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('reports.create') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('reports.create') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Send Report</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('notifications.index') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                        <svg class="w-5 h-5 {{ request()->routeIs('notifications.index') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Notifications</span>
                    </a>
                @endauth

                @hasrole('Super Admin')
                    <a href="{{ route('admin.grants') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.grants') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.grants') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Grants &amp; Funding</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.reports') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Report Notifications</span>
                    </a>
                    <a href="{{ route('admin.notifications.create') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.notifications.create') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.notifications.create') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>Send Notifications</span>
                    </a>
                @endhasrole

                <!-- ADMINISTRATIVE & OPERATIONS MENUS (Guarded for Staff/Admins) -->
                @hasanyrole('Super Admin|State Coordinator|Zonal Coordinator|LGA Coordinator|Coordinator')

                    <!-- SECTION: ORGANIZATION & PEOPLE -->
                    <div class="pt-4 pb-1">
                        <p class="px-3 text-[10px] uppercase font-mono font-semibold text-ink-muted/70 tracking-wider">Organization &amp; People</p>
                    </div>

                    @hasrole('Super Admin')
                        <a href="{{ route('admin.regional-command') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.regional-command') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.regional-command') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 00-2-2h-1c-1 0-1.5-.5-1.5-1V5a2 2 0 00-2-2h-2.335"/></svg>
                            <span>Zones &amp; LGA Commands</span>
                        </a>

                        <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.users') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>User Management</span>
                        </a>

                        <a href="{{ route('admin.students') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.students') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.students') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            <span>Student Registrations</span>
                        </a>

                        <a href="{{ route('admin.site-settings') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.site-settings') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.site-settings') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Site Settings</span>
                        </a>

                        <a href="{{ route('admin.pages') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.pages') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.pages') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Pages</span>
                        </a>

                        <a href="{{ route('admin.blog') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.blog') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.blog') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"/></svg>
                            <span>Blog</span>
                        </a>
                    @endhasrole

                    <a href="{{ route('beneficiaries.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('beneficiaries.*') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('beneficiaries.*') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Beneficiaries</span>
                    </a>

                    <!-- Student Portal Link -->
                    @hasrole('Student')
                        <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('student.dashboard') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition">
                            <svg class="w-5 h-5 {{ request()->routeIs('student.dashboard') ? 'text-ochre' : 'text-ink-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            <span>Student Portal</span>
                        </a>
                    @endhasrole

                    <!-- SECTION: OPERATIONS & FIELD -->
                    <div class="pt-4 pb-1">
                        <p class="px-3 text-[10px] uppercase font-mono font-semibold text-ink-muted/70 tracking-wider">Operations &amp; Field</p>
                    </div>

                    <a href="{{ route('programs.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('programs.*') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('programs.*') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Programs</span>
                    </a>

                    <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('projects.*') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('projects.*') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <span>Projects</span>
                    </a>

                    <a href="{{ route('communities.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('communities.*') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('communities.*') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span>Communities</span>
                    </a>

                    <!-- SECTION: MONITORING & EVALUATION -->
                    <div class="pt-4 pb-1">
                        <p class="px-3 text-[10px] uppercase font-mono font-semibold text-ink-muted/70 tracking-wider">M&amp;E &amp; Reporting</p>
                    </div>

                    <a href="{{ route('me.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('me.*') ? 'text-ochre bg-ochre-soft border border-ochre-dim font-semibold' : 'text-ink-muted hover:bg-surface-raised' }} transition group">
                        <svg class="w-5 h-5 {{ request()->routeIs('me.*') ? 'text-ochre' : 'text-ink-muted group-hover:text-ochre' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                        <span>M&amp;E Dashboard</span>
                    </a>

                    <a href="{{ route('me.field-visits.create') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-ink-muted hover:bg-surface-raised transition group">
                        <svg class="w-5 h-5 text-ink-muted group-hover:text-ochre transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Field Monitoring Reports</span>
                    </a>

                @endhasanyrole

            </nav>

            <!-- Current Authenticated User & Profile Settings Link -->
            <div class="p-4 border-t border-line bg-surface/60">
                <div class="flex items-center justify-between">
                    <a href="{{ route('user.profile') }}" class="flex items-center space-x-3 overflow-hidden group">
                        <div class="w-9 h-9 rounded-full bg-surface-raised border border-line flex items-center justify-center font-mono font-bold text-ink-muted shrink-0 group-hover:border-ochre transition">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-xs font-semibold text-ink truncate group-hover:text-ochre transition">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-ink-muted truncate">Profile Settings &rarr;</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 text-ink-muted hover:text-red-400 hover:bg-surface-raised rounded-lg transition" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="relative z-0 flex-1 flex flex-col overflow-hidden min-w-0">
            <!-- Top Navbar Header -->
            <header class="relative z-10 h-16 shrink-0 bg-surface border-b border-line flex items-center justify-between px-4 md:px-8">
                <div class="flex items-center space-x-3 min-w-0">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-ink-muted hover:text-ink transition shrink-0" aria-label="Open menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-sm sm:text-base md:text-lg font-display font-bold text-ink truncate">ICDMS Central Command Center</h1>
                </div>

                <div class="flex items-center space-x-4 shrink-0">
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-teal-soft text-teal border border-teal-dim">
                        System Active (v1.0)
                    </span>

                    <a href="{{ route('landing') }}" target="_blank" class="hidden sm:flex text-xs text-ink-muted hover:text-ink transition items-center space-x-1">
                        <span>View Public Site</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </header>

            <!-- Dynamic View Body -->
            <main class="dashboard-main flex-1 min-w-0 overflow-y-auto overflow-x-hidden p-3 sm:p-5 md:p-8 bg-canvas">
                @php
                    $whatsappGroupLinks = $siteSettings->whatsapp_group_links ?? [];
                    $whatsappGroupLink = null;
                    foreach (auth()->user()?->getRoleNames() ?? [] as $roleName) {
                        if (!empty($whatsappGroupLinks[$roleName])) {
                            $whatsappGroupLink = $whatsappGroupLinks[$roleName];
                            break;
                        }
                    }
                @endphp
                @if(!empty($whatsappGroupLink ?? null) && !request()->routeIs('admin.site-settings'))
                    <div class="mb-6 flex flex-col gap-3 rounded-xl border border-teal-dim bg-teal-soft p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-teal" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.14 6.44 2.14 11.9c0 1.75.46 3.46 1.33 4.96L2 22l5.28-1.39a9.88 9.88 0 004.76 1.22h.01c5.46 0 9.9-4.44 9.9-9.9C21.95 6.44 17.5 2 12.04 2zm5.78 14.25c-.24.67-1.4 1.28-1.93 1.35-.5.07-1.13.1-1.83-.11-.42-.13-.96-.3-1.66-.59-2.92-1.26-4.82-4.2-4.97-4.4-.15-.2-1.19-1.58-1.19-3.01 0-1.43.75-2.14 1.02-2.43.27-.29.59-.36.78-.36h.56c.18 0 .42-.07.66.5.24.57.82 2 .89 2.17.07.17.12.38.02.61-.1.24-.15.39-.3.6-.15.2-.31.45-.44.61-.15.17-.3.35-.13.69.17.34.76 1.25 1.63 2.02 1.12.99 2.06 1.3 2.4 1.47.34.17.54.14.74-.08.2-.22.85-.99 1.08-1.33.22-.34.45-.28.76-.17.31.11 1.96.93 2.3 1.1.34.17.56.25.64.39.08.14.08.81-.16 1.48z"/></svg>
                            <div>
                                <p class="text-sm font-bold text-ink">Join your {{ auth()->user()->getRoleNames()->first() }} WhatsApp group</p>
                                <p class="text-xs text-ink-muted">Connect with others in your role and receive team updates.</p>
                            </div>
                        </div>
                        <a href="{{ $whatsappGroupLink ?? '' }}" target="_blank" rel="noopener" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-teal px-4 py-2 text-xs font-bold text-canvas transition hover:bg-teal/90">Join Group</a>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
