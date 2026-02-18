@extends('admin.layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Typing Services Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-2">Welcome back, {{ $user->name }}!</h3>
                    <p class="text-teal-100">Typing Services Dashboard - Manage documentation and visa services</p>
                </div>
                <div class="text-right">
                    <div class="text-teal-100 text-sm">Today</div>
                    <div class="text-white text-xl font-bold">{{ now()->format('M j, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Typing Services Stats -->
            <x-admin.ui.stats-card
                title="Typing Services"
                :value="$stats['services']['total']"
                subtitle="{{ $stats['services']['active'] }} active, {{ $stats['services']['featured'] }} featured"
                color="blue"
                icon="📄"
            />

            <!-- Family Visa Stats -->
            <x-admin.ui.stats-card
                title="Family Visa"
                :value="$stats['family_visa']['total_visa_types']"
                subtitle="{{ $stats['family_visa']['total_emirates'] }} emirates, {{ $stats['family_visa']['active_visa_types'] }} active types"
                color="purple"
                icon="👨‍👩‍👧‍👦"
            />

            <!-- Inquiries Stats -->
            <x-admin.ui.stats-card
                title="Inquiries"
                :value="$stats['inquiries']['total']"
                subtitle="{{ $stats['inquiries']['new'] }} new, {{ $stats['inquiries']['responded'] }} responded"
                color="green"
                icon="💬"
            />

            <!-- Office Stats -->
            <x-admin.ui.stats-card
                title="Offices"
                :value="$stats['offices']['total']"
                subtitle="{{ $stats['offices']['global'] }} global locations"
                color="amber"
                icon="🏢"
            />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Inquiries -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Inquiries</h3>
                        <a href="{{ route('admin.typing.inquiries.index') }}" class="text-sm text-teal-600 hover:text-teal-700">
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentInquiries->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentInquiries as $inquiry)
                                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-lg">
                                    <div class="flex-shrink-0 w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                                        <span class="text-teal-600 font-medium">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900 truncate">{{ $inquiry->name }}</p>
                                        <p class="text-sm text-slate-600 truncate">{{ $inquiry->subject }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($inquiry->status->value === 'new') bg-green-100 text-green-700
                                                @elseif($inquiry->status->value === 'read') bg-blue-100 text-blue-700
                                                @elseif($inquiry->status->value === 'responded') bg-purple-100 text-purple-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($inquiry->status->value) }}
                                            </span>
                                            <span class="text-xs text-slate-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.typing.inquiries.show', $inquiry) }}"
                                           class="text-sm text-slate-500 hover:text-slate-700">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-slate-400 text-6xl mb-4">💬</div>
                            <p class="text-slate-600 font-medium">No inquiries yet</p>
                            <p class="text-sm text-slate-500 mt-1">Customer inquiries will appear here when submitted</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Featured Services -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Featured Services</h3>
                        <a href="{{ route('admin.typing.services.index') }}" class="text-sm text-teal-600 hover:text-teal-700">
                            Manage All
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($featuredServices->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($featuredServices as $service)
                                <div class="p-4 bg-slate-50 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        @if($service->icon)
                                            <div class="flex-shrink-0 text-2xl">{{ $service->icon }}</div>
                                        @else
                                            <div class="flex-shrink-0 w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                                <span class="text-teal-600 text-sm">📄</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-slate-900 truncate">{{ $service->title }}</p>
                                            <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $service->short_description }}</p>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <a href="{{ route('admin.typing.services.edit', $service) }}"
                                                   class="text-xs text-teal-600 hover:text-teal-700">
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-slate-400 text-6xl mb-4">📄</div>
                            <p class="text-slate-600 font-medium">No featured services</p>
                            <p class="text-sm text-slate-500 mt-1">Mark services as featured to showcase them here</p>
                            <a href="{{ route('admin.typing.services.index') }}"
                               class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                                Manage Services
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.typing.services.create') }}"
                   class="flex items-center gap-3 p-4 bg-teal-50 hover:bg-teal-100 rounded-lg transition">
                    <div class="flex-shrink-0 w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">+</span>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Add Service</p>
                        <p class="text-sm text-slate-600">Create new typing service</p>
                    </div>
                </a>

                <a href="{{ route('admin.typing.family-visa.index') }}"
                   class="flex items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">👨‍👩‍👧‍👦</span>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Family Visa</p>
                        <p class="text-sm text-slate-600">Manage visa types</p>
                    </div>
                </a>

                <a href="{{ route('admin.typing.inquiries.index') }}"
                   class="flex items-center gap-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">💬</span>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Inquiries</p>
                        <p class="text-sm text-slate-600">Customer messages</p>
                    </div>
                </a>

                <a href="{{ route('admin.typing.settings.index') }}"
                   class="flex items-center gap-3 p-4 bg-amber-50 hover:bg-amber-100 rounded-lg transition">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm">⚙️</span>
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Settings</p>
                        <p class="text-sm text-slate-600">Site configuration</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
