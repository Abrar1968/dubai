<x-admin.layouts.app title="Add FAQ">
    <div class="mx-auto max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.hajj.faqs.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to FAQs
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Add FAQ</h1>
        </div>

        <form action="{{ route('admin.hajj.faqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-admin.ui.card>
                <div class="space-y-4">
                    <!-- Question -->
                    <x-admin.ui.textarea
                        name="question"
                        label="Question"
                        :value="old('question')"
                        required
                        rows="2"
                        placeholder="What is included in the package?"
                    />

                    <!-- Answer -->
                    <x-admin.ui.textarea
                        name="answer"
                        label="Answer"
                        :value="old('answer')"
                        required
                        rows="5"
                        placeholder="Provide a detailed answer..."
                    />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Section -->
                        <x-admin.ui.select
                            name="section"
                            label="Section"
                            :value="old('section', 'hajj')"
                        >
                            <option value="hajj" {{ old('section', 'hajj') === 'hajj' ? 'selected' : '' }}>Hajj & Umrah</option>
                            <option value="global" {{ old('section') === 'global' ? 'selected' : '' }}>General</option>
                        </x-admin.ui.select>

                        <!-- Sort Order -->
                        <x-admin.ui.input
                            type="number"
                            name="sort_order"
                            label="Sort Order"
                            :value="old('sort_order', 0)"
                            min="0"
                            hint="Lower numbers appear first"
                        />
                    </div>

                    <x-admin.ui.toggle
                        name="is_active"
                        label="Active"
                        :checked="old('is_active', true)"
                        description="Show this FAQ on the website"
                    />
                </div>
            </x-admin.ui.card>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.hajj.faqs.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                    Create FAQ
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
