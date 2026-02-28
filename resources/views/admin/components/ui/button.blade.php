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
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] active:scale-[0.98]';

    $variants = [
        'primary' => 'bg-gradient-to-r from-amber-500 to-amber-600 text-white hover:from-amber-600 hover:to-amber-700 focus:ring-amber-500 shadow-lg shadow-amber-500/25 hover:shadow-xl hover:shadow-amber-500/30',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 focus:ring-slate-400 hover:shadow-md',
        'danger' => 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700 focus:ring-red-500 shadow-lg shadow-red-500/25 hover:shadow-xl hover:shadow-red-500/30',
        'success' => 'bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700 focus:ring-green-500 shadow-lg shadow-green-500/25 hover:shadow-xl hover:shadow-green-500/30',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-slate-400',
        'outline' => 'border-2 border-slate-300 bg-white text-slate-700 hover:border-slate-400 hover:bg-slate-50 focus:ring-slate-400 hover:shadow-md',
        'outline-primary' => 'border-2 border-amber-500 bg-transparent text-amber-600 hover:bg-amber-50 focus:ring-amber-400 hover:shadow-md',
    ];

    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3.5 py-2 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];

    $iconSizes = [
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
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
            <svg class="animate-spin {{ $iconSizes[$size] ?? 'h-5 w-5' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-5 w-5' }}" />
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-5 w-5' }}" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled || $loading) disabled @endif
    >
        @if($loading)
            <svg class="animate-spin {{ $iconSizes[$size] ?? 'h-5 w-5' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-5 w-5' }}" />
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'right' && !$loading)
            <x-dynamic-component :component="$icon" class="{{ $iconSizes[$size] ?? 'h-5 w-5' }}" />
        @endif
    </button>
@endif
