@props([
    'name',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
    'size' => 'md',
    'color' => 'amber',
])

@php
    $isChecked = old($name, $checked);

    $sizes = [
        'sm' => [
            'toggle' => 'h-5 w-9',
            'dot' => 'h-3.5 w-3.5',
            'translate' => 'translate-x-4',
            'padding' => 'p-0.5',
        ],
        'md' => [
            'toggle' => 'h-6 w-11',
            'dot' => 'h-4 w-4',
            'translate' => 'translate-x-5',
            'padding' => 'p-1',
        ],
        'lg' => [
            'toggle' => 'h-8 w-14',
            'dot' => 'h-6 w-6',
            'translate' => 'translate-x-6',
            'padding' => 'p-1',
        ],
    ];

    $colors = [
        'amber' => 'bg-gradient-to-r from-amber-500 to-amber-600 shadow-amber-500/30',
        'green' => 'bg-gradient-to-r from-green-500 to-green-600 shadow-green-500/30',
        'blue' => 'bg-gradient-to-r from-blue-500 to-blue-600 shadow-blue-500/30',
        'purple' => 'bg-gradient-to-r from-purple-500 to-purple-600 shadow-purple-500/30',
        'red' => 'bg-gradient-to-r from-red-500 to-red-600 shadow-red-500/30',
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $colorConfig = $colors[$color] ?? $colors['amber'];
@endphp

<div
    {{ $attributes->merge(['class' => 'flex items-center justify-between']) }}
    x-data="{ enabled: {{ $isChecked ? 'true' : 'false' }} }"
>
    <input type="hidden" name="{{ $name }}" :value="enabled ? '1' : '0'" />

    @if($label || $description)
        <span class="flex flex-grow flex-col mr-4">
            @if($label)
                <span class="text-sm font-semibold text-slate-700 {{ $disabled ? 'opacity-50' : '' }}">
                    {{ $label }}
                </span>
            @endif
            @if($description)
                <span class="text-sm text-slate-500 {{ $disabled ? 'opacity-50' : '' }}">
                    {{ $description }}
                </span>
            @endif
        </span>
    @endif

    <button
        type="button"
        role="switch"
        :aria-checked="enabled"
        @click="enabled = !enabled"
        :class="enabled ? '{{ $colorConfig }} shadow-lg' : 'bg-slate-200 shadow-inner'"
        class="{{ $sizeConfig['toggle'] }} {{ $sizeConfig['padding'] }} relative inline-flex flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-amber-500/20 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
        @if($disabled) disabled @endif
    >
        <span class="sr-only">{{ $label ?? 'Toggle' }}</span>
        <span
            :class="enabled ? '{{ $sizeConfig['translate'] }}' : 'translate-x-0'"
            class="{{ $sizeConfig['dot'] }} pointer-events-none inline-block transform rounded-full bg-white shadow-lg ring-0 transition-all duration-300 ease-in-out"
        >
            {{-- Optional icon inside the toggle dot --}}
            <span class="absolute inset-0 flex items-center justify-center" :class="enabled ? 'opacity-100' : 'opacity-0'">
                <svg class="h-2.5 w-2.5 text-amber-500" fill="currentColor" viewBox="0 0 12 12">
                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                </svg>
            </span>
        </span>
    </button>
</div>
