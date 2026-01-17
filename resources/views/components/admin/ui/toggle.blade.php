@props([
    'name',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
    'size' => 'md',
])

@php
    $isChecked = old($name, $checked);

    $sizes = [
        'sm' => [
            'toggle' => 'h-5 w-9',
            'dot' => 'h-4 w-4',
            'translate' => 'translate-x-4',
        ],
        'md' => [
            'toggle' => 'h-6 w-11',
            'dot' => 'h-5 w-5',
            'translate' => 'translate-x-5',
        ],
        'lg' => [
            'toggle' => 'h-7 w-14',
            'dot' => 'h-6 w-6',
            'translate' => 'translate-x-7',
        ],
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
@endphp

<div
    {{ $attributes->merge(['class' => 'flex items-center justify-between']) }}
    x-data="{ enabled: {{ $isChecked ? 'true' : 'false' }} }"
>
    <input type="hidden" name="{{ $name }}" :value="enabled ? '1' : '0'" />

    @if($label || $description)
        <span class="flex flex-grow flex-col mr-4">
            @if($label)
                <span class="text-sm font-medium text-slate-700 {{ $disabled ? 'opacity-50' : '' }}">
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
        :class="enabled ? 'bg-amber-600' : 'bg-slate-200'"
        class="{{ $sizeConfig['toggle'] }} relative inline-flex flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
        @if($disabled) disabled @endif
    >
        <span class="sr-only">{{ $label ?? 'Toggle' }}</span>
        <span
            :class="enabled ? '{{ $sizeConfig['translate'] }}' : 'translate-x-0'"
            class="{{ $sizeConfig['dot'] }} pointer-events-none inline-block transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>
</div>
