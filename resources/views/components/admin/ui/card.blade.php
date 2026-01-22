@props([
    'title' => null,
    'description' => null,
    'padding' => true,
    'divided' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-slate-200 {{ isset($actions) ? 'flex items-center justify-between' : '' }}">
            <div>
                @if($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
                @if(isset($header))
                    {{ $header }}
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }} {{ $divided ? 'divide-y divide-slate-200' : '' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $footer }}
        </div>
    @endif
</div>
