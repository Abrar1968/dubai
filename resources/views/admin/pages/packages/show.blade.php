@extends('admin.layouts.app')

@section('title', $package->title)

@section('header')
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.hajj.packages.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $package->title }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-admin.ui.badge :type="match($package->type) {
                        \App\Enums\PackageType::HAJJ => 'success',
                        \App\Enums\PackageType::UMRAH => 'info',
                        \App\Enums\PackageType::TOUR => 'warning',
                        default => 'secondary'
                    }">
                        {{ ucfirst($package->type->value) }}
                    </x-admin.ui.badge>
                    @if($package->is_active)
                        <x-admin.ui.badge type="success">Active</x-admin.ui.badge>
                    @else
                        <x-admin.ui.badge type="secondary">Inactive</x-admin.ui.badge>
                    @endif
                    @if($package->is_featured)
                        <x-admin.ui.badge type="warning">Featured</x-admin.ui.badge>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.hajj.packages.edit', $package) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.hajj.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Thumbnail & Gallery --}}
            <x-admin.ui.card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Main Thumbnail --}}
                    <div>
                        @if($package->thumbnail || $package->image)
                            <img src="{{ Storage::url($package->thumbnail ?? $package->image) }}" alt="{{ $package->title }}" class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-slate-200 rounded-lg flex items-center justify-center">
                                <svg class="h-16 w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Gallery Grid --}}
                    <div class="grid grid-cols-3 gap-2">
                        @forelse($package->gallery as $image)
                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text ?? $package->title }}" class="w-full h-20 object-cover rounded-lg">
                        @empty
                            <div class="col-span-3 flex items-center justify-center h-20 bg-slate-100 rounded-lg">
                                <span class="text-sm text-slate-500">No gallery images</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </x-admin.ui.card>

            {{-- Description --}}
            @if($package->description)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Description</h3>
                    </x-slot>

                    <div class="prose prose-sm max-w-none">
                        {!! $package->description !!}
                    </div>
                </x-admin.ui.card>
            @endif

            {{-- Itinerary --}}
            @if($package->itinerary && count($package->itinerary) > 0)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Itinerary</h3>
                    </x-slot>

                    <div class="space-y-4">
                        @foreach($package->itinerary as $day)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-16 h-16 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-bold text-amber-700">Day {{ $day['day'] ?? $loop->iteration }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-slate-900">{{ $day['title'] }}</h4>
                                    @if(!empty($day['description']))
                                        <p class="text-sm text-slate-600 mt-1">{{ $day['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-admin.ui.card>
            @endif

            {{-- Inclusions & Exclusions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($package->inclusions && count($package->inclusions) > 0)
                    <x-admin.ui.card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-slate-900">Inclusions</h3>
                        </x-slot>

                        <ul class="space-y-2">
                            @foreach($package->inclusions as $item)
                                <li class="flex items-start gap-2">
                                    <svg class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span class="text-sm text-slate-600">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </x-admin.ui.card>
                @endif

                @if($package->exclusions && count($package->exclusions) > 0)
                    <x-admin.ui.card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-slate-900">Exclusions</h3>
                        </x-slot>

                        <ul class="space-y-2">
                            @foreach($package->exclusions as $item)
                                <li class="flex items-start gap-2">
                                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-sm text-slate-600">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </x-admin.ui.card>
                @endif
            </div>

            {{-- Hotel Details --}}
            @if($package->hotel_details && count($package->hotel_details) > 0)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Hotel Details</h3>
                    </x-slot>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($package->hotel_details as $hotel)
                            <div class="p-4 bg-slate-50 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                    </svg>
                                    <h4 class="font-medium text-slate-900">{{ $hotel['name'] }}</h4>
                                </div>
                                <div class="space-y-1 text-sm text-slate-600">
                                    @if(!empty($hotel['location']))
                                        <p>📍 {{ $hotel['location'] }}</p>
                                    @endif
                                    @if(!empty($hotel['rating']))
                                        <p>⭐ {{ $hotel['rating'] }} Stars</p>
                                    @endif
                                    @if(!empty($hotel['nights']))
                                        <p>🌙 {{ $hotel['nights'] }} Nights</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-admin.ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Quick Info</h3>
                </x-slot>

                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Price</dt>
                        <dd class="text-sm font-medium text-slate-900">${{ number_format($package->price, 2) }}</dd>
                    </div>
                    @if($package->discounted_price)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Discounted Price</dt>
                            <dd class="text-sm font-medium text-green-600">${{ number_format($package->discounted_price, 2) }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-600">Duration</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</dd>
                    </div>
                    @if($package->departure_location)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Departure From</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $package->departure_location }}</dd>
                        </div>
                    @endif
                    @if($package->departure_date)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Departure Date</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $package->departure_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    @if($package->return_date)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Return Date</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $package->return_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    @if($package->max_capacity)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Max Capacity</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $package->max_capacity }} travelers</dd>
                        </div>
                    @endif
                    @if($package->available_slots !== null)
                        <div class="flex justify-between">
                            <dt class="text-sm text-slate-600">Available Slots</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $package->available_slots }}</dd>
                        </div>
                    @endif
                </dl>
            </x-admin.ui.card>

            {{-- Features --}}
            @if($package->features && count($package->features) > 0)
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Features</h3>
                    </x-slot>

                    <div class="flex flex-wrap gap-2">
                        @foreach($package->features as $feature)
                            <x-admin.ui.badge type="secondary">{{ $feature }}</x-admin.ui.badge>
                        @endforeach
                    </div>
                </x-admin.ui.card>
            @endif

            {{-- Bookings Summary --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Bookings</h3>
                </x-slot>

                <div class="text-center py-4">
                    <p class="text-3xl font-bold text-slate-900">{{ $package->bookings->count() }}</p>
                    <p class="text-sm text-slate-600">Total Bookings</p>
                </div>

                @if($package->bookings->count() > 0)
                    <a href="{{ route('admin.hajj.bookings.index', ['package_id' => $package->id]) }}" class="block w-full text-center text-sm text-amber-600 hover:text-amber-700 font-medium">
                        View All Bookings →
                    </a>
                @endif
            </x-admin.ui.card>

            {{-- Meta Info --}}
            <x-admin.ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-slate-900">Meta</h3>
                </x-slot>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Created</dt>
                        <dd class="text-slate-900">{{ $package->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Updated</dt>
                        <dd class="text-slate-900">{{ $package->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Slug</dt>
                        <dd class="text-slate-900 font-mono text-xs">{{ $package->slug }}</dd>
                    </div>
                </dl>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
