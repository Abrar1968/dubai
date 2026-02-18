<x-admin.layouts.app title="Edit Typing Service">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Typing Service</h1>
                <p class="mt-1 text-sm text-gray-500">Update "{{ $service->title }}"</p>
            </div>
            <a href="{{ route('admin.typing.services.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Services
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.typing.services.update', $service) }}" method="POST" enctype="multipart/form-data"
              x-data="{
                  subServices: {{ Js::from(old('sub_services', $service->sub_services ?? [])) }},
                  addSubService() {
                      this.subServices.push({ name: '', description: '' });
                  },
                  removeSubService(index) {
                      this.subServices.splice(index, 1);
                  }
              }">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <x-admin.ui.input
                                name="title"
                                label="Service Title"
                                :value="old('title', $service->title)"
                                placeholder="e.g., Immigration Services"
                                required
                            />

                            <x-admin.ui.input
                                name="slug"
                                label="Slug (URL)"
                                :value="old('slug', $service->slug)"
                                placeholder="Auto-generated from title if left empty"
                                hint="Leave empty to auto-generate from title"
                            />

                            <x-admin.ui.textarea
                                name="short_description"
                                label="Short Description"
                                :value="old('short_description', $service->short_description)"
                                rows="2"
                                placeholder="Brief description for service cards..."
                            />

                            <x-admin.form.rich-editor
                                name="long_description"
                                label="Full Description"
                                :value="old('long_description', $service->long_description)"
                                placeholder="Detailed description of the service..."
                            />
                        </div>
                    </x-admin.ui.card>

                    <!-- Sub-Services -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Sub-Services</h3>
                                <button type="button" @click="addSubService()"
                                        class="inline-flex items-center gap-1 rounded-lg bg-purple-100 px-3 py-1.5 text-sm font-medium text-purple-700 hover:bg-purple-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Add Sub-Service
                                </button>
                            </div>
                        </x-slot:header>

                        <div class="space-y-4">
                            <template x-if="subServices.length === 0">
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <p class="mt-2">No sub-services added yet</p>
                                    <p class="text-sm">Click "Add Sub-Service" to add offerings under this service.</p>
                                </div>
                            </template>

                            <template x-for="(subService, index) in subServices" :key="index">
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800" x-text="'Sub-Service #' + (index + 1)"></span>
                                        <button type="button" @click="removeSubService(index)"
                                                class="text-gray-400 hover:text-red-600">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                            <input type="text"
                                                   :name="'sub_services[' + index + '][name]'"
                                                   x-model="subService.name"
                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"
                                                   placeholder="Sub-service name">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea :name="'sub_services[' + index + '][description]'"
                                                      x-model="subService.description"
                                                      rows="2"
                                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"
                                                      placeholder="Brief description..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </x-admin.ui.card>

                    <!-- SEO -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">SEO Settings</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <x-admin.ui.input
                                name="meta_title"
                                label="Meta Title"
                                :value="old('meta_title', $service->meta_title)"
                                placeholder="SEO title (leave empty to use service title)"
                            />

                            <x-admin.ui.textarea
                                name="meta_description"
                                label="Meta Description"
                                :value="old('meta_description', $service->meta_description)"
                                rows="2"
                                placeholder="SEO description..."
                            />
                        </div>
                    </x-admin.ui.card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publish Settings -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Publish Settings</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" id="is_active" class="peer sr-only" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-purple-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="is_featured" class="text-sm font-medium text-gray-700">Featured</label>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" id="is_featured" class="peer sr-only" {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-amber-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300"></div>
                                </label>
                            </div>

                            <x-admin.ui.input
                                type="number"
                                name="sort_order"
                                label="Sort Order"
                                :value="old('sort_order', $service->sort_order)"
                                min="0"
                                hint="Lower numbers appear first"
                            />
                        </div>
                    </x-admin.ui.card>

                    <!-- Media -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Media</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <!-- Icon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Emoji)</label>
                                <input type="text"
                                       name="icon"
                                       value="{{ old('icon', $service->icon) }}"
                                       placeholder="📄 or 🏛️"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm"
                                       maxlength="10">
                                <p class="mt-1 text-xs text-gray-500">Optional: emoji icon for the service</p>
                            </div>

                            <!-- Featured Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Featured Image</label>
                                <div class="mt-2" x-data="{ preview: '{{ $service->featured_image_url ?: '' }}' }">
                                    <div class="space-y-3">
                                        <div class="h-40 w-full overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                                            <template x-if="preview">
                                                <img :src="preview" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!preview">
                                                <div class="flex h-full flex-col items-center justify-center text-gray-400">
                                                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="mt-2 text-sm">No image</span>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="featured_image" id="featured_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                            <label for="featured_image" class="cursor-pointer rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                                                Choose Featured Image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Main image displayed on service page header</p>
                            </div>

                            <!-- Gallery Images -->
                            <div x-data="{
                                previews: [],
                                files: [],
                                handleFiles(event) {
                                    const newFiles = Array.from(event.target.files);
                                    newFiles.forEach(file => {
                                        this.previews.push(URL.createObjectURL(file));
                                        this.files.push(file);
                                    });
                                },
                                removeImage(index) {
                                    this.previews.splice(index, 1);
                                    this.files.splice(index, 1);
                                    this.$refs.galleryInput.value = '';
                                }
                            }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                <p class="text-xs text-gray-500 mb-3">Upload government documents or reference images</p>

                                <!-- Existing Gallery -->
                                @if($service->gallery_images && count($service->gallery_images) > 0)
                                <div class="mb-3">
                                    <p class="text-xs text-gray-600 mb-2">Current Gallery:</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($service->gallery_images as $index => $img)
                                        <div class="relative group">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($img) }}" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                                            <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg transition cursor-pointer">
                                                <input type="checkbox" name="remove_gallery[]" value="{{ $index }}" class="w-5 h-5">
                                                <span class="text-white text-xs ml-1">Remove</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Check images to remove them on save</p>
                                </div>
                                @endif

                                <!-- Gallery Preview -->
                                <div class="grid grid-cols-3 gap-2 mb-3" x-show="previews.length > 0">
                                    <template x-for="(preview, index) in previews" :key="index">
                                        <div class="relative group">
                                            <img :src="preview" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                                            <button type="button" @click="removeImage(index)"
                                                    class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                                                ×
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- Upload Button -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-purple-400 transition cursor-pointer"
                                     @click="$refs.galleryInput.click()">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-500">Click to add gallery images</p>
                                </div>
                                <input type="file" name="gallery_images[]" x-ref="galleryInput" multiple accept="image/*" class="hidden" @change="handleFiles($event)">
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- CTA Settings -->
                    <x-admin.ui.card>
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-gray-900">Call to Action</h3>
                        </x-slot:header>

                        <div class="space-y-4">
                            <x-admin.ui.input
                                name="cta_text"
                                label="CTA Button Text"
                                :value="old('cta_text', $service->cta_text ?? 'Learn More')"
                                placeholder="Learn More"
                            />

                            <x-admin.ui.input
                                name="cta_link"
                                label="CTA Link (optional)"
                                :value="old('cta_link', $service->cta_link)"
                                placeholder="/contact or https://..."
                                hint="Leave empty to use service page"
                            />
                        </div>
                    </x-admin.ui.card>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Update Service
                        </button>
                        <a href="{{ route('admin.typing.services.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>

                    <!-- Danger Zone -->
                    <x-admin.ui.card class="border-red-200">
                        <x-slot:header>
                            <h3 class="text-lg font-medium text-red-600">Danger Zone</h3>
                        </x-slot:header>

                        <p class="text-sm text-gray-500 mb-4">Once you delete this service, there is no going back. Please be certain.</p>

                        <form action="{{ route('admin.typing.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                Delete Service
                            </button>
                        </form>
                    </x-admin.ui.card>
                </div>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
