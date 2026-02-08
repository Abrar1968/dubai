@props([])

@php
    $user = auth()->user();
    $sections = $user->getSectionNames();
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<!-- Mobile sidebar -->
<div
    x-show="sidebarOpen"
    x-cloak
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 w-72 lg:hidden"
>
    <div class="flex h-full flex-col bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
        <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-slate-700/50">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-500/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-white">Dubai Travel</span>
                    <span class="ml-2 inline-flex items-center rounded-full bg-amber-500/20 px-2 py-0.5 text-[10px] font-semibold text-amber-400">Admin</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="rounded-lg p-2 text-slate-400 hover:bg-slate-700/50 hover:text-white transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-4 py-4">
            @include('admin.components.layout.sidebar-content')
        </div>
    </div>
</div>

<!-- Desktop sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col transition-all duration-300">
    <div class="flex grow flex-col bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center px-6 border-b border-slate-700/50">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-500/20 group-hover:shadow-amber-500/40 transition-shadow">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-white">Dubai Travel</span>
                    <span class="ml-2 inline-flex items-center rounded-full bg-amber-500/20 px-2 py-0.5 text-[10px] font-semibold text-amber-400">Admin</span>
                </div>
            </a>
        </div>

        <div 
            class="flex-1 overflow-y-auto px-4 py-4"
            x-ref="sidebarScroll"
            x-init="
                $nextTick(() => {
                    const savedPos = localStorage.getItem('admin_sidebar_scroll');
                    if (savedPos) $refs.sidebarScroll.scrollTop = parseInt(savedPos);
                });
            "
            @scroll.debounce.100ms="localStorage.setItem('admin_sidebar_scroll', $refs.sidebarScroll.scrollTop)"
        >
            @include('admin.components.layout.sidebar-content')
        </div>

        <!-- User info at bottom -->
        <div class="shrink-0 border-t border-slate-700/50 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-slate-800/50 p-3">
                @if($user->avatar)
                    <img class="h-10 w-10 rounded-full ring-2 ring-amber-500/50" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 ring-2 ring-amber-500/50">
                        <span class="text-sm font-semibold text-white">{{ $user->initials }}</span>
                    </span>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $user->role->label() }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg p-2 text-slate-400 hover:bg-slate-700/50 hover:text-white transition-colors" title="Sign out">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
