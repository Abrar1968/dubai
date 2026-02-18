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
        uploading: false,
        progress: 0,

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
            this.uploading = true;
            this.progress = 0;

            // Simulate upload progress
            const interval = setInterval(() => {
                this.progress += 10;
                if (this.progress >= 100) {
                    clearInterval(interval);
                    this.uploading = false;
                }
            }, 50);

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
        <label class="block text-sm font-semibold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div
        x-show="!preview"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-on:drop.prevent="handleDrop($event)"
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        :class="dragging ? 'border-amber-500 bg-amber-50/50 ring-4 ring-amber-500/10 scale-[1.02]' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
        class="relative border-2 border-dashed rounded-2xl p-8 transition-all duration-300 cursor-pointer group"
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
            <div class="mx-auto w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 group-hover:bg-amber-100 group-hover:scale-110" :class="dragging && 'bg-amber-100 scale-110'">
                <svg class="h-8 w-8 text-slate-400 transition-colors group-hover:text-amber-500" :class="dragging && 'text-amber-500'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600">
                <span class="font-semibold text-amber-600 hover:text-amber-700">Click to upload</span>
                <span class="text-slate-400">or drag and drop</span>
            </p>
            <p class="mt-2 text-xs text-slate-400">PNG, JPG, WEBP up to {{ $maxSizeMb }}MB</p>
        </div>
    </div>

    {{-- Preview --}}
    <div
        x-show="preview"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative inline-block group"
    >
        {{-- Upload Progress Overlay --}}
        <div x-show="uploading" class="absolute inset-0 bg-white/80 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center z-10">
            <div class="w-16 h-16 mb-3">
                <svg class="animate-spin h-full w-full text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div class="w-32 h-2 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
            </div>
            <p class="mt-2 text-sm text-slate-600 font-medium" x-text="progress + '%'"></p>
        </div>

        <div class="relative overflow-hidden rounded-2xl border-2 border-slate-200 shadow-lg group-hover:shadow-xl group-hover:border-amber-200 transition-all duration-300">
            <img
                :src="preview"
                alt="Preview"
                class="object-cover transition-transform duration-500 group-hover:scale-105"
                style="max-width: {{ $previewWidth }}px; max-height: {{ $previewHeight }}px;"
            />
            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>

        {{-- Remove Button --}}
        <button
            type="button"
            @click="remove()"
            class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-red-500 text-white shadow-lg flex items-center justify-center hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-500/30 transition-all duration-200 transform hover:scale-110"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Replace Button --}}
        <button
            type="button"
            @click="$refs.input.click()"
            class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-medium text-slate-700 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-white"
        >
            Replace image
        </button>
    </div>

    {{-- Hidden field for existing value --}}
    @if($value)
        <input type="hidden" name="{{ $name }}_existing" value="{{ $value }}" x-bind:disabled="file !== null" />
    @endif

    {{-- Error display --}}
    <template x-if="error">
        <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span x-text="error"></span>
        </p>
    </template>

    @if($hasError)
        <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            {{ $errorMessage }}
        </p>
    @endif

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500 flex items-center gap-1.5">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            {{ $hint }}
        </p>
    @endif
</div>
