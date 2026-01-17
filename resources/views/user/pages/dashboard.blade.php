<x-admin.layouts.user title="Dashboard">
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="overflow-hidden rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 p-6 text-white shadow-lg">
            <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="mt-1 text-amber-100">Track your bookings and manage your profile.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-blue-100 p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-yellow-100 p-3">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Confirmed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-lg bg-purple-100 p-3">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                    <a href="{{ route('user.bookings.index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-500">
                        View all →
                    </a>
                </div>
            </div>

            @if(isset($recentBookings) && $recentBookings->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->package?->title ?? 'Package' }}</p>
                                    <p class="text-sm text-gray-500">
                                        Booking #{{ $booking->booking_number }} • {{ $booking->created_at->format('M j, Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @switch($booking->status->value ?? $booking->status)
                                        @case('pending')
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                            @break
                                        @case('confirmed')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Confirmed</span>
                                            @break
                                        @case('completed')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Completed</span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Cancelled</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">{{ ucfirst($booking->status->value ?? $booking->status) }}</span>
                                    @endswitch
                                    <a href="{{ route('user.bookings.show', $booking) }}" class="text-amber-600 hover:text-amber-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Explore our packages and book your journey.</p>
                    <div class="mt-4">
                        <a href="/" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                            Browse Packages
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.user>
