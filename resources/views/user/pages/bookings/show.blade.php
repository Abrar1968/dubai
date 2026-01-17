<x-admin.layouts.user title="Booking Details">
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <a href="{{ route('user.bookings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Bookings
            </a>
            <div class="mt-2 flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Booking #{{ $booking->booking_number }}</h1>
                @switch($booking->status->value ?? $booking->status)
                    @case('pending')
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800">Pending</span>
                        @break
                    @case('confirmed')
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">Confirmed</span>
                        @break
                    @case('completed')
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">Completed</span>
                        @break
                    @case('cancelled')
                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">Cancelled</span>
                        @break
                @endswitch
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Package Info -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Package Details</h2>
                    </div>
                    <div class="p-6">
                        @if($booking->package)
                            <div class="flex gap-4">
                                @if($booking->package->featured_image)
                                    <img src="{{ Storage::url($booking->package->featured_image) }}" alt="{{ $booking->package->title }}" class="h-24 w-32 rounded-lg object-cover">
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $booking->package->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $booking->package->duration_days }} days package</p>
                                    @if($booking->package->package_type)
                                        <span class="mt-2 inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">
                                            {{ ucfirst($booking->package->package_type->value ?? $booking->package->package_type) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">Package information not available.</p>
                        @endif
                    </div>
                </div>

                <!-- Travelers -->
                @if($booking->travelers && $booking->travelers->count() > 0)
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Travelers</h2>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            @foreach($booking->travelers as $traveler)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $traveler->full_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $traveler->passport_number ?? 'Passport pending' }}
                                                @if($traveler->date_of_birth)
                                                    • DOB: {{ $traveler->date_of_birth->format('M j, Y') }}
                                                @endif
                                            </p>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ ucfirst($traveler->traveler_type ?? 'adult') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Status History -->
                @if($booking->statusLogs && $booking->statusLogs->count() > 0)
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Status History</h2>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($booking->statusLogs as $log)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 ring-8 ring-white">
                                                            <svg class="h-4 w-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                Status changed to <span class="font-medium">{{ ucfirst($log->new_status) }}</span>
                                                            </p>
                                                            @if($log->notes)
                                                                <p class="mt-1 text-sm text-gray-500">{{ $log->notes }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                            {{ $log->created_at->format('M j, Y g:i A') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Booking Summary -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Booking Summary</h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Booking Number</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Booked On</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('M j, Y') }}</dd>
                            </div>
                            @if($booking->travel_date)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Travel Date</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $booking->travel_date->format('M j, Y') }}</dd>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Travelers</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $booking->number_of_travelers ?? 1 }}</dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <dt class="text-base font-medium text-gray-900">Total Amount</dt>
                                    <dd class="text-base font-semibold text-amber-600">AED {{ number_format($booking->total_amount, 2) }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Need Help?</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-500">
                            If you have any questions about your booking, please contact our support team.
                        </p>
                        <div class="mt-4 space-y-2">
                            <a href="mailto:support@dubaihaij.com" class="flex items-center text-sm text-amber-600 hover:text-amber-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                support@dubaihajj.com
                            </a>
                            <a href="tel:+971501234567" class="flex items-center text-sm text-amber-600 hover:text-amber-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                +971 50 123 4567
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.user>
