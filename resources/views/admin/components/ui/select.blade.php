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
    'searchable' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);

    $selectClasses = 'block w-full rounded-xl border-2 py-3 pl-4 pr-10 text-slate-900 shadow-sm transition-all duration-300 sm:text-sm appearance-none bg-no-repeat';
    $selectClasses .= $hasError
        ? ' border-red-300 bg-red-50/50 focus:border-red-500 focus:ring-4 focus:ring-red-500/10'
        : ' border-slate-200 bg-white hover:border-slate-300 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10';
    $selectClasses .= $disabled ? ' bg-slate-100 cursor-not-allowed opacity-60' : '';

    $selectedValue = old($name, $value);
    $hasSlotContent = !empty(trim($slot ?? ''));
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-2']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative group">
        <select
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            {{ $attributes->except('class')->merge(['class' => $selectClasses]) }}
            style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-size: 1.5em 1.5em;"
        >
            @if($placeholder && !$multiple)
                <option value="">{{ $placeholder }}</option>
            @endif

            {{-- Render slot content if provided, otherwise use options prop --}}
            @if($hasSlotContent)
                {{ $slot }}
            @else
                @foreach($options as $optionValue => $optionLabel)
                    @if(is_array($optionLabel))
                        {{-- Option group --}}
                        <optgroup label="{{ $optionValue }}" class="font-semibold text-slate-700">
                            @foreach($optionLabel as $groupValue => $groupLabel)
                                <option
                                    value="{{ $groupValue }}"
                                    class="py-2"
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
                            class="py-2"
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

        {{-- Custom dropdown arrow indicator --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
        </div>

        {{-- Error indicator --}}
        @if($hasError)
            <div class="pointer-events-none absolute inset-y-0 right-8 flex items-center pr-3">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
        @endif
    </div>

    {{-- Hint or Error Message --}}
    @if($hint && !$hasError)
        <p class="text-sm text-slate-500 flex items-center gap-1.5">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            {{ $hint }}
        </p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600 flex items-center gap-1.5 font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            {{ $errorMessage }}
        </p>
    @endif
</div>
