<x-admin.layouts.app title="Add Emirate">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.typing.family-visa.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-purple-600 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Emirates
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Add Emirate</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new UAE emirate for family visa services.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.typing.family-visa.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Emirate Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                       placeholder="e.g., Sharjah">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Short Description
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                          placeholder="Brief description of family visa services in this emirate">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Intro Text -->
            <div>
                <label for="intro_text" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Introduction Text
                </label>
                <textarea name="intro_text" id="intro_text" rows="4"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                          placeholder="Detailed introduction text shown on the emirate's page">{{ old('intro_text') }}</textarea>
                @error('intro_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order & Active -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Sort Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.typing.family-visa.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                    Create Emirate
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
