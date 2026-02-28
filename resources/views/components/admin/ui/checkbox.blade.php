@props([
    'name',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
    'value' => '1',
])

@php
    $isChecked = old($name, $checked);
@endphp

<div {{ $attributes->merge(['class' => 'relative flex items-start']) }}>
    <div class="flex h-6 items-center">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            @checked($isChecked)
            @disabled($disabled)
            class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed"
        />
    </div>
    @if($label || $description)
        <div class="ml-3 text-sm leading-6">
            @if($label)
                <label for="{{ $name }}" class="font-medium text-slate-700 {{ $disabled ? 'opacity-50' : '' }}">
                    {{ $label }}
                </label>
            @endif
            @if($description)
                <p class="text-slate-500 {{ $disabled ? 'opacity-50' : '' }}">{{ $description }}</p>
            @endif
        </div>
    @endif
</div>
