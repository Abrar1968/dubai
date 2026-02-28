@props([
    'title' => null,
    'description' => null,
    'padding' => true,
    'divided' => false,
    'hover' => false,
    'gradient' => false,
])

@php
    $baseClasses = 'bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden transition-all duration-300';
    if ($hover) {
        $baseClasses .= ' hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300';
    }
    if ($gradient) {
        $baseClasses = str_replace('bg-white', 'bg-gradient-to-br from-white to-slate-50', $baseClasses);
    }
@endphp

<div {{ $attributes->merge(['class' => $baseClasses]) }}>
    @if($title || isset($header))
        <div class="px-6 py-5 border-b border-slate-200/80 bg-gradient-to-r from-slate-50/50 to-transparent {{ isset($actions) ? 'flex items-center justify-between gap-4' : '' }}">
            <div class="flex-1 min-w-0">
                @if($title)
                    <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
                @if(isset($header))
                    {{ $header }}
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-3 flex-shrink-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }} {{ $divided ? 'divide-y divide-slate-100' : '' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100/50 border-t border-slate-200/80">
            {{ $footer }}
        </div>
    @endif
</div>
