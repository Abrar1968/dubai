@props([
    'icon' => null,
    'title' => 'No items found',
    'description' => null,
    'action' => null,
    'actionText' => 'Create',
    'actionHref' => null,
    'compact' => false,
])

<div {{ $attributes->merge(['class' => $compact ? 'text-center py-8' : 'text-center py-16']) }}>
    {{-- Decorative Background --}}
    <div class="relative inline-block">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-orange-500/10 rounded-full blur-2xl scale-150"></div>

        {{-- Icon Container --}}
        <div class="relative mx-auto {{ $compact ? 'w-16 h-16' : 'w-24 h-24' }} bg-gradient-to-br from-slate-100 to-slate-50 rounded-3xl flex items-center justify-center shadow-lg shadow-slate-200/50 mb-6">
            @if($icon)
                <div class="{{ $compact ? 'h-8 w-8' : 'h-12 w-12' }} text-slate-400">
                    <x-dynamic-component :component="$icon" class="h-full w-full" />
                </div>
            @else
                <svg class="{{ $compact ? 'h-8 w-8' : 'h-12 w-12' }} text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            @endif
        </div>
    </div>

    <h3 class="{{ $compact ? 'text-lg' : 'text-xl' }} font-bold text-slate-900">{{ $title }}</h3>

    @if($description)
        <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">{{ $description }}</p>
    @endif

    @if($actionHref)
        <div class="mt-6">
            <a
                href="{{ $actionHref }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:from-amber-600 hover:to-amber-700 hover:shadow-xl hover:shadow-amber-500/40 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-amber-500/30 transition-all duration-300"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ $actionText }}
            </a>
        </div>
    @elseif(isset($actions))
        <div class="mt-6">
            {{ $actions }}
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
