<x-admin.layouts.app title="Add Office Location">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Office Location</h1>
                <p class="mt-1 text-sm text-gray-500">Create a new office location to display on section home pages.</p>
            </div>
            <a href="{{ route('admin.office-locations.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Locations
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.office-locations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Location Details</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <x-admin.ui.input
                                name="name"
                                label="Location Name"
                                :value="old('name')"
                                placeholder="e.g., Main Office - Dubai"
                                required
                            />

                            <x-admin.ui.textarea
                                name="address"
                                label="Full Address"
                                :value="old('address')"
                                rows="3"
                                placeholder="Enter the complete address..."
                                required
                            />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-admin.ui.input
                                    name="phone"
                                    label="Phone Number"
                                    :value="old('phone')"
                                    placeholder="+971 4 XXX XXXX"
                                />

                                <x-admin.ui.input
                                    type="email"
                                    name="email"
                                    label="Email Address"
                                    :value="old('email')"
                                    placeholder="office@example.com"
                                />
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- Map Coordinates -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Map Coordinates</h3>
                            <p class="text-sm text-gray-500">Optional: Add coordinates to link to Google Maps.</p>
                        </x-slot:header>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-admin.ui.input
                                type="number"
                                step="0.00000001"
                                name="map_lat"
                                label="Latitude"
                                :value="old('map_lat')"
                                placeholder="e.g., 25.276987"
                            />

                            <x-admin.ui.input
                                type="number"
                                step="0.00000001"
                                name="map_lng"
                                label="Longitude"
                                :value="old('map_lng')"
                                placeholder="e.g., 55.296249"
                            />
                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            <a href="https://www.google.com/maps" target="_blank" class="text-purple-600 hover:text-purple-700">
                                Find coordinates on Google Maps →
                            </a>
                        </p>
                    </x-admin.ui.card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Section & Settings -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Settings</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <x-admin.ui.select
                                name="section"
                                label="Section"
                                :value="old('section', 'global')"
                                required
                            >
                                <option value="global" {{ old('section', 'global') === 'global' ? 'selected' : '' }}>Global (All Sections)</option>
                                <option value="hajj" {{ old('section') === 'hajj' ? 'selected' : '' }}>Hajj & Umrah</option>
                                <option value="tour" {{ old('section') === 'tour' ? 'selected' : '' }}>Tour & Travel</option>
                                <option value="typing" {{ old('section') === 'typing' ? 'selected' : '' }}>Typing Services</option>
                            </x-admin.ui.select>

                            <p class="text-xs text-gray-500">
                                <strong>Global</strong> locations appear on all section home pages.
                            </p>

                            <x-admin.ui.input
                                type="number"
                                name="sort_order"
                                label="Sort Order"
                                :value="old('sort_order', 0)"
                                min="0"
                                hint="Lower numbers appear first"
                            />

                            <div class="flex items-center justify-between pt-2 border-t">
                                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" id="is_active" class="peer sr-only" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-purple-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300"></div>
                                </label>
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- Submit -->
                    <x-admin.ui.card>
                        <div class="flex flex-col gap-3">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Create Location
                            </button>
                            <a href="{{ route('admin.office-locations.index') }}"
                               class="w-full inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                        </div>
                    </x-admin.ui.card>
                </div>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
