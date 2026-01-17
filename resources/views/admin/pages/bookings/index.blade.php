@extends('admin.layouts.app')

@section('title', 'Bookings')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Bookings</h1>
            <p class="mt-1 text-sm text-slate-600">Manage customer package bookings</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Total</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Confirmed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Completed</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Cancelled</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Revenue</p>
            <p class="text-2xl font-bold text-slate-900">${{ number_format($stats['total_revenue'], 0) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-4 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600">Status:</span>
                <a href="{{ route('admin.bookings.index') }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ !$currentStatus ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    All
                </a>
                @foreach($statuses as $status)
                    <a href="{{ route('admin.bookings.index', ['status' => $status->value]) }}"
                       class="px-3 py-1.5 text-sm rounded-lg {{ $currentStatus === $status ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bookings Table --}}
    @if($bookings->count() > 0)
        <x-admin.data.table :columns="[
            'booking_number' => 'Booking #',
            'customer' => 'Customer',
            'package' => 'Package',
            'travelers' => 'Travelers',
            'amount' => 'Amount',
            'travel_date' => 'Travel Date',
            'status' => 'Status',
            'actions' => ['label' => '', 'sortable' => false, 'width' => '24'],
        ]">
            @foreach($bookings as $booking)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900 font-mono">{{ $booking->booking_number }}</div>
                        <div class="text-xs text-slate-500">{{ $booking->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $booking->contact_name }}</div>
                        <div class="text-sm text-slate-500">{{ $booking->contact_email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->package)
                            <div class="text-sm text-slate-900">{{ Str::limit($booking->package->title, 30) }}</div>
                            <x-admin.ui.badge :type="match($booking->package->type) {
                                \App\Enums\PackageType::HAJJ => 'success',
                                \App\Enums\PackageType::UMRAH => 'info',
                                \App\Enums\PackageType::TOUR => 'warning',
                                default => 'secondary'
                            }" size="sm">
                                {{ ucfirst($booking->package->type->value) }}
                            </x-admin.ui.badge>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-slate-900">{{ $booking->travelers_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">${{ number_format($booking->total_amount, 2) }}</div>
                        @if($booking->remaining_balance > 0)
                            <div class="text-xs text-red-600">Due: ${{ number_format($booking->remaining_balance, 2) }}</div>
                        @else
                            <div class="text-xs text-green-600">Paid</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $booking->travel_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg" title="View">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            @if($booking->status === \App\Enums\BookingStatus::PENDING)
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg" title="Confirm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot name="footer">
                <x-admin.data.pagination :paginator="$bookings" />
            </x-slot>
        </x-admin.data.table>
    @else
        <x-admin.data.empty-state
            title="No bookings found"
            description="Bookings will appear here when customers book packages."
        />
    @endif
@endsection
