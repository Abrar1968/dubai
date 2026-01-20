@extends('admin.layouts.app')

@section('title', 'Create Package')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.hajj.packages.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create Package</h1>
            <p class="mt-1 text-sm text-slate-600">Add a new Hajj, Umrah, or Tour package</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.hajj.packages.store') }}" method="POST" enctype="multipart/form-data" x-data="packageForm()">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Basic Information</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.input name="title" label="Title" :value="old('title')" required placeholder="e.g., Premium Hajj Package 2025" />

                        <x-admin.ui.input name="slug" label="Slug" :value="old('slug')" placeholder="Leave empty to auto-generate" hint="URL-friendly identifier" />

                        <x-admin.ui.select name="type" label="Package Type" :options="collect($types)->mapWithKeys(fn($t) => [$t->value => ucfirst($t->value)])->toArray()" :value="old('type')" required />

                        <x-admin.ui.textarea name="short_description" label="Short Description" :value="old('short_description')" rows="2" maxlength="500" showCount placeholder="Brief description for package cards..." />

                        <x-admin.form.rich-editor name="description" label="Full Description" :value="old('description')" />
                    </div>
                </x-admin.ui.card>

                {{-- Pricing & Capacity --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Pricing & Capacity</h3>
                    </x-slot>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.ui.input type="number" name="price" label="Price ($)" :value="old('price')" required min="0" step="0.01" placeholder="0.00" />

                        <x-admin.ui.input type="number" name="discounted_price" label="Discounted Price ($)" :value="old('discounted_price')" min="0" step="0.01" placeholder="0.00" hint="Leave empty for no discount" />

                        <x-admin.ui.input type="number" name="max_capacity" label="Max Capacity" :value="old('max_capacity')" min="1" placeholder="e.g., 50" />

                        <x-admin.ui.input type="number" name="available_slots" label="Available Slots" :value="old('available_slots')" min="0" placeholder="e.g., 25" />
                    </div>
                </x-admin.ui.card>

                {{-- Duration & Dates --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Duration & Dates</h3>
                    </x-slot>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-admin.ui.input type="number" name="duration_days" label="Duration (Days)" :value="old('duration_days')" required min="1" placeholder="e.g., 15" />

                        <x-admin.ui.input type="number" name="duration_nights" label="Duration (Nights)" :value="old('duration_nights')" required min="0" placeholder="e.g., 14" />

                        <x-admin.ui.input name="departure_location" label="Departure Location" :value="old('departure_location')" placeholder="e.g., Dubai, UAE" />

                        <x-admin.form.date-picker name="departure_date" label="Departure Date" :value="old('departure_date')" />

                        <x-admin.form.date-picker name="return_date" label="Return Date" :value="old('return_date')" />
                    </div>
                </x-admin.ui.card>

                {{-- Features, Inclusions, Exclusions --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Features & Details</h3>
                    </x-slot>

                    {{-- Features --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Features</label>
                        <div class="space-y-2">
                            <template x-for="(feature, index) in features" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'features[' + index + ']'" x-model="feature.value" class="flex-1 rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Enter a feature...">
                                    <button type="button" @click="features.splice(index, 1)" class="p-2 text-slate-400 hover:text-red-600 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="features.push({ value: '' })" class="mt-2 text-sm text-amber-600 hover:text-amber-700">
                            + Add Feature
                        </button>
                    </div>

                    {{-- Inclusions --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Inclusions</label>
                        <div class="space-y-2">
                            <template x-for="(item, index) in inclusions" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'inclusions[' + index + ']'" x-model="item.value" class="flex-1 rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="What's included...">
                                    <button type="button" @click="inclusions.splice(index, 1)" class="p-2 text-slate-400 hover:text-red-600 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="inclusions.push({ value: '' })" class="mt-2 text-sm text-amber-600 hover:text-amber-700">
                            + Add Inclusion
                        </button>
                    </div>

                    {{-- Exclusions --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Exclusions</label>
                        <div class="space-y-2">
                            <template x-for="(item, index) in exclusions" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'exclusions[' + index + ']'" x-model="item.value" class="flex-1 rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="What's not included...">
                                    <button type="button" @click="exclusions.splice(index, 1)" class="p-2 text-slate-400 hover:text-red-600 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="exclusions.push({ value: '' })" class="mt-2 text-sm text-amber-600 hover:text-amber-700">
                            + Add Exclusion
                        </button>
                    </div>
                </x-admin.ui.card>

                {{-- Itinerary --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Itinerary</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <template x-for="(day, index) in itinerary" :key="index">
                            <div class="p-4 bg-slate-50 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-medium text-slate-900" x-text="'Day ' + (index + 1)"></span>
                                    <button type="button" @click="itinerary.splice(index, 1)" class="p-1 text-slate-400 hover:text-red-600 rounded">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" :name="'itinerary[' + index + '][day]'" :value="index + 1">
                                <div class="space-y-3">
                                    <input type="text" :name="'itinerary[' + index + '][title]'" x-model="day.title" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Day title...">
                                    <textarea :name="'itinerary[' + index + '][description]'" x-model="day.description" rows="2" class="w-full rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Day description..."></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="itinerary.push({ title: '', description: '' })" class="mt-4 text-sm text-amber-600 hover:text-amber-700">
                        + Add Day
                    </button>
                </x-admin.ui.card>

                {{-- Hotel Details --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Hotel Details</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <template x-for="(hotel, index) in hotels" :key="index">
                            <div class="p-4 bg-slate-50 rounded-lg">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-medium text-slate-900" x-text="'Hotel ' + (index + 1)"></span>
                                    <button type="button" @click="hotels.splice(index, 1)" class="p-1 text-slate-400 hover:text-red-600 rounded">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="text" :name="'hotel_details[' + index + '][name]'" x-model="hotel.name" class="rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Hotel name...">
                                    <input type="text" :name="'hotel_details[' + index + '][location]'" x-model="hotel.location" class="rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Location...">
                                    <select :name="'hotel_details[' + index + '][rating]'" x-model="hotel.rating" class="rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                                        <option value="">Select rating</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    <input type="number" :name="'hotel_details[' + index + '][nights]'" x-model="hotel.nights" class="rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Number of nights" min="0">
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="hotels.push({ name: '', location: '', rating: '', nights: '' })" class="mt-4 text-sm text-amber-600 hover:text-amber-700">
                        + Add Hotel
                    </button>
                </x-admin.ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Publish</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <x-admin.ui.toggle name="is_active" label="Active" :checked="old('is_active', true)" description="Make this package visible on the website" />

                        <x-admin.ui.toggle name="is_featured" label="Featured" :checked="old('is_featured', false)" description="Show on homepage featured section" />
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.hajj.packages.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                                Cancel
                            </a>
                            <x-admin.ui.button type="submit">
                                Create Package
                            </x-admin.ui.button>
                        </div>
                    </x-slot>
                </x-admin.ui.card>

                {{-- Thumbnail --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Thumbnail</h3>
                    </x-slot>

                    <x-admin.form.image-upload name="thumbnail" label="Package Thumbnail" required accept="image/jpeg,image/png,image/jpg,image/webp" maxSize="5120" hint="Recommended: 800x600px" />
                </x-admin.ui.card>

                {{-- Gallery --}}
                <x-admin.ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-slate-900">Gallery</h3>
                    </x-slot>

                    <x-admin.form.multi-image-upload name="gallery" label="Gallery Images" accept="image/jpeg,image/png,image/jpg,image/webp" maxSize="15360" maxFiles="10" hint="Upload up to 10 images" />
                </x-admin.ui.card>
            </div>
        </div>
    </form>

    <script>
        function packageForm() {
            return {
                features: @json(old('features', [])).length ? @json(old('features', [])).map(v => ({ value: v })) : [{ value: '' }],
                inclusions: @json(old('inclusions', [])).length ? @json(old('inclusions', [])).map(v => ({ value: v })) : [{ value: '' }],
                exclusions: @json(old('exclusions', [])).length ? @json(old('exclusions', [])).map(v => ({ value: v })) : [{ value: '' }],
                itinerary: @json(old('itinerary', [])).length ? @json(old('itinerary', [])) : [{ title: '', description: '' }],
                hotels: @json(old('hotel_details', [])).length ? @json(old('hotel_details', [])) : [],
            }
        }
    </script>
@endsection
