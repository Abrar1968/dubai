<x-admin.layouts.app title="Dashboard">
    <div class="space-y-8">
        <!-- Welcome Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl shadow-2xl">
            {{-- Decorative Elements --}}
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-600/10 rounded-full blur-3xl"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-50"></div>
            </div>

            <div class="relative px-6 py-10 sm:px-10 sm:py-12">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-amber-400 text-sm font-semibold tracking-wider uppercase mb-2">
                            {{ now()->format('l, F j, Y') }}
                        </p>
                        <h1 class="text-3xl sm:text-4xl font-bold text-white">
                            Welcome back, <span class="bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">{{ $user->name }}</span>!
                        </h1>
                        <p class="mt-3 text-slate-400 text-lg max-w-xl">
                            Here's what's happening in your admin panel today.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-medium text-white">{{ $user->role->label() }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($stats))
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Packages -->
            @if(isset($stats['packages']))
            <x-admin.ui.stats-card
                title="Total Packages"
                :value="$stats['packages']['total']"
                :subtitle="$stats['packages']['active'] . ' active packages'"
                icon="package"
                color="amber"
                :trend="$stats['packages']['active'] > 0 ? 'up' : null"
                :trendValue="$stats['packages']['active'] > 0 ? round(($stats['packages']['active'] / max($stats['packages']['total'], 1)) * 100) . '%' : null"
            />
            @endif

            <!-- Bookings -->
            @if(isset($stats['bookings']))
            <x-admin.ui.stats-card
                title="Total Bookings"
                :value="$stats['bookings']['total']"
                :subtitle="$stats['bookings']['pending'] . ' pending review'"
                icon="booking"
                color="blue"
                :trend="$stats['bookings']['pending'] > 0 ? 'up' : null"
                :trendValue="$stats['bookings']['pending'] > 0 ? $stats['bookings']['pending'] . ' new' : null"
            />
            @endif

            <!-- Articles -->
            @if(isset($stats['articles']))
            <x-admin.ui.stats-card
                title="Published Articles"
                :value="$stats['articles']['published']"
                :subtitle="number_format($stats['articles']['total_views']) . ' total views'"
                icon="article"
                color="green"
                trend="up"
                :trendValue="number_format($stats['articles']['total_views'])"
            />
            @endif

            <!-- Inquiries -->
            @if(isset($stats['inquiries']))
            <x-admin.ui.stats-card
                title="New Inquiries"
                :value="$stats['inquiries']['new']"
                :subtitle="$stats['inquiries']['total'] . ' total inquiries'"
                icon="inquiry"
                color="purple"
                :trend="$stats['inquiries']['new'] > 0 ? 'up' : null"
                :trendValue="$stats['inquiries']['new'] > 0 ? 'Action needed' : null"
            />
            @endif
        </div>

        <!-- Revenue Card -->
        @if(isset($stats['bookings']['total_revenue']))
        <div class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-amber-500 to-orange-500 rounded-3xl shadow-2xl shadow-amber-500/20">
            {{-- Decorative --}}
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.1%22%20fill-rule%3D%22evenodd%22%3E%3Ccircle%20cx%3D%223%22%20cy%3D%223%22%20r%3D%223%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E')]"></div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

            <div class="relative px-6 py-8 sm:px-10 sm:py-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-white/80 font-semibold uppercase tracking-wider text-sm">Total Revenue</p>
                        </div>
                        <p class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
                            AED {{ number_format($stats['bookings']['total_revenue'], 2) }}
                        </p>
                        <p class="mt-3 text-white/70 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            From {{ $stats['bookings']['confirmed'] }} confirmed bookings
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-32 h-32 rounded-full border-8 border-white/20 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/40" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Bookings -->
            <x-admin.ui.card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-xl">
                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Recent Bookings</h3>
                        </div>
                        @if($recentBookings->count() > 0)
                            <a href="{{ route('admin.hajj.bookings.index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View all
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </x-slot>

                @if($recentBookings->count() > 0)
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach($recentBookings as $booking)
                    <li class="px-6 py-4 hover:bg-slate-50/50 transition-colors duration-200 group">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-600">{{ substr($booking->booking_number, -2) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-amber-600 transition-colors">
                                        {{ $booking->booking_number }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">
                                        {{ $booking->user?->name ?? $booking->contact_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <x-admin.ui.badge
                                    :variant="match($booking->status->value) {
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    }"
                                    size="sm"
                                >
                                    {{ $booking->status->label() }}
                                </x-admin.ui.badge>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <x-admin.data.empty-state
                    title="No bookings yet"
                    description="Bookings will appear here once customers start making reservations."
                    :compact="true"
                />
                @endif
            </x-admin.ui.card>

            <!-- Recent Inquiries -->
            <x-admin.ui.card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-xl">
                                <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Recent Inquiries</h3>
                        </div>
                        @if($recentInquiries->count() > 0)
                            <a href="{{ route('admin.hajj.inquiries.index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View all
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </x-slot>

                @if($recentInquiries->count() > 0)
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach($recentInquiries as $inquiry)
                    <li class="px-6 py-4 hover:bg-slate-50/50 transition-colors duration-200 group">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                                    <span class="text-sm font-bold text-purple-600 uppercase">{{ substr($inquiry->name, 0, 2) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-amber-600 transition-colors">
                                        {{ $inquiry->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">
                                        {{ $inquiry->subject }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <x-admin.ui.badge
                                    :variant="match($inquiry->status->value) {
                                        'new' => 'info',
                                        'read' => 'warning',
                                        'responded' => 'success',
                                        'closed' => 'secondary',
                                        default => 'secondary'
                                    }"
                                    size="sm"
                                    :dot="$inquiry->status->value === 'new'"
                                >
                                    {{ $inquiry->status->label() }}
                                </x-admin.ui.badge>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <x-admin.data.empty-state
                    title="No inquiries yet"
                    description="Customer inquiries will appear here when they contact you."
                    :compact="true"
                />
                @endif
            </x-admin.ui.card>
        </div>
    </div>
</x-admin.layouts.app>
