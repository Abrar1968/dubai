<x-admin.layouts.app title="Edit Testimonial">
    <div class="mx-auto max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.hajj.testimonials.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Testimonials
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Edit Testimonial</h1>
        </div>

        <form action="{{ route('admin.hajj.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Photo</label>
                        <div class="mt-2" x-data="{ preview: '{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : '' }}' }">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 overflow-hidden rounded-full bg-gray-100">
                                    <template x-if="preview">
                                        <img :src="preview" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <div class="flex h-full items-center justify-center">
                                            <span class="text-lg font-medium text-gray-400">{{ substr($testimonial->name, 0, 1) }}</span>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/heic,image/heif" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                    <label for="avatar" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                        Change Photo
                                    </label>
                                </div>
                            </div>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Customer Name -->
                        <x-admin.ui.input
                            name="name"
                            label="Customer Name"
                            :value="old('name', $testimonial->name)"
                            required
                        />

                        <!-- Location -->
                        <x-admin.ui.input
                            name="location"
                            label="Location"
                            :value="old('location', $testimonial->location)"
                        />
                    </div>
                </div>
            </x-admin.ui.card>

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Testimonial Details</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rating <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <x-admin.ui.star-rating :rating="old('rating', $testimonial->rating)" name="rating" :editable="true" size="lg" />
                        </div>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <x-admin.ui.textarea
                        name="content"
                        label="Testimonial Content"
                        :value="old('content', $testimonial->content)"
                        required
                        rows="4"
                    />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Package Type -->
                        <x-admin.ui.select
                            name="package_type"
                            label="Package Type"
                            :value="old('package_type', $testimonial->package_type)"
                        >
                            <option value="">Select Package Type</option>
                            <option value="hajj" {{ old('package_type', $testimonial->package_type) === 'hajj' ? 'selected' : '' }}>Hajj</option>
                            <option value="umrah" {{ old('package_type', $testimonial->package_type) === 'umrah' ? 'selected' : '' }}>Umrah</option>
                        </x-admin.ui.select>

                        <!-- Travel Date -->
                        <x-admin.ui.input
                            type="month"
                            name="travel_date"
                            label="Travel Date"
                            :value="old('travel_date', $testimonial->travel_date?->format('Y-m'))"
                        />
                    </div>
                </div>
            </x-admin.ui.card>

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Settings</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <x-admin.ui.select
                        name="status"
                        label="Status"
                        :value="old('status', $testimonial->status)"
                    >
                        <option value="pending" {{ old('status', $testimonial->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ old('status', $testimonial->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $testimonial->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </x-admin.ui.select>

                    <x-admin.ui.toggle
                        name="is_featured"
                        label="Featured"
                        :checked="old('is_featured', $testimonial->is_featured)"
                        description="Show this testimonial prominently on the homepage"
                    />
                </div>
            </x-admin.ui.card>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.hajj.testimonials.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                    Update Testimonial
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
