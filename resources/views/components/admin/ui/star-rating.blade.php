@props([
    'rating' => 0,
    'name' => 'rating',
    'editable' => false,
    'size' => 'md'
])

@php
    $sizes = [
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
    ];
    $starSize = $sizes[$size] ?? $sizes['md'];
@endphp

<div 
    @if($editable)
        x-data="{ rating: {{ $rating }}, hoverRating: 0 }"
    @else
        x-data="{ rating: {{ $rating }} }"
    @endif
    class="flex items-center gap-0.5"
>
    @if($editable)
        <input type="hidden" name="{{ $name }}" :value="rating">
    @endif

    @for($i = 1; $i <= 5; $i++)
        <button 
            type="button"
            @if($editable)
                @click="rating = {{ $i }}"
                @mouseenter="hoverRating = {{ $i }}"
                @mouseleave="hoverRating = 0"
                class="focus:outline-none"
            @else
                disabled
                class="cursor-default"
            @endif
        >
            <svg 
                class="{{ $starSize }} transition-colors"
                :class="{
                    'text-amber-400': {{ $editable ? "(hoverRating >= $i || (!hoverRating && rating >= $i))" : "rating >= $i" }},
                    'text-gray-300': {{ $editable ? "(hoverRating ? hoverRating < $i : rating < $i)" : "rating < $i" }}
                }"
                fill="currentColor" 
                viewBox="0 0 20 20"
            >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </button>
    @endfor

    @if(!$editable && $rating > 0)
        <span class="ml-1 text-sm text-gray-600">({{ $rating }}/5)</span>
    @endif
</div>
