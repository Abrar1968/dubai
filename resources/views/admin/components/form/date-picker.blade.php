@props([
    'name',
    'label' => null,
    'value' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'min' => null,
    'max' => null,
    'format' => 'Y-m-d',
    'displayFormat' => 'M d, Y',
    'placeholder' => 'Select a date',
    'time' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);

    $inputClasses = 'block w-full rounded-lg border shadow-sm transition-colors duration-200 sm:text-sm pl-10';
    $inputClasses .= $hasError
        ? ' border-red-500 focus:border-red-500 focus:ring-red-500'
        : ' border-slate-300 focus:border-amber-500 focus:ring-amber-500';
    $inputClasses .= $disabled ? ' bg-slate-100 cursor-not-allowed' : ' bg-white';
@endphp

<div
    {{ $attributes->merge(['class' => 'space-y-1']) }}
    x-data="{
        value: '{{ old($name, $value) }}',
        displayValue: '',
        showPicker: false,
        currentMonth: null,
        currentYear: null,
        days: [],
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        weekdays: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

        init() {
            const date = this.value ? new Date(this.value) : new Date();
            this.currentMonth = date.getMonth();
            this.currentYear = date.getFullYear();
            this.generateDays();
            this.updateDisplay();
        },

        generateDays() {
            this.days = [];
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const startPadding = firstDay.getDay();

            // Previous month days
            const prevMonthLastDay = new Date(this.currentYear, this.currentMonth, 0).getDate();
            for (let i = startPadding - 1; i >= 0; i--) {
                this.days.push({ day: prevMonthLastDay - i, current: false, disabled: true });
            }

            // Current month days
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const date = new Date(this.currentYear, this.currentMonth, i);
                let disabled = false;
                @if($min) disabled = disabled || date < new Date('{{ $min }}'); @endif
                @if($max) disabled = disabled || date > new Date('{{ $max }}'); @endif
                this.days.push({ day: i, current: true, disabled: disabled });
            }

            // Next month days
            const remaining = 42 - this.days.length;
            for (let i = 1; i <= remaining; i++) {
                this.days.push({ day: i, current: false, disabled: true });
            }
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.generateDays();
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.generateDays();
        },

        selectDate(day) {
            if (day.disabled) return;
            const date = new Date(this.currentYear, this.currentMonth, day.day);
            this.value = date.toISOString().split('T')[0];
            this.updateDisplay();
            this.showPicker = false;
        },

        updateDisplay() {
            if (!this.value) {
                this.displayValue = '';
                return;
            }
            const date = new Date(this.value + 'T00:00:00');
            this.displayValue = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },

        isSelected(day) {
            if (!this.value || !day.current) return false;
            const selected = new Date(this.value);
            return selected.getDate() === day.day &&
                   selected.getMonth() === this.currentMonth &&
                   selected.getFullYear() === this.currentYear;
        },

        isToday(day) {
            if (!day.current) return false;
            const today = new Date();
            return today.getDate() === day.day &&
                   today.getMonth() === this.currentMonth &&
                   today.getFullYear() === this.currentYear;
        },

        clear() {
            this.value = '';
            this.displayValue = '';
        }
    }"
    @click.outside="showPicker = false"
>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </div>

        <input
            type="text"
            readonly
            :value="displayValue"
            @click="showPicker = !showPicker"
            placeholder="{{ $placeholder }}"
            class="{{ $inputClasses }} cursor-pointer"
            @if($disabled) disabled @endif
        />

        <input type="hidden" name="{{ $name }}" x-model="value" @if($required) required @endif />

        @if(!$disabled)
            <button
                type="button"
                x-show="value"
                @click="clear()"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif

        {{-- Picker dropdown --}}
        <div
            x-show="showPicker"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-1 w-72 rounded-lg bg-white shadow-lg ring-1 ring-black/5 p-4"
            @click.stop
        >
            {{-- Month/Year header --}}
            <div class="flex items-center justify-between mb-4">
                <button type="button" @click="prevMonth()" class="p-1 rounded hover:bg-slate-100">
                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <span class="text-sm font-semibold text-slate-900" x-text="months[currentMonth] + ' ' + currentYear"></span>
                <button type="button" @click="nextMonth()" class="p-1 rounded hover:bg-slate-100">
                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>

            {{-- Weekday headers --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <template x-for="day in weekdays" :key="day">
                    <div class="text-center text-xs font-medium text-slate-500 py-1" x-text="day"></div>
                </template>
            </div>

            {{-- Days grid --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="(day, index) in days" :key="index">
                    <button
                        type="button"
                        @click="selectDate(day)"
                        :disabled="day.disabled"
                        :class="{
                            'bg-amber-600 text-white hover:bg-amber-700': isSelected(day),
                            'text-slate-900 hover:bg-slate-100': day.current && !isSelected(day) && !day.disabled,
                            'text-slate-400': !day.current,
                            'cursor-not-allowed opacity-50': day.disabled,
                            'ring-1 ring-amber-500': isToday(day) && !isSelected(day)
                        }"
                        class="w-8 h-8 text-sm rounded-full flex items-center justify-center transition-colors"
                        x-text="day.day"
                    ></button>
                </template>
            </div>

            {{-- Today button --}}
            <div class="mt-4 pt-4 border-t border-slate-200">
                <button
                    type="button"
                    @click="selectDate({ day: new Date().getDate(), current: true, disabled: false }); currentMonth = new Date().getMonth(); currentYear = new Date().getFullYear(); generateDays();"
                    class="w-full text-sm text-amber-600 hover:text-amber-700 font-medium"
                >
                    Today
                </button>
            </div>
        </div>
    </div>

    @if($hint && !$hasError)
        <p class="text-sm text-slate-500">{{ $hint }}</p>
    @endif

    @if($hasError)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
