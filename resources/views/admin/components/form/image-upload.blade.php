@props([
    'name',
    'label' => null,
    'value' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'accept' => 'image/*',
    'maxSize' => 5120,
    'previewWidth' => 200,
    'previewHeight' => 200,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);
    $maxSizeMb = round($maxSize / 1024, 1);
@endphp

<div
    {{ $attributes->merge(['class' => 'space-y-2']) }}
    x-data="{
        file: null,
        preview: '{{ $value ? asset('storage/' . $value) : '' }}',
        error: '',
        dragging: false,
        maxSize: {{ $maxSize * 1024 }},

        handleDrop(e) {
            this.dragging = false;
            if (e.dataTransfer.files.length) {
                this.handleFile(e.dataTransfer.files[0]);
            }
        },

        handleFile(file) {
            this.error = '';

            if (!file.type.startsWith('image/')) {
                this.error = 'Please select an image file';
                return;
            }

            if (file.size > this.maxSize) {
                this.error = 'File size must be less than {{ $maxSizeMb }}MB';
                return;
            }

            this.file = file;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);

            // Update the file input
            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.input.files = dt.files;
        },

        remove() {
            this.file = null;
            this.preview = '';
            this.error = '';
            this.$refs.input.value = '';
        }
    }"
>
    @if($label)
        <label class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        x-show="!preview"
        x-on:drop.prevent="handleDrop($event)"
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        :class="dragging ? 'border-amber-500 bg-amber-50' : 'border-slate-300'"
        class="relative border-2 border-dashed rounded-lg p-6 transition-colors duration-200 cursor-pointer hover:border-amber-500"
        @click="$refs.input.click()"
    >
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            x-ref="input"
            @change="handleFile($event.target.files[0])"
            class="sr-only"
            @if($required && !$value) required @endif
        />

        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="mt-2 text-sm text-slate-600">
                <span class="font-medium text-amber-600 hover:text-amber-500">Click to upload</span>
                or drag and drop
            </p>
            <p class="mt-1 text-xs text-slate-500">PNG, JPG, WEBP up to {{ $maxSizeMb }}MB</p>
        </div>
    </div>

    {{-- Preview --}}
    <div x-show="preview" class="relative inline-block">
        <img
            :src="preview"
            alt="Preview"
            class="rounded-lg object-cover border border-slate-200"
            style="max-width: {{ $previewWidth }}px; max-height: {{ $previewHeight }}px;"
        />
        <button
            type="button"
            @click="remove()"
            class="absolute -top-2 -right-2 rounded-full bg-red-600 p-1 text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Hidden field for existing value --}}
    @if($value)
        <input type="hidden" name="{{ $name }}_existing" value="{{ $value }}" x-bind:disabled="file !== null" />
    @endif

    {{-- Error display --}}
    <template x-if="error">
        <p class="text-sm text-red-600" x-text="error"></p>
    </template>

    @if($hasError)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif
</div>
