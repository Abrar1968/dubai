@props([
    'name',
    'title' => null,
    'description' => null,
    'maxWidth' => 'lg',
    'closeable' => true,
    'focusable' => true,
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

    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'];
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
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
        @if($closeable) @click="show = false" @endif
    ></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
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
                class="relative w-full {{ $maxWidthClass }} bg-white rounded-xl shadow-xl transform transition-all"
            >
                {{-- Header --}}
                @if($title || $closeable)
                    <div class="flex items-start justify-between p-6 border-b border-slate-200">
                        <div>
                            @if($title)
                                <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                            @endif
                            @if($description)
                                <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                            @endif
                        </div>
                        @if($closeable)
                            <button
                                type="button"
                                @click="show = false"
                                class="ml-4 rounded-lg p-1 text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                    <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
