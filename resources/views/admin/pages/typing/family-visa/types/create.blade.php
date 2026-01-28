<x-admin.layouts.app title="Add Visa Type - {{ $emirate->name }}">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.typing.family-visa.show', $emirate) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-purple-600 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to {{ $emirate->name }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Add Visa Type</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new visa type for {{ $emirate->name }}.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.typing.family-visa.types.store', $emirate) }}" method="POST"
              class="bg-white rounded-xl shadow-sm border divide-y" x-data="visaTypeForm()">
            @csrf

            <!-- Basic Info -->
            <div class="p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900">Basic Information</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Visa Type Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="e.g., New Residency Visa">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Short Description
                        </label>
                        <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Brief one-line description">
                        @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="long_description" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Long Description
                        </label>
                        <textarea name="long_description" id="long_description" rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                  placeholder="Detailed description shown on visa type detail page">{{ old('long_description') }}</textarea>
                        @error('long_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="processing_time" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Processing Time
                        </label>
                        <input type="text" name="processing_time" id="processing_time" value="{{ old('processing_time') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="e.g., 3-5 working days">
                        @error('processing_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Price Range
                        </label>
                        <input type="text" name="price_range" id="price_range" value="{{ old('price_range') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="e.g., AED 500 - 1,200">
                        @error('price_range')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Requirements</h2>
                    <button type="button" @click="addRequirement()"
                            class="text-sm font-medium text-purple-600 hover:text-purple-700">
                        + Add Requirement
                    </button>
                </div>
                <template x-for="(req, index) in requirements" :key="index">
                    <div class="flex gap-2">
                        <input type="text" :name="`requirements[${index}]`" x-model="requirements[index]"
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Enter requirement">
                        <button type="button" @click="removeRequirement(index)"
                                class="p-2 text-gray-400 hover:text-red-600 rounded">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>
                <p x-show="requirements.length === 0" class="text-sm text-gray-500">No requirements added yet.</p>
            </div>

            <!-- Documents -->
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Required Documents</h2>
                    <button type="button" @click="addDocument()"
                            class="text-sm font-medium text-purple-600 hover:text-purple-700">
                        + Add Document
                    </button>
                </div>
                <template x-for="(doc, index) in documents" :key="index">
                    <div class="flex gap-2">
                        <input type="text" :name="`documents[${index}]`" x-model="documents[index]"
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Enter document name">
                        <button type="button" @click="removeDocument(index)"
                                class="p-2 text-gray-400 hover:text-red-600 rounded">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>
                <p x-show="documents.length === 0" class="text-sm text-gray-500">No documents added yet.</p>
            </div>

            <!-- Process Steps -->
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Process Steps</h2>
                    <button type="button" @click="addStep()"
                            class="text-sm font-medium text-purple-600 hover:text-purple-700">
                        + Add Step
                    </button>
                </div>
                <template x-for="(step, index) in processSteps" :key="index">
                    <div class="flex gap-2 items-start">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-purple-100 text-purple-600 text-sm font-medium shrink-0" x-text="index + 1"></span>
                        <input type="text" :name="`process_steps[${index}]`" x-model="processSteps[index]"
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Enter process step">
                        <button type="button" @click="removeStep(index)"
                                class="p-2 text-gray-400 hover:text-red-600 rounded">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>
                <p x-show="processSteps.length === 0" class="text-sm text-gray-500">No process steps added yet.</p>
            </div>

            <!-- CTA Settings -->
            <div class="p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900">Call to Action</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="cta_text" class="block text-sm font-medium text-gray-700 mb-1.5">
                            CTA Button Text
                        </label>
                        <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text', 'Get Started') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="e.g., Get Started">
                    </div>

                    <div>
                        <label for="cta_link" class="block text-sm font-medium text-gray-700 mb-1.5">
                            CTA Link
                        </label>
                        <input type="text" name="cta_link" id="cta_link" value="{{ old('cta_link', '/typing/contact') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="e.g., /typing/contact">
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="p-6 space-y-5">
                <h2 class="text-base font-semibold text-gray-900">Settings</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <label class="relative inline-flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-sm text-gray-600">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="p-6 bg-gray-50 flex items-center justify-end gap-3">
                <a href="{{ route('admin.typing.family-visa.show', $emirate) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                    Create Visa Type
                </button>
            </div>
        </form>
    </div>

    <script>
        function visaTypeForm() {
            return {
                requirements: @json(old('requirements', [])),
                documents: @json(old('documents', [])),
                processSteps: @json(old('process_steps', [])),
                addRequirement() {
                    this.requirements.push('');
                },
                removeRequirement(index) {
                    this.requirements.splice(index, 1);
                },
                addDocument() {
                    this.documents.push('');
                },
                removeDocument(index) {
                    this.documents.splice(index, 1);
                },
                addStep() {
                    this.processSteps.push('');
                },
                removeStep(index) {
                    this.processSteps.splice(index, 1);
                }
            }
        }
    </script>
</x-admin.layouts.app>
