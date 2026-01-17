@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'error' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'icon' => null,
    'hint' => null,
    'prefix' => null,
    'suffix' => null,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);

    $inputClasses = 'block w-full rounded-lg border shadow-sm transition-colors duration-200 sm:text-sm';
    $inputClasses .= $hasError
        ? ' border-red-500 focus:border-red-500 focus:ring-red-500'
        : ' border-slate-300 focus:border-amber-500 focus:ring-amber-500';
    $inputClasses .= ($disabled || $readonly) ? ' bg-slate-100 cursor-not-allowed' : ' bg-white';
    $inputClasses .= $icon ? ' pl-10' : '';
    $inputClasses .= $prefix ? ' pl-10' : '';
    $inputClasses .= $suffix ? ' pr-10' : '';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-dynamic-component :component="$icon" class="h-5 w-5 text-slate-400" />
            </div>
        @endif

        @if($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-slate-500 sm:text-sm">{{ $prefix }}</span>
            </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes->except('class')->merge(['class' => $inputClasses]) }}
        />

        @if($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-slate-500 sm:text-sm">{{ $suffix }}</span>
            </div>
        @endif
    </div>

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
