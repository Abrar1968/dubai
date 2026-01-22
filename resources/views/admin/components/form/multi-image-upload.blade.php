@props([
    'name',
    'label' => null,
    'value' => [],
    'error' => null,
    'hint' => null,
    'required' => false,
    'accept' => 'image/*',
    'maxSize' => 15360,
    'maxFiles' => 10,
])

@php
    $hasError = $error || $errors->has($name) || $errors->has($name . '.*');
    $errorMessage = $error ?? $errors->first($name) ?? $errors->first($name . '.*');
    $maxSizeMb = round($maxSize / 1024, 1);
    $existingImages = is_array($value) ? $value : [];
@endphp

<div
    {{ $attributes->merge(['class' => 'space-y-2']) }}
    x-data="{
        files: [],
        existing: {{ json_encode($existingImages) }},
        error: '',
        dragging: false,
        maxSize: {{ $maxSize * 1024 }},
        maxFiles: {{ $maxFiles }},

        get totalCount() {
            return this.files.length + this.existing.length;
        },

        handleDrop(e) {
            this.dragging = false;
            this.handleFiles(e.dataTransfer.files);
        },

        handleFiles(fileList) {
            this.error = '';
            const remaining = this.maxFiles - this.totalCount;

            if (remaining <= 0) {
                this.error = 'Maximum {{ $maxFiles }} images allowed';
                return;
            }

            const toAdd = Math.min(fileList.length, remaining);

            for (let i = 0; i < toAdd; i++) {
                const file = fileList[i];

                if (!file.type.startsWith('image/')) {
                    this.error = 'Please select only image files';
                    continue;
                }

                if (file.size > this.maxSize) {
                    this.error = 'Each file must be less than {{ $maxSizeMb }}MB';
                    continue;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files.push({
                        file: file,
                        preview: e.target.result,
                        id: Date.now() + Math.random()
                    });
                };
                reader.readAsDataURL(file);
            }

            if (fileList.length > toAdd) {
                this.error = 'Some images were not added due to the maximum limit';
            }
        },

        removeNew(id) {
            this.files = this.files.filter(f => f.id !== id);
        },

        removeExisting(index) {
            this.existing.splice(index, 1);
        },

        updateInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f.file));
            this.$refs.input.files = dt.files;
        }
    }"
    x-effect="updateInput()"
>
    @if($label)
        <label class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
            <span class="text-slate-500 font-normal" x-text="'(' + totalCount + '/{{ $maxFiles }})'"></span>
        </label>
    @endif

    {{-- Upload zone --}}
    <div
        x-show="totalCount < maxFiles"
        x-on:drop.prevent="handleDrop($event)"
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        :class="dragging ? 'border-amber-500 bg-amber-50' : 'border-slate-300'"
        class="relative border-2 border-dashed rounded-lg p-6 transition-colors duration-200 cursor-pointer hover:border-amber-500"
        @click="$refs.input.click()"
    >
        <input
            type="file"
            name="{{ $name }}[]"
            id="{{ $name }}"
            accept="{{ $accept }}"
            multiple
            x-ref="input"
            @change="handleFiles($event.target.files)"
            class="sr-only"
        />

        <div class="text-center">
            <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="mt-2 text-sm text-slate-600">
                <span class="font-medium text-amber-600">Click to upload</span>
                or drag and drop
            </p>
            <p class="mt-1 text-xs text-slate-500">PNG, JPG, WEBP up to {{ $maxSizeMb }}MB each</p>
        </div>
    </div>

    {{-- Existing images --}}
    <template x-if="existing.length > 0">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4">
            <template x-for="(image, index) in existing" :key="'existing-' + index">
                <div class="relative group">
                    <img
                        :src="'{{ asset('storage') }}/' + image"
                        alt="Gallery image"
                        class="h-24 w-full object-cover rounded-lg border border-slate-200"
                    />
                    <button
                        type="button"
                        @click="removeExisting(index)"
                        class="absolute -top-2 -right-2 rounded-full bg-red-600 p-1 text-white shadow-sm hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <input type="hidden" :name="'{{ $name }}_existing[]'" :value="image" />
                </div>
            </template>
        </div>
    </template>

    {{-- New images preview --}}
    <template x-if="files.length > 0">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4">
            <template x-for="item in files" :key="item.id">
                <div class="relative group">
                    <img
                        :src="item.preview"
                        alt="Preview"
                        class="h-24 w-full object-cover rounded-lg border border-slate-200"
                    />
                    <div class="absolute inset-0 bg-amber-500/20 rounded-lg flex items-center justify-center">
                        <span class="text-xs font-medium text-amber-800 bg-amber-100 px-2 py-0.5 rounded">New</span>
                    </div>
                    <button
                        type="button"
                        @click="removeNew(item.id)"
                        class="absolute -top-2 -right-2 rounded-full bg-red-600 p-1 text-white shadow-sm hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </template>

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
