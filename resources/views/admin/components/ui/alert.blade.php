@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => false,
    'icon' => true,
    'bordered' => true,
    'solid' => false,
])

@php
    $types = [
        'success' => [
            'bg' => $solid ? 'bg-green-500' : 'bg-green-50',
            'border' => 'border-green-200',
            'ring' => 'ring-green-500/20',
            'text' => $solid ? 'text-white' : 'text-green-800',
            'iconBg' => $solid ? 'bg-green-400/30' : 'bg-green-100',
            'icon' => $solid ? 'text-white' : 'text-green-600',
            'buttonHover' => $solid ? 'hover:bg-green-400/30' : 'hover:bg-green-100',
            'iconPath' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z',
        ],
        'error' => [
            'bg' => $solid ? 'bg-red-500' : 'bg-red-50',
            'border' => 'border-red-200',
            'ring' => 'ring-red-500/20',
            'text' => $solid ? 'text-white' : 'text-red-800',
            'iconBg' => $solid ? 'bg-red-400/30' : 'bg-red-100',
            'icon' => $solid ? 'text-white' : 'text-red-600',
            'buttonHover' => $solid ? 'hover:bg-red-400/30' : 'hover:bg-red-100',
            'iconPath' => 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z',
        ],
        'warning' => [
            'bg' => $solid ? 'bg-amber-500' : 'bg-amber-50',
            'border' => 'border-amber-200',
            'ring' => 'ring-amber-500/20',
            'text' => $solid ? 'text-white' : 'text-amber-800',
            'iconBg' => $solid ? 'bg-amber-400/30' : 'bg-amber-100',
            'icon' => $solid ? 'text-white' : 'text-amber-600',
            'buttonHover' => $solid ? 'hover:bg-amber-400/30' : 'hover:bg-amber-100',
            'iconPath' => 'M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z',
        ],
        'info' => [
            'bg' => $solid ? 'bg-blue-500' : 'bg-blue-50',
            'border' => 'border-blue-200',
            'ring' => 'ring-blue-500/20',
            'text' => $solid ? 'text-white' : 'text-blue-800',
            'iconBg' => $solid ? 'bg-blue-400/30' : 'bg-blue-100',
            'icon' => $solid ? 'text-white' : 'text-blue-600',
            'buttonHover' => $solid ? 'hover:bg-blue-400/30' : 'hover:bg-blue-100',
            'iconPath' => 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z',
        ],
    ];

    $config = $types[$type] ?? $types['info'];
    $borderClass = $bordered ? "border {$config['border']}" : '';
@endphp

<div
    {{ $attributes->merge(['class' => "rounded-xl {$borderClass} p-4 {$config['bg']} ring-1 {$config['ring']} shadow-sm"]) }}
    @if($dismissible)
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
    @endif
>
    <div class="flex items-start gap-3">
        @if($icon)
            <div class="flex-shrink-0 p-1 rounded-lg {{ $config['iconBg'] }}">
                <svg class="h-5 w-5 {{ $config['icon'] }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="{{ $config['iconPath'] }}" clip-rule="evenodd" />
                </svg>
            </div>
        @endif

        <div class="flex-1 min-w-0">
            @if($title)
                <h3 class="text-sm font-semibold {{ $config['text'] }}">{{ $title }}</h3>
            @endif
            @if($message)
                <p class="@if($title) mt-1 @endif text-sm {{ $config['text'] }} opacity-90">{{ $message }}</p>
            @endif
            @if($slot->isNotEmpty())
                <div class="@if($title || $message) mt-2 @endif text-sm {{ $config['text'] }} opacity-90">
                    {{ $slot }}
                </div>
            @endif
        </div>

        @if($dismissible)
            <div class="flex-shrink-0">
                <button
                    type="button"
                    @click="show = false"
                    class="inline-flex rounded-lg p-1.5 {{ $config['text'] }} {{ $config['buttonHover'] }} transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent"
                >
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>
