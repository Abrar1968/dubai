@props([])

@php
    $user = auth()->user();
    $sections = $user->getSectionNames();
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<!-- Mobile sidebar -->
<div 
    x-show="sidebarOpen"
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 lg:hidden"
>
    <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-slate-700">
        <span class="text-xl font-bold text-amber-500">Dubai Travel</span>
        <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @include('admin.components.layout.sidebar-content')
</div>

<!-- Desktop sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-800 px-6 pb-4">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center border-b border-slate-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="text-xl font-bold text-amber-500">Dubai Travel</span>
                <span class="text-xs font-medium text-slate-400">Admin</span>
            </a>
        </div>

        @include('admin.components.layout.sidebar-content')
    </div>
</div>
