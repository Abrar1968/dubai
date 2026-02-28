@props([
    'columns' => [],
    'sortable' => false,
    'selectable' => false,
    'sortColumn' => null,
    'sortDirection' => 'asc',
    'selectAll' => false,
])

<div class="overflow-hidden bg-white shadow-sm rounded-xl border border-slate-200">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-200']) }}>
            <thead class="bg-slate-50">
                <tr>
                    @if($selectable)
                        <th scope="col" class="w-12 px-4 py-3">
                            <input
                                type="checkbox"
                                @if($selectAll) checked @endif
                                @change="$dispatch('select-all', $event.target.checked)"
                                class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                            />
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
                            class="px-6 py-3 text-{{ $align }} text-xs font-medium text-slate-500 uppercase tracking-wider {{ $width ? 'w-' . $width : '' }}"
                        >
                            @if($isSortable)
                                <a
                                    href="{{ request()->fullUrlWithQuery(['sort' => $columnKey, 'direction' => ($sortColumn === $columnKey && $sortDirection === 'asc') ? 'desc' : 'asc']) }}"
                                    class="group inline-flex items-center gap-1 hover:text-slate-700"
                                >
                                    {{ $columnName }}
                                    <span class="flex-none rounded {{ $sortColumn === $columnKey ? 'text-slate-700' : 'text-slate-400 invisible group-hover:visible' }}">
                                        @if($sortColumn === $columnKey && $sortDirection === 'desc')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
            <tbody class="bg-white divide-y divide-slate-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($footer))
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200">
            {{ $footer }}
        </div>
    @endif
</div>
