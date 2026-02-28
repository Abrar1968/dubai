@props([
    'title',
    'value',
    'subtitle' => null,
    'icon' => 'default',
    'color' => 'gray',
    'trend' => null,
    'trendUp' => true,
    'href' => null,
])

@php
    $colorClasses = [
        'amber' => [
            'bg' => 'bg-gradient-to-br from-amber-400 to-amber-600',
            'shadow' => 'shadow-amber-500/30',
            'text' => 'text-amber-600',
            'hover' => 'hover:text-amber-700',
            'light' => 'bg-amber-50',
        ],
        'blue' => [
            'bg' => 'bg-gradient-to-br from-blue-400 to-blue-600',
            'shadow' => 'shadow-blue-500/30',
            'text' => 'text-blue-600',
            'hover' => 'hover:text-blue-700',
            'light' => 'bg-blue-50',
        ],
        'green' => [
            'bg' => 'bg-gradient-to-br from-green-400 to-green-600',
            'shadow' => 'shadow-green-500/30',
            'text' => 'text-green-600',
            'hover' => 'hover:text-green-700',
            'light' => 'bg-green-50',
        ],
        'purple' => [
            'bg' => 'bg-gradient-to-br from-purple-400 to-purple-600',
            'shadow' => 'shadow-purple-500/30',
            'text' => 'text-purple-600',
            'hover' => 'hover:text-purple-700',
            'light' => 'bg-purple-50',
        ],
        'red' => [
            'bg' => 'bg-gradient-to-br from-red-400 to-red-600',
            'shadow' => 'shadow-red-500/30',
            'text' => 'text-red-600',
            'hover' => 'hover:text-red-700',
            'light' => 'bg-red-50',
        ],
        'gray' => [
            'bg' => 'bg-gradient-to-br from-slate-400 to-slate-600',
            'shadow' => 'shadow-slate-500/30',
            'text' => 'text-slate-600',
            'hover' => 'hover:text-slate-700',
            'light' => 'bg-slate-50',
        ],
    ];

    $colors = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300">
    <!-- Background decoration -->
    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full {{ $colors['light'] }} opacity-50 transition-transform duration-500 group-hover:scale-150"></div>

    <div class="relative flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">{{ $title }}</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-slate-900">{{ $value }}</p>
                @if($trend)
                    <span class="inline-flex items-center gap-0.5 rounded-full px-2 py-0.5 text-xs font-semibold {{ $trendUp ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        @if($trendUp)
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        @else
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" />
                            </svg>
                        @endif
                        {{ $trend }}
                    </span>
                @endif
            </div>
            @if($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="{{ $colors['bg'] }} {{ $colors['shadow'] }} rounded-xl p-3 shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
            @switch($icon)
                @case('package')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                    </svg>
                    @break
                @case('booking')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    @break
                @case('article')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                    @break
                @case('inquiry')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    @break
                @case('users')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    @break
                @case('money')
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @break
                @default
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
            @endswitch
        </div>
    </div>

    @if($href)
        <div class="mt-4 pt-4 border-t border-slate-100">
            <a href="{{ $href }}" class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $colors['text'] }} {{ $colors['hover'] }} transition-colors">
                View details
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    @endif
</div>
