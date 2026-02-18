@props([
    'name',
    'title' => null,
    'description' => null,
    'maxWidth' => 'lg',
    'closeable' => true,
    'focusable' => true,
    'icon' => null,
    'iconColor' => 'amber',
])

@php
    $maxWidthClasses = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        'full' => 'sm:max-w-full sm:mx-4',
    ];

    $iconColors = [
        'amber' => 'bg-amber-100 text-amber-600',
        'red' => 'bg-red-100 text-red-600',
        'green' => 'bg-green-100 text-green-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'purple' => 'bg-purple-100 text-purple-600',
    ];

    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'];
    $iconColorClass = $iconColors[$iconColor] ?? $iconColors['amber'];
@endphp

<div
    x-data="{ show: false, focusables() { return [...$el.querySelectorAll('a, button, input:not([type=hidden]), textarea, select, [tabindex]:not([tabindex=\'-1\'])')] }, firstFocusable() { return this.focusables()[0] }, lastFocusable() { return this.focusables().slice(-1)[0] } }"
    x-init="$watch('show', value => { if (value) { document.body.classList.add('overflow-hidden'); @if($focusable) setTimeout(() => firstFocusable()?.focus(), 100) @endif } else { document.body.classList.remove('overflow-hidden'); } })"
    x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    @if($closeable)
        x-on:keydown.escape.window="show = false"
    @endif
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
        @if($closeable) @click="show = false" @endif
    ></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-on:click.outside="@if($closeable) show = false @endif"
                x-trap.inert.noscroll="show"
                class="relative w-full {{ $maxWidthClass }} bg-white rounded-2xl shadow-2xl transform transition-all overflow-hidden"
            >
                {{-- Header --}}
                @if($title || $closeable)
                    <div class="flex items-start justify-between p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50/80 to-transparent">
                        <div class="flex items-start gap-4">
                            @if($icon)
                                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl {{ $iconColorClass }}">
                                    <x-dynamic-component :component="$icon" class="h-6 w-6" />
                                </div>
                            @endif
                            <div>
                                @if($title)
                                    <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                                @endif
                                @if($description)
                                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                                @endif
                            </div>
                        </div>
                        @if($closeable)
                            <button
                                type="button"
                                @click="show = false"
                                class="flex-shrink-0 rounded-xl p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all duration-200"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif

                {{-- Body --}}
                <div class="p-6">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @if(isset($footer))
                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-t border-slate-200">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
