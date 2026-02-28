@props([
    'name',
    'label' => null,
    'value' => null,
    'error' => null,
    'rows' => 4,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'hint' => null,
    'maxlength' => null,
    'showCount' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);

    $textareaClasses = 'block w-full rounded-lg border shadow-sm transition-colors duration-200 sm:text-sm resize-y';
    $textareaClasses .= $hasError
        ? ' border-red-500 focus:border-red-500 focus:ring-red-500'
        : ' border-slate-300 focus:border-amber-500 focus:ring-amber-500';
    $textareaClasses .= ($disabled || $readonly) ? ' bg-slate-100 cursor-not-allowed' : ' bg-white';
@endphp

<div
    {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}
    @if($showCount && $maxlength)
        x-data="{ count: {{ strlen(old($name, $value) ?? '') }} }"
    @endif
>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($showCount && $maxlength) x-on:input="count = $el.value.length" @endif
        {{ $attributes->except('class')->merge(['class' => $textareaClasses]) }}
    >{{ old($name, $value) }}</textarea>

    <div class="flex justify-between">
        @if($hint && !$hasError)
            <p class="text-sm text-slate-500">{{ $hint }}</p>
        @elseif($hasError)
            <p class="text-sm text-red-600">{{ $errorMessage }}</p>
        @else
            <span></span>
        @endif

        @if($showCount && $maxlength)
            <p class="text-sm text-slate-500">
                <span x-text="count">{{ strlen(old($name, $value) ?? '') }}</span>/{{ $maxlength }}
            </p>
        @endif
    </div>
</div>
