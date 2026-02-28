<x-admin.layouts.app title="Hajj & Umrah Dashboard">
    <div class="space-y-6">
        <!-- Welcome message -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Welcome to Hajj & Umrah Management
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Here's an overview of your Hajj & Umrah section. You are logged in as <span class="font-medium text-amber-600">{{ $user->role->label() }}</span>.
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

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <a href="{{ route('admin.hajj.packages.create') }}" class="group relative rounded-lg p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-amber-500">
                        <div class="text-center">
                            <span class="rounded-lg inline-flex p-3 bg-amber-50 text-amber-600 ring-4 ring-white group-hover:bg-amber-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 text-center">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600">
                                <span class="absolute inset-0"></span>
                                Add Package
                            </h3>
                        </div>
                    </a>

                    <a href="{{ route('admin.hajj.articles.create') }}" class="group relative rounded-lg p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500">
                        <div class="text-center">
                            <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-600 ring-4 ring-white group-hover:bg-blue-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 text-center">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600">
                                <span class="absolute inset-0"></span>
                                Write Article
                            </h3>
                        </div>
                    </a>

                    <a href="{{ route('admin.hajj.team.create') }}" class="group relative rounded-lg p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-green-500">
                        <div class="text-center">
                            <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-600 ring-4 ring-white group-hover:bg-green-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 009.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 text-center">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-green-600">
                                <span class="absolute inset-0"></span>
                                Add Team
                            </h3>
                        </div>
                    </a>

                    <a href="{{ route('admin.hajj.settings.index') }}" class="group relative rounded-lg p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-500">
                        <div class="text-center">
                            <span class="rounded-lg inline-flex p-3 bg-gray-50 text-gray-600 ring-4 ring-white group-hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-4 text-center">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-gray-600">
                                <span class="absolute inset-0"></span>
                                Settings
                            </h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>

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
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Hajj Inquiries</h3>
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
