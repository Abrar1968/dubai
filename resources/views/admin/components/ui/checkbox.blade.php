@props([
    'name',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
    'value' => '1',
    'error' => null,
])

@php
    $isChecked = old($name, $checked);
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div {{ $attributes->merge(['class' => 'group']) }}>
    <label class="relative flex items-start gap-3 cursor-pointer {{ $disabled ? 'cursor-not-allowed' : '' }}">
        {{-- Custom Checkbox --}}
        <div class="relative flex h-6 items-center">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $name }}"
                value="{{ $value }}"
                @checked($isChecked)
                @disabled($disabled)
                class="peer sr-only"
            />
            <div class="h-5 w-5 rounded-md border-2 transition-all duration-200
                {{ $hasError ? 'border-red-300 bg-red-50' : 'border-slate-300 bg-white' }}
                {{ $disabled ? 'opacity-50' : '' }}
                peer-checked:border-amber-500 peer-checked:bg-amber-500
                peer-focus:ring-4 peer-focus:ring-amber-500/20
                group-hover:border-slate-400 peer-checked:group-hover:border-amber-600
                shadow-sm">
            </div>
            {{-- Checkmark Icon --}}
            <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-3.5 w-3.5 text-white opacity-0 transition-all duration-200 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        {{-- Label & Description --}}
        @if($label || $description)
            <div class="flex flex-col select-none">
                @if($label)
                    <span class="text-sm font-medium transition-colors duration-200
                        {{ $disabled ? 'text-slate-400' : 'text-slate-700 group-hover:text-slate-900' }}">
                        {{ $label }}
                    </span>
                @endif
                @if($description)
                    <span class="text-sm transition-colors duration-200
                        {{ $disabled ? 'text-slate-400' : 'text-slate-500' }}">
                        {{ $description }}
                    </span>
                @endif
            </div>
        @endif
    </label>

    {{-- Error Message --}}
    @if($hasError)
        <p class="mt-2 text-sm text-red-600 flex items-center gap-1.5 font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            {{ $errorMessage }}
        </p>
    @endif
</div>
