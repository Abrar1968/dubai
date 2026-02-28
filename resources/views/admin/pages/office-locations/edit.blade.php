<x-admin.layouts.app title="Edit Office Location">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Office Location</h1>
                <p class="mt-1 text-sm text-gray-500">Update the office location details.</p>
            </div>
            <a href="{{ route('admin.office-locations.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Locations
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.office-locations.update', $location) }}" method="POST">
            @csrf
            @method('PUT')

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
                                :value="old('name', $location->name)"
                                placeholder="e.g., Main Office - Dubai"
                                required
                            />

                            <x-admin.ui.textarea
                                name="address"
                                label="Full Address"
                                :value="old('address', $location->address)"
                                rows="3"
                                placeholder="Enter the complete address..."
                                required
                            />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-admin.ui.input
                                    name="phone"
                                    label="Phone Number"
                                    :value="old('phone', $location->phone)"
                                    placeholder="+971 4 XXX XXXX"
                                />

                                <x-admin.ui.input
                                    type="email"
                                    name="email"
                                    label="Email Address"
                                    :value="old('email', $location->email)"
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
                                :value="old('map_lat', $location->map_lat)"
                                placeholder="e.g., 25.276987"
                            />

                            <x-admin.ui.input
                                type="number"
                                step="0.00000001"
                                name="map_lng"
                                label="Longitude"
                                :value="old('map_lng', $location->map_lng)"
                                placeholder="e.g., 55.296249"
                            />
                        </div>

                        @if($location->google_maps_url)
                            <p class="mt-3 text-xs text-gray-500">
                                <a href="{{ $location->google_maps_url }}" target="_blank" class="text-purple-600 hover:text-purple-700">
                                    View current location on Google Maps →
                                </a>
                            </p>
                        @else
                            <p class="mt-3 text-xs text-gray-500">
                                <a href="https://www.google.com/maps" target="_blank" class="text-purple-600 hover:text-purple-700">
                                    Find coordinates on Google Maps →
                                </a>
                            </p>
                        @endif
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
                                :value="old('section', $location->section)"
                                :options="['global' => 'Global (All Sections)', 'hajj' => 'Hajj & Umrah', 'typing' => 'Typing Services']"
                                required
                            />

                            <p class="text-xs text-gray-500">
                                <strong>Global</strong> locations appear on all section pages.
                            </p>

                            <x-admin.ui.input
                                type="number"
                                name="sort_order"
                                label="Sort Order"
                                :value="old('sort_order', $location->sort_order)"
                                min="0"
                                hint="Lower numbers appear first"
                            />

                            <div class="flex items-center justify-between pt-2 border-t">
                                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" id="is_active" class="peer sr-only" {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Save Changes
                            </button>
                            <a href="{{ route('admin.office-locations.index') }}"
                               class="w-full inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                        </div>
                    </x-admin.ui.card>

                    <!-- Danger Zone -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-red-600">Danger Zone</h3>
                        </x-slot:header>

                        <p class="text-sm text-gray-500 mb-4">Once deleted, this location cannot be recovered.</p>
                        <form action="{{ route('admin.office-locations.destroy', $location) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this location?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Delete Location
                            </button>
                        </form>
                    </x-admin.ui.card>
                </div>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
