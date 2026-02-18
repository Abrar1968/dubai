<x-admin.layouts.app title="View Typing Service">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $service->title }}</h1>
                <p class="mt-1 text-sm text-gray-500">Service details and sub-services</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.typing.services.edit', $service) }}" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.typing.services.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-gray-900">Service Information</h3>
                    </x-slot:header>

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $service->slug }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Short Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->short_description ?? 'Not set' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Full Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $service->long_description ?? 'Not set' }}</dd>
                        </div>
                    </dl>
                </x-admin.ui.card>

                <!-- Sub-Services -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Sub-Services</h3>
                            @php
                                $subServices = is_array($service->sub_services) ? $service->sub_services : [];
                            @endphp
                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                {{ count($subServices) }} {{ Str::plural('sub-service', count($subServices)) }}
                            </span>
                        </div>
                    </x-slot:header>

                    @if(count($subServices) > 0)
                        <div class="space-y-4">
                            @foreach($subServices as $index => $subService)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-start gap-3">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-600 text-white text-sm font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $subService['name'] ?? 'Unnamed' }}</h4>
                                            @if(!empty($subService['description']))
                                                <p class="mt-1 text-sm text-gray-600">{{ $subService['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="mt-2">No sub-services defined</p>
                        </div>
                    @endif
                </x-admin.ui.card>

                <!-- SEO Info -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-gray-900">SEO Settings</h3>
                    </x-slot:header>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->meta_title ?? 'Using service title' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->meta_description ?? 'Not set' }}</dd>
                        </div>
                    </dl>
                </x-admin.ui.card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-gray-900">Status</h3>
                    </x-slot:header>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Active</span>
                            @if($service->is_active)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Featured</span>
                            @if($service->is_featured)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                    </svg>
                                    Featured
                                </span>
                            @else
                                <span class="text-xs text-gray-500">Not featured</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Sort Order</span>
                            <span class="text-sm font-medium text-gray-900">{{ $service->sort_order ?? 0 }}</span>
                        </div>
                    </div>
                </x-admin.ui.card>

                <!-- Media -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-gray-900">Media</h3>
                    </x-slot:header>

                    <div class="space-y-4">
                        @if($service->icon)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Icon</span>
                                <div class="mt-1 text-4xl">{{ $service->icon }}</div>
                            </div>
                        @endif

                        @if($service->image)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Image</span>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}" class="w-full h-48 object-cover rounded-lg">
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm">No image uploaded</p>
                            </div>
                        @endif
                    </div>
                </x-admin.ui.card>

                <!-- CTA -->
                @if($service->cta_text || $service->cta_link)
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Call to Action</h3>
                        </x-slot:header>

                        <dl class="space-y-3">
                            @if($service->cta_text)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Button Text</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $service->cta_text }}</dd>
                                </div>
                            @endif
                            @if($service->cta_link)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Link</dt>
                                    <dd class="mt-1 text-sm text-purple-600">{{ $service->cta_link }}</dd>
                                </div>
                            @endif
                        </dl>
                    </x-admin.ui.card>
                @endif

                <!-- Timestamps -->
                <x-admin.ui.card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-gray-900">Timestamps</h3>
                    </x-slot:header>

                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $service->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </x-admin.ui.card>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
