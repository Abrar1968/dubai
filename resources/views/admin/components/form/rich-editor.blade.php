@props([
    'name',
    'label' => null,
    'value' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'height' => 400,
    'placeholder' => 'Write your content here...',
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);
    $editorId = 'editor-' . $name;
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="{
            content: '',
            editor: null,

            init() {
                this.content = this.$refs.textarea.value;
                
                // Wait for CKEditor to be available
                if (typeof window.ClassicEditor !== 'undefined') {
                    this.initCKEditor();
                } else {
                    setTimeout(() => this.init(), 100);
                }
            },

            initCKEditor() {
                window.ClassicEditor
                    .create(this.$refs.editor, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'link', 'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', '|',
                                'undo', 'redo'
                            ]
                        },
                        placeholder: '{{ $placeholder }}',
                    })
                    .then(editor => {
                        this.editor = editor;
                        
                        // Sync editor content with textarea
                        editor.model.document.on('change:data', () => {
                            this.content = editor.getData();
                            this.$refs.textarea.value = this.content;
                        });
                    })
                    .catch(error => {
                        console.error('CKEditor initialization error:', error);
                    });
            },

            destroy() {
                if (this.editor) {
                    this.editor.destroy().catch(error => {
                        console.error('CKEditor destroy error:', error);
                    });
                }
            }
        }"
        x-on:destroy.window="destroy()"
    >
        {{-- Fallback textarea (hidden but used for form submission) --}}
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            x-ref="textarea"
            class="hidden"
            @if($required) required @endif
        >{{ old($name, $value) }}</textarea>

        {{-- TinyMCE container --}}
        <div
            x-ref="editor"
            class="prose max-w-none border rounded-lg {{ $hasError ? 'border-red-500' : 'border-slate-300' }}"
        >{!! old($name, $value) !!}</div>
    </div>

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
