@props([
    'type' => 'gray',
    'size' => 'md',
    'dot' => false,
    'removable' => false,
    'pulse' => false,
])

@php
    $types = [
        'gray' => 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200',
        'secondary' => 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-200',
        'success' => 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 ring-1 ring-inset ring-green-200',
        'warning' => 'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 ring-1 ring-inset ring-amber-200',
        'danger' => 'bg-gradient-to-r from-red-50 to-rose-50 text-red-700 ring-1 ring-inset ring-red-200',
        'info' => 'bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 ring-1 ring-inset ring-blue-200',
        'purple' => 'bg-gradient-to-r from-purple-50 to-violet-50 text-purple-700 ring-1 ring-inset ring-purple-200',
        'pink' => 'bg-gradient-to-r from-pink-50 to-rose-50 text-pink-700 ring-1 ring-inset ring-pink-200',
        'indigo' => 'bg-gradient-to-r from-indigo-50 to-blue-50 text-indigo-700 ring-1 ring-inset ring-indigo-200',
        'primary' => 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 ring-1 ring-inset ring-amber-200',
    ];

    $dotColors = [
        'gray' => 'bg-slate-500',
        'secondary' => 'bg-slate-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
        'purple' => 'bg-purple-500',
        'pink' => 'bg-pink-500',
        'indigo' => 'bg-indigo-500',
        'primary' => 'bg-amber-500',
    ];

    $sizes = [
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    $typeClasses = $types[$type] ?? $types['gray'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $dotColor = $dotColors[$type] ?? $dotColors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-semibold rounded-full transition-all duration-200 {$typeClasses} {$sizeClasses}"]) }}>
    @if($dot)
        <span class="relative flex h-2 w-2">
            @if($pulse)
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotColor }} opacity-75"></span>
            @endif
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotColor }}"></span>
        </span>
    @endif

    {{ $slot }}

    @if($removable)
        <button
            type="button"
            class="ml-0.5 -mr-1 inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-black/10 focus:outline-none transition-colors"
        >
            <span class="sr-only">Remove</span>
            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
        </button>
    @endif
</span>
