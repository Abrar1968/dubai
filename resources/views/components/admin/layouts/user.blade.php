<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false"
         x-cloak>
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 transform bg-white shadow-lg transition-transform duration-300 ease-in-out lg:translate-x-0">
        <!-- Logo -->
        <div class="flex h-16 items-center justify-center border-b border-gray-200 px-4">
            <a href="/" class="flex items-center">
                <span class="text-xl font-bold text-amber-600">Dubai Hajj</span>
            </a>
        </div>

        <!-- User Info -->
        <div class="border-b border-gray-200 p-4">
            <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full bg-amber-100">
                    <span class="flex h-full w-full items-center justify-center text-sm font-medium text-amber-600">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 px-2 py-4">
            <a href="{{ route('user.dashboard') }}" 
               class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'bg-amber-100 text-amber-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('user.dashboard') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('user.bookings.index') }}" 
               class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('user.bookings.*') ? 'bg-amber-100 text-amber-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('user.bookings.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                My Bookings
            </a>

            <a href="{{ route('user.profile') }}" 
               class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('user.profile') ? 'bg-amber-100 text-amber-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('user.profile') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile
            </a>
        </nav>

        <!-- Logout -->
        <div class="border-t border-gray-200 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="lg:pl-64">
        <!-- Top bar -->
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 shadow-sm lg:px-8">
            <button type="button" @click="sidebarOpen = true" class="text-gray-500 lg:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex flex-1 items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>

                <a href="/" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Website
                </a>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-4 lg:p-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700" x-data="{ show: true }" x-show="show">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
