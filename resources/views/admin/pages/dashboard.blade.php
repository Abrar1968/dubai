<x-admin.layouts.app title="Dashboard">
    <div class="space-y-6">
        <!-- Welcome message -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Welcome back, {{ $user->name }}!
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Here's an overview of your admin panel. You are logged in as <span class="font-medium text-amber-600">{{ $user->role->label() }}</span>.
                </p>
            </div>
        </div>

        @if(!empty($stats))
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Packages -->
            @if(isset($stats['packages']))
            <x-admin.ui.stats-card
                title="Total Packages"
                :value="$stats['packages']['total']"
                :subtitle="$stats['packages']['active'] . ' active'"
                icon="package"
                color="amber"
            />
            @endif

            <!-- Bookings -->
            @if(isset($stats['bookings']))
            <x-admin.ui.stats-card
                title="Total Bookings"
                :value="$stats['bookings']['total']"
                :subtitle="$stats['bookings']['pending'] . ' pending'"
                icon="booking"
                color="blue"
            />
            @endif

            <!-- Articles -->
            @if(isset($stats['articles']))
            <x-admin.ui.stats-card
                title="Published Articles"
                :value="$stats['articles']['published']"
                :subtitle="number_format($stats['articles']['total_views']) . ' views'"
                icon="article"
                color="green"
            />
            @endif

            <!-- Inquiries -->
            @if(isset($stats['inquiries']))
            <x-admin.ui.stats-card
                title="New Inquiries"
                :value="$stats['inquiries']['new']"
                :subtitle="$stats['inquiries']['total'] . ' total'"
                icon="inquiry"
                color="purple"
            />
            @endif
        </div>

        <!-- Revenue Card -->
        @if(isset($stats['bookings']['total_revenue']))
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-amber-100 truncate">Total Revenue</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-white">
                    AED {{ number_format($stats['bookings']['total_revenue'], 2) }}
                </dd>
                <p class="mt-2 text-sm text-amber-100">
                    From {{ $stats['bookings']['confirmed'] }} confirmed bookings
                </p>
            </div>
        </div>
        @endif
        @endif

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Bookings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Bookings</h3>
                </div>
                <div class="overflow-hidden">
                    @if($recentBookings->count() > 0)
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($recentBookings as $booking)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $booking->booking_number }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $booking->user?->name ?? $booking->contact_name }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                        {{ $booking->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status->value === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $booking->status->value === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status->value === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ $booking->status->label() }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No bookings yet</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Inquiries -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Inquiries</h3>
                </div>
                <div class="overflow-hidden">
                    @if($recentInquiries->count() > 0)
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($recentInquiries as $inquiry)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $inquiry->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $inquiry->subject }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                        {{ $inquiry->status->value === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $inquiry->status->value === 'read' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $inquiry->status->value === 'responded' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $inquiry->status->value === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ $inquiry->status->label() }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No inquiries yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
