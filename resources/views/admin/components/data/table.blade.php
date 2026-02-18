@props([
    'columns' => [],
    'sortable' => false,
    'selectable' => false,
    'sortColumn' => null,
    'sortDirection' => 'asc',
    'selectAll' => false,
    'striped' => false,
    'hoverable' => true,
])

<div class="overflow-hidden bg-white shadow-sm rounded-2xl border border-slate-200/80 transition-all duration-300 hover:shadow-md">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-200']) }}>
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                <tr>
                    @if($selectable)
                        <th scope="col" class="w-12 px-5 py-4">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    @if($selectAll) checked @endif
                                    @change="$dispatch('select-all', $event.target.checked)"
                                    class="h-4 w-4 rounded-md border-2 border-slate-300 text-amber-600 focus:ring-2 focus:ring-amber-500 focus:ring-offset-0 transition-colors cursor-pointer"
                                />
                            </div>
                        </th>
                    @endif

                    @foreach($columns as $key => $column)
                        @php
                            $columnName = is_array($column) ? ($column['label'] ?? $key) : $column;
                            $columnKey = is_array($column) ? ($column['key'] ?? $key) : $key;
                            $isSortable = $sortable && (is_array($column) ? ($column['sortable'] ?? true) : true);
                            $width = is_array($column) ? ($column['width'] ?? null) : null;
                            $align = is_array($column) ? ($column['align'] ?? 'left') : 'left';
                        @endphp
                        <th
                            scope="col"
                            class="px-6 py-4 text-{{ $align }} text-xs font-bold text-slate-600 uppercase tracking-wider {{ $width ? 'w-' . $width : '' }}"
                        >
                            @if($isSortable)
                                <a
                                    href="{{ request()->fullUrlWithQuery(['sort' => $columnKey, 'direction' => ($sortColumn === $columnKey && $sortDirection === 'asc') ? 'desc' : 'asc']) }}"
                                    class="group inline-flex items-center gap-2 hover:text-slate-900 transition-colors"
                                >
                                    {{ $columnName }}
                                    <span class="flex-none rounded-md p-0.5 {{ $sortColumn === $columnKey ? 'bg-amber-100 text-amber-700' : 'text-slate-400 opacity-0 group-hover:opacity-100' }} transition-all">
                                        @if($sortColumn === $columnKey && $sortDirection === 'desc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            @else
                                {{ $columnName }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($footer))
        <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-t border-slate-200">
            {{ $footer }}
        </div>
    @endif
</div>

{{-- Helper CSS for table rows - can be used with :class on tr elements --}}
@once
@push('styles')
<style>
    .table-row-hover {
        @apply transition-colors duration-150 hover:bg-slate-50/80;
    }
    .table-row-striped:nth-child(even) {
        @apply bg-slate-50/50;
    }
    .table-row-selected {
        @apply bg-amber-50/50;
    }
</style>
@endpush
@endonce
