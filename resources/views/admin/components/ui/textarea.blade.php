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

    $textareaClasses = 'block w-full rounded-xl border-2 px-4 py-3 text-slate-900 shadow-sm transition-all duration-300 sm:text-sm resize-y placeholder:text-slate-400';
    $textareaClasses .= $hasError
        ? ' border-red-300 bg-red-50/50 focus:border-red-500 focus:ring-4 focus:ring-red-500/10'
        : ' border-slate-200 bg-white hover:border-slate-300 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10';
    $textareaClasses .= ($disabled || $readonly) ? ' bg-slate-100 cursor-not-allowed opacity-60' : '';
@endphp

<div
    {{ $attributes->only('class')->merge(['class' => 'space-y-2']) }}
    @if($showCount && $maxlength)
        x-data="{ count: {{ strlen(old($name, $value) ?? '') }} }"
    @endif
>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative group">
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

        {{-- Error indicator --}}
        @if($hasError)
            <div class="pointer-events-none absolute top-3 right-3">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
            @if($hint && !$hasError)
                <p class="text-sm text-slate-500 flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    {{ $hint }}
                </p>
            @elseif($hasError)
                <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $errorMessage }}
                </p>
            @endif
        </div>

        @if($showCount && $maxlength)
            <p class="text-sm font-medium tabular-nums" :class="count >= {{ $maxlength }} ? 'text-red-500' : count >= {{ $maxlength * 0.9 }} ? 'text-amber-500' : 'text-slate-400'">
                <span x-text="count">{{ strlen(old($name, $value) ?? '') }}</span><span class="text-slate-300">/</span>{{ $maxlength }}
            </p>
        @endif
    </div>
</div>
