@props(['title' => 'Dashboard'])

@php
    $user = auth()->user();
@endphp

<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu button -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Page title -->
        <div class="flex items-center">
            <h1 class="text-lg font-semibold leading-7 text-gray-900">{{ $title }}</h1>
        </div>

        <div class="flex flex-1 items-center justify-end gap-x-4 lg:gap-x-6">
            <!-- Quick actions / notifications would go here -->

            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    class="-m-1.5 flex items-center p-1.5"
                    @click="open = !open"
                    @click.away="open = false"
                >
                    <span class="sr-only">Open user menu</span>
                    @if($user->avatar)
                        <img class="h-8 w-8 rounded-full bg-gray-50" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                    @else
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-500">
                            <span class="text-sm font-medium leading-none text-white">{{ $user->initials }}</span>
                        </span>
                    @endif
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900">{{ $user->name }}</span>
                        <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Dropdown menu -->
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm text-gray-500">Signed in as</p>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->email }}</p>
                        <p class="text-xs text-amber-600 mt-1">{{ $user->role->label() }}</p>
                    </div>

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Your Profile
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
