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

    $inputClasses = 'block w-full rounded-xl border-2 shadow-sm transition-all duration-300 sm:text-sm py-3 px-4';
    $inputClasses .= $hasError
        ? ' border-red-300 bg-red-50/50 focus:border-red-500 focus:ring-4 focus:ring-red-500/10'
        : ' border-slate-200 bg-white hover:border-slate-300 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10';
    $inputClasses .= ($disabled || $readonly) ? ' bg-slate-100 cursor-not-allowed opacity-75' : '';
    $inputClasses .= $icon ? ' pl-11' : '';
    $inputClasses .= $prefix ? ' pl-11' : '';
    $inputClasses .= $suffix ? ' pr-11' : '';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-2']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative group">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <x-dynamic-component :component="$icon" class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-amber-500" />
            </div>
        @endif

        @if($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                <span class="text-slate-500 sm:text-sm font-medium">{{ $prefix }}</span>
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
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                <span class="text-slate-500 sm:text-sm font-medium">{{ $suffix }}</span>
            </div>
        @endif

        @if($hasError)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
        @endif
    </div>

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500 flex items-center gap-1.5">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            {{ $hint }}
        </p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            {{ $errorMessage }}
        </p>
    @endif
</div>
