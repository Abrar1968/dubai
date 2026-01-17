<x-admin.layouts.user title="My Bookings">
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
            <p class="mt-1 text-sm text-gray-500">View and track all your bookings.</p>
        </div>

        <!-- Status Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('user.bookings.index') }}" 
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ !$currentStatus ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('user.bookings.index', ['status' => 'pending']) }}" 
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Pending
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('user.bookings.index', ['status' => 'confirmed']) }}" 
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Confirmed
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['confirmed'] }}</span>
            </a>
            <a href="{{ route('user.bookings.index', ['status' => 'completed']) }}" 
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Completed
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['completed'] }}</span>
            </a>
            <a href="{{ route('user.bookings.index', ['status' => 'cancelled']) }}" 
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Cancelled
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['cancelled'] }}</span>
            </a>
        </div>

        <!-- Bookings List -->
        @if($bookings->count() > 0)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <li class="p-4 hover:bg-gray-50 sm:p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="truncate font-medium text-gray-900">
                                            {{ $booking->package?->title ?? 'Package' }}
                                        </h3>
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
                                    </div>
                                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                        <span class="inline-flex items-center">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            {{ $booking->booking_number }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $booking->created_at->format('M j, Y') }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            {{ $booking->number_of_travelers ?? 1 }} travelers
                                        </span>
                                        <span class="inline-flex items-center font-medium text-gray-900">
                                            AED {{ number_format($booking->total_amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('user.bookings.show', $booking) }}" 
                                       class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="overflow-hidden rounded-xl bg-white px-6 py-12 text-center shadow-sm ring-1 ring-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($currentStatus)
                        No {{ $currentStatus }} bookings. Try a different filter.
                    @else
                        You haven't made any bookings yet.
                    @endif
                </p>
                <div class="mt-4">
                    <a href="/" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                        Browse Packages
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-admin.layouts.user>
