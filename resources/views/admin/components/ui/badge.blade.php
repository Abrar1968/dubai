@props([
    'type' => 'gray',
    'size' => 'md',
    'dot' => false,
    'removable' => false,
])

@php
    $types = [
        'gray' => 'bg-slate-100 text-slate-700',
        'secondary' => 'bg-slate-100 text-slate-600',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'pink' => 'bg-pink-100 text-pink-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
    ];

    $dotColors = [
        'gray' => 'bg-slate-400',
        'secondary' => 'bg-slate-400',
        'success' => 'bg-green-400',
        'warning' => 'bg-amber-400',
        'danger' => 'bg-red-400',
        'info' => 'bg-blue-400',
        'purple' => 'bg-purple-400',
        'pink' => 'bg-pink-400',
        'indigo' => 'bg-indigo-400',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-sm',
    ];

    $typeClasses = $types[$type] ?? $types['gray'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $dotColor = $dotColors[$type] ?? $dotColors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-medium rounded-full {$typeClasses} {$sizeClasses}"]) }}>
    @if($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
    @endif

    {{ $slot }}

    @if($removable)
        <button
            type="button"
            class="ml-0.5 -mr-1 inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-black/10 focus:outline-none"
        >
            <span class="sr-only">Remove</span>
            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
        </button>
    @endif
</span>
