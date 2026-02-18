@extends('admin.layouts.app')

@section('title', 'User Details')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
            <p class="mt-1 text-sm text-slate-600">User since {{ $user->created_at->format('F d, Y') }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 rounded-full bg-amber-100 flex items-center justify-center">
                        <span class="text-3xl font-bold text-amber-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <div class="mt-3">
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-200 pt-6 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase">Phone</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase">Nationality</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->nationality ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase">Passport Number</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->passport_number ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->date_of_birth?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 py-2 px-4 bg-amber-600 text-white text-sm font-medium rounded-lg text-center hover:bg-amber-700">
                        Edit User
                    </a>
                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 px-4 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Booking History</h2>
                    <p class="text-sm text-slate-500">{{ $user->bookings->count() }} total booking(s)</p>
                </div>

                @if($user->bookings->count() > 0)
                    <div class="divide-y divide-slate-200">
                        @foreach($user->bookings as $booking)
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono text-sm font-medium text-slate-900">{{ $booking->booking_number }}</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ match($booking->status->value) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-purple-100 text-purple-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-slate-100 text-slate-800'
                                            } }}">
                                                {{ ucfirst($booking->status->value) }}
                                            </span>
                                        </div>
                                        <h4 class="mt-2 font-medium text-slate-900">{{ $booking->package?->title ?? 'Unknown Package' }}</h4>
                                        <p class="text-sm text-slate-500">
                                            {{ $booking->travelers_count }} traveler(s) • Travel date: {{ $booking->travel_date?->format('M d, Y') ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-slate-900">AED {{ number_format($booking->total_amount, 2) }}</p>
                                        <p class="text-xs text-slate-500">{{ $booking->created_at->format('M d, Y') }}</p>
                                        <a href="{{ route('admin.hajj.bookings.show', $booking) }}" class="mt-2 inline-flex text-sm text-amber-600 hover:text-amber-700">
                                            View Details →
                                        </a>
                                    </div>
                                </div>

                                @if($booking->travelers->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-slate-100">
                                        <p class="text-xs font-medium text-slate-500 uppercase mb-2">Travelers</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($booking->travelers as $traveler)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-sm text-slate-700">
                                                    {{ $traveler->name }}
                                                    @if($traveler->is_primary)
                                                        <span class="ml-1 text-xs text-amber-600">(Primary)</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No bookings yet</h3>
                        <p class="mt-1 text-sm text-slate-500">This user hasn't made any bookings.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
