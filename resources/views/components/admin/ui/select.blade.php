@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'error' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'hint' => null,
    'multiple' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);

    $selectClasses = 'block w-full rounded-lg border shadow-sm transition-colors duration-200 sm:text-sm';
    $selectClasses .= $hasError
        ? ' border-red-500 focus:border-red-500 focus:ring-red-500'
        : ' border-slate-300 focus:border-amber-500 focus:ring-amber-500';
    $selectClasses .= $disabled ? ' bg-slate-100 cursor-not-allowed' : ' bg-white';

    $selectedValue = old($name, $value);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
        {{ $attributes->except('class')->merge(['class' => $selectClasses]) }}
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                {{-- Option group --}}
                <optgroup label="{{ $optionValue }}">
                    @foreach($optionLabel as $groupValue => $groupLabel)
                        <option
                            value="{{ $groupValue }}"
                            @if($multiple)
                                @selected(is_array($selectedValue) && in_array($groupValue, $selectedValue))
                            @else
                                @selected($selectedValue == $groupValue)
                            @endif
                        >
                            {{ $groupLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option
                    value="{{ $optionValue }}"
                    @if($multiple)
                        @selected(is_array($selectedValue) && in_array($optionValue, $selectedValue))
                    @else
                        @selected($selectedValue == $optionValue)
                    @endif
                >
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
