@props([
    'icon' => null,
    'title' => 'No items found',
    'description' => null,
    'action' => null,
    'actionText' => 'Create',
    'actionHref' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    @if($icon)
        <div class="mx-auto h-12 w-12 text-slate-400">
            <x-dynamic-component :component="$icon" class="h-12 w-12" />
        </div>
    @else
        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    @endif

    <h3 class="mt-4 text-lg font-medium text-slate-900">{{ $title }}</h3>

    @if($description)
        <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">{{ $description }}</p>
    @endif

    @if($actionHref)
        <div class="mt-6">
            <a
                href="{{ $actionHref }}"
                class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
