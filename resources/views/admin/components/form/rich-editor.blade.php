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
                
                // Wait for TinyMCE to be available
                if (typeof window.tinymce !== 'undefined') {
                    this.initTinyMCE();
                } else {
                    setTimeout(() => this.init(), 100);
                }
            },

            initTinyMCE() {
                window.tinymce.init({
                    target: this.$refs.editor,
                    height: {{ $height }},
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat code',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                    placeholder: '{{ $placeholder }}',
                    skin: false,
                    content_css: false,
                    setup: (editor) => {
                        this.editor = editor;
                        editor.on('change keyup', () => {
                            this.content = editor.getContent();
                            this.$refs.textarea.value = this.content;
                        });
                    }
                });
            },

            destroy() {
                if (this.editor) {
                    this.editor.destroy();
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
