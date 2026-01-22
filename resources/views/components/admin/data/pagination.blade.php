@props([
    'paginator',
    'showInfo' => true,
    'showPerPage' => true,
    'perPageOptions' => [10, 25, 50, 100],
])

@if($paginator->hasPages())
    <nav class="flex items-center justify-between" aria-label="Pagination">
        {{-- Info --}}
        @if($showInfo)
            <div class="hidden sm:block">
                <p class="text-sm text-slate-700">
                    Showing
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>
        @endif

        <div class="flex items-center gap-4">
            {{-- Per page selector --}}
            @if($showPerPage)
                <div class="hidden sm:flex items-center gap-2">
                    <label for="per_page" class="text-sm text-slate-700">Show</label>
                    <select
                        id="per_page"
                        name="per_page"
                        onchange="window.location.href = this.value"
                        class="rounded-lg border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                    >
                        @foreach($perPageOptions as $option)
                            <option
                                value="{{ request()->fullUrlWithQuery(['per_page' => $option, 'page' => 1]) }}"
                                @selected($paginator->perPage() == $option)
                            >
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Pagination links --}}
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if($paginator->onFirstPage())
                    <span class="relative inline-flex items-center rounded-lg px-2 py-2 text-slate-300 cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg px-2 py-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Page numbers --}}
                @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="relative inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold bg-amber-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-lg px-2 py-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center rounded-lg px-2 py-2 text-slate-300 cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
