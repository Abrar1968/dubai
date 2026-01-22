<x-admin.layouts.app title="Testimonials">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Testimonials</h1>
                <p class="mt-1 text-sm text-gray-500">Manage customer reviews and testimonials.</p>
            </div>
            <a href="{{ route('admin.hajj.testimonials.create') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Testimonial
            </a>
        </div>

        <!-- Status Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hajj.testimonials.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ !$currentStatus ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('admin.hajj.testimonials.index', ['status' => 'pending']) }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('admin.hajj.testimonials.index', ['status' => 'approved']) }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Approved
            </a>
            <a href="{{ route('admin.hajj.testimonials.index', ['status' => 'rejected']) }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Rejected
            </a>
        </div>

        <!-- Testimonials List -->
        @if($testimonials->count() > 0)
            <div class="space-y-4">
                @foreach($testimonials as $testimonial)
                    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <!-- Customer Photo -->
                                <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-full bg-gray-100">
                                    @if($testimonial->avatar)
                                        <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <span class="text-lg font-medium text-gray-400">{{ substr($testimonial->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $testimonial->name }}</h3>
                                            @if($testimonial->location)
                                                <p class="text-sm text-gray-500">{{ $testimonial->location }}</p>
                                            @endif
                                        </div>

                                        <!-- Status & Featured Badges -->
                                        <div class="flex items-center gap-2">
                                            @if($testimonial->is_featured)
                                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">
                                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Featured
                                                </span>
                                            @endif
                                            @switch($testimonial->status)
                                                @case('pending')
                                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Pending</span>
                                                    @break
                                                @case('approved')
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Approved</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Rejected</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    <div class="mt-2">
                                        <x-admin.ui.star-rating :rating="$testimonial->rating" size="sm" />
                                    </div>

                                    <!-- Testimonial Content -->
                                    <blockquote class="mt-3 text-gray-700">
                                        "{{ $testimonial->content }}"
                                    </blockquote>

                                    <!-- Meta Info -->
                                    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                        @if($testimonial->package_type)
                                            <span class="inline-flex items-center">
                                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                {{ ucfirst($testimonial->package_type) }}
                                            </span>
                                        @endif
                                        @if($testimonial->travel_date)
                                            <span class="inline-flex items-center">
                                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $testimonial->travel_date->format('M Y') }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center">
                                            <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Added {{ $testimonial->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4">
                                @if($testimonial->status === 'pending')
                                    <form action="{{ route('admin.hajj.testimonials.approve', $testimonial) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.hajj.testimonials.reject', $testimonial) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200">
                                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.hajj.testimonials.toggle-featured', $testimonial) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-purple-100 px-3 py-1.5 text-xs font-medium text-purple-700 hover:bg-purple-200">
                                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $testimonial->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.hajj.testimonials.edit', $testimonial) }}" class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-200">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.hajj.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-200">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $testimonials->links() }}
            </div>
        @else
            <x-admin.data.empty-state
                icon="chat"
                title="No testimonials yet"
                description="Start by adding your first customer testimonial."
                :actionUrl="route('admin.hajj.testimonials.create')"
                actionLabel="Add Testimonial"
            />
        @endif
    </div>
</x-admin.layouts.app>
