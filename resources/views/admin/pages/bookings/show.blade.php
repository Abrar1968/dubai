@extends('admin.layouts.app')

@section('title', 'Booking ' . $booking->booking_number)

@section('header')
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.hajj.bookings.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-mono">{{ $booking->booking_number }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-admin.ui.badge :type="match($booking->status) {
                        \App\Enums\BookingStatus::PENDING => 'warning',
                        \App\Enums\BookingStatus::CONFIRMED => 'success',
                        \App\Enums\BookingStatus::PROCESSING => 'info',
                        \App\Enums\BookingStatus::COMPLETED => 'primary',
                        \App\Enums\BookingStatus::CANCELLED => 'danger',
                        \App\Enums\BookingStatus::REFUNDED => 'secondary',
                        default => 'secondary'
                    }">
                        {{ ucfirst(str_replace('_', ' ', $booking->status->value)) }}
                    </x-admin.ui.badge>
                    <span class="text-sm text-slate-500">Created {{ $booking->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($booking->status === \App\Enums\BookingStatus::PENDING)
                <form action="{{ route('admin.hajj.bookings.confirm', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <x-admin.ui.button type="submit" variant="success">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Confirm Booking
                    </x-admin.ui.button>
                </form>
            @endif
            @if(!in_array($booking->status, [\App\Enums\BookingStatus::CANCELLED, \App\Enums\BookingStatus::REFUNDED, \App\Enums\BookingStatus::COMPLETED]))
                <button
                    type="button"
                    x-data
                    @click="$dispatch('open-modal', 'cancel-booking')"
                    class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                >
                    Cancel Booking
                </button>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Package Info --}}
            @if($booking->package)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Package Details</h3>
                    </x-slot>

                    <div class="flex gap-4">
                        @if($booking->package->thumbnail || $booking->package->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($booking->package->thumbnail ?? $booking->package->image) }}" alt="{{ $booking->package->title }}" class="h-24 w-32 object-cover rounded-lg flex-shrink-0">
                        @endif
                        <div>
                            <h4 class="font-medium text-slate-900">{{ $booking->package->title }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <x-admin.ui.badge :type="match($booking->package->type) {
                                    \App\Enums\PackageType::HAJJ => 'success',
                                    \App\Enums\PackageType::UMRAH => 'info',
                                    \App\Enums\PackageType::TOUR => 'warning',
                                    default => 'secondary'
                                }" size="sm">
                                    {{ ucfirst($booking->package->type->value) }}
                                </x-admin.ui.badge>
                                <span class="text-sm text-slate-500">{{ $booking->package->duration_days }} Days / {{ $booking->package->duration_nights }} Nights</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ $booking->package->short_description }}</p>
                        </div>
                    </div>
                </x-admin.ui.card>
            @endif

            {{-- Travelers --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-slate-900">Travelers ({{ $booking->travelers_count }})</h3>
                    </div>
                </x-slot>

                @if($booking->travelers->count() > 0)
                    <div class="divide-y divide-slate-200">
                        @foreach($booking->travelers as $traveler)
                            <div class="py-4 {{ $loop->first ? 'pt-0' : '' }} {{ $loop->last ? 'pb-0' : '' }}">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-slate-900">{{ $traveler->full_name }}</span>
                                            @if($traveler->is_primary)
                                                <x-admin.ui.badge type="info" size="sm">Primary</x-admin.ui.badge>
                                            @endif
                                        </div>
                                        <div class="mt-1 text-sm text-slate-600 space-y-1">
                                            @if($traveler->passport_number)
                                                <p>Passport: {{ $traveler->passport_number }}</p>
                                            @endif
                                            @if($traveler->nationality)
                                                <p>Nationality: {{ $traveler->nationality }}</p>
                                            @endif
                                            @if($traveler->date_of_birth)
                                                <p>DOB: {{ $traveler->date_of_birth->format('M d, Y') }} ({{ $traveler->date_of_birth->age }} years)</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($traveler->gender)
                                        <span class="text-sm text-slate-500 capitalize">{{ $traveler->gender }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No traveler details provided.</p>
                @endif
            </x-admin.ui.card>

            {{-- Status History --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Status History</h3>
                </x-slot>

                @if($booking->statusLogs->count() > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($booking->statusLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ match($log->to_status) {
                                                    \App\Enums\BookingStatus::PENDING => 'bg-amber-100 text-amber-600',
                                                    \App\Enums\BookingStatus::CONFIRMED => 'bg-green-100 text-green-600',
                                                    \App\Enums\BookingStatus::PROCESSING => 'bg-blue-100 text-blue-600',
                                                    \App\Enums\BookingStatus::COMPLETED => 'bg-indigo-100 text-indigo-600',
                                                    \App\Enums\BookingStatus::CANCELLED => 'bg-red-100 text-red-600',
                                                    \App\Enums\BookingStatus::REFUNDED => 'bg-slate-100 text-slate-600',
                                                    default => 'bg-slate-100 text-slate-600'
                                                } }}">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-slate-900">
                                                        Status changed from
                                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $log->from_status?->value ?? 'new')) }}</span>
                                                        to
                                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $log->to_status?->value ?? 'unknown')) }}</span>
                                                    </p>
                                                    @if($log->notes)
                                                        <p class="mt-1 text-sm text-slate-500">{{ $log->notes }}</p>
                                                    @endif
                                                    @if($log->changedBy)
                                                        <p class="mt-1 text-xs text-slate-400">By {{ $log->changedBy->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-slate-500">
                                                    {{ $log->created_at->format('M d, Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-slate-500">No status changes recorded.</p>
                @endif
            </x-admin.ui.card>

            {{-- Special Requests --}}
            @if($booking->special_requests)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Special Requests</h3>
                    </x-slot>

                    <p class="text-sm text-slate-600">{{ $booking->special_requests }}</p>
                </x-admin.ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Contact Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Contact Information</h3>
                </x-slot>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-slate-600">Name</dt>
                        <dd class="font-medium text-slate-900">{{ $booking->contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-600">Email</dt>
                        <dd class="font-medium text-slate-900">
                            <a href="mailto:{{ $booking->contact_email }}" class="text-amber-600 hover:text-amber-700">{{ $booking->contact_email }}</a>
                        </dd>
                    </div>
                    @if($booking->contact_phone)
                        <div>
                            <dt class="text-sm text-slate-600">Phone</dt>
                            <dd class="font-medium text-slate-900">
                                <a href="tel:{{ $booking->contact_phone }}" class="text-amber-600 hover:text-amber-700">{{ $booking->contact_phone }}</a>
                            </dd>
                        </div>
                    @endif
                    @if($booking->travel_date)
                        <div>
                            <dt class="text-sm text-slate-600">Travel Date</dt>
                            <dd class="font-medium text-slate-900">{{ $booking->travel_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.ui.card>

            {{-- Payment Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Payment</h3>
                </x-slot>

                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Total Amount</dt>
                        <dd class="font-medium text-slate-900">AED {{ number_format($booking->total_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Paid Amount</dt>
                        <dd class="font-medium text-green-600">AED {{ number_format($booking->paid_amount ?? 0, 2) }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-3">
                        <dt class="text-sm font-medium text-slate-900">Balance Due</dt>
                        <dd class="font-bold {{ $booking->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            AED {{ number_format($booking->remaining_balance, 2) }}
                        </dd>
                    </div>
                    @if($booking->payment_method)
                        <div class="pt-2">
                            <dt class="text-sm text-slate-600">Payment Method</dt>
                            <dd class="text-sm text-slate-900">{{ $booking->payment_method }}</dd>
                        </div>
                    @endif
                    @if($booking->payment_reference)
                        <div>
                            <dt class="text-sm text-slate-600">Reference</dt>
                            <dd class="text-sm text-slate-900 font-mono">{{ $booking->payment_reference }}</dd>
                        </div>
                    @endif
                </dl>

                <x-slot name="footer">
                    <button
                        type="button"
                        x-data
                        @click="$dispatch('open-modal', 'update-payment')"
                        class="w-full text-center text-sm text-amber-600 hover:text-amber-700 font-medium"
                    >
                        Update Payment
                    </button>
                </x-slot>
            </x-admin.ui.card>

            {{-- Update Status --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Update Status</h3>
                </x-slot>

                <form action="{{ route('admin.hajj.bookings.update-status', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <x-admin.ui.select
                        name="status"
                        label="New Status"
                        :options="collect($statuses)->mapWithKeys(fn($s) => [$s->value => ucfirst(str_replace('_', ' ', $s->value))])->toArray()"
                        :value="$booking->status->value"
                    />

                    <x-admin.ui.textarea name="notes" label="Notes" rows="2" placeholder="Optional notes about this status change..." />

                    <x-admin.ui.button type="submit" class="w-full">
                        Update Status
                    </x-admin.ui.button>
                </form>
            </x-admin.ui.card>

            {{-- Dates --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Timeline</h3>
                </x-slot>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Created</dt>
                        <dd class="text-slate-900">{{ $booking->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if($booking->confirmed_at)
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Confirmed</dt>
                            <dd class="text-slate-900">{{ $booking->confirmed_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($booking->cancelled_at)
                        <div class="flex justify-between">
                            <dt class="text-slate-600">Cancelled</dt>
                            <dd class="text-red-600">{{ $booking->cancelled_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Updated</dt>
                        <dd class="text-slate-900">{{ $booking->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </x-admin.ui.card>
        </div>
    </div>

    {{-- Cancel Booking Modal --}}
    <x-admin.ui.modal name="cancel-booking" title="Cancel Booking">
        <form action="{{ route('admin.hajj.bookings.cancel', $booking) }}" method="POST">
            @csrf
            @method('PATCH')

            <p class="text-sm text-slate-600 mb-4">Are you sure you want to cancel this booking? This action cannot be undone.</p>

            <x-admin.ui.textarea name="reason" label="Cancellation Reason" rows="3" placeholder="Provide a reason for cancellation..." />

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'cancel-booking')" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                    Keep Booking
                </button>
                <x-admin.ui.button type="submit" variant="danger">
                    Cancel Booking
                </x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.modal>

    {{-- Update Payment Modal --}}
    <x-admin.ui.modal name="update-payment" title="Update Payment">
        <form action="{{ route('admin.hajj.bookings.update-payment', $booking) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                <x-admin.ui.input type="number" name="paid_amount" label="Paid Amount ($)" :value="$booking->paid_amount" step="0.01" min="0" required />

                <x-admin.ui.input name="payment_method" label="Payment Method" :value="$booking->payment_method" placeholder="e.g., Bank Transfer, Credit Card" />

                <x-admin.ui.input name="payment_reference" label="Payment Reference" :value="$booking->payment_reference" placeholder="Transaction ID or reference number" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'update-payment')" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                    Cancel
                </button>
                <x-admin.ui.button type="submit">
                    Update Payment
                </x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.modal>
@endsection
