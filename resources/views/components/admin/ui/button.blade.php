@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-amber-600 text-white hover:bg-amber-700 focus:ring-amber-500',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 focus:ring-slate-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 focus:ring-slate-500',
        'outline' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-500',
    ];

    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-lg',
    ];

    $iconSizes = [
        'xs' => 'h-3 w-3',
        'sm' => 'h-4 w-4',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5',
        'xl' => 'h-6 w-6',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        @if($loading)
            <svg class="animate-spin {{ $iconSizes[$size] ?? 'h-4 w-4' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-4 w-4' }}" />
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-4 w-4' }}" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled || $loading) disabled @endif
    >
        @if($loading)
            <svg class="animate-spin {{ $iconSizes[$size] ?? 'h-4 w-4' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-4 w-4' }}" />
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-4 w-4' }}" />
        @endif
    </button>
@endif
