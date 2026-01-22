<x-admin.layouts.app title="Contact Inquiries">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Contact Inquiries</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and respond to customer inquiries.</p>
            </div>
        </div>

        <!-- Status Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hajj.inquiries.index') }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ !$currentStatus ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.hajj.inquiries.index', ['status' => 'new']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'new' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                New
                @if($counts['new'] > 0)
                    <span class="rounded-full bg-blue-500 px-1.5 text-xs text-white">{{ $counts['new'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.hajj.inquiries.index', ['status' => 'read']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'read' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Read
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['read'] }}</span>
            </a>
            <a href="{{ route('admin.hajj.inquiries.index', ['status' => 'responded']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'responded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Responded
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['responded'] }}</span>
            </a>
            <a href="{{ route('admin.hajj.inquiries.index', ['status' => 'closed']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentStatus === 'closed' ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Closed
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['closed'] }}</span>
            </a>
        </div>

        <!-- Inquiries List -->
        @if($inquiries->count() > 0)
            <div x-data="{ selected: [] }" class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <!-- Bulk Actions -->
                <div x-show="selected.length > 0" x-cloak class="flex items-center gap-4 border-b border-gray-200 bg-amber-50 px-4 py-3">
                    <span class="text-sm font-medium text-amber-800" x-text="selected.length + ' selected'"></span>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.hajj.inquiries.bulk-mark-read') }}" method="POST" class="inline">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="rounded bg-amber-600 px-3 py-1 text-xs font-medium text-white hover:bg-amber-700">
                                Mark as Read
                            </button>
                        </form>
                        <form action="{{ route('admin.hajj.inquiries.bulk-delete') }}" method="POST" class="inline" onsubmit="return confirm('Delete selected inquiries?')">
                            @csrf
                            @method('DELETE')
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit" class="rounded bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <ul class="divide-y divide-gray-200">
                    @foreach($inquiries as $inquiry)
                        <li class="relative hover:bg-gray-50 {{ $inquiry->status === 'new' ? 'bg-blue-50/50' : '' }}">
                            <div class="flex items-start gap-4 px-4 py-4">
                                <!-- Checkbox -->
                                <input type="checkbox"
                                       :value="{{ $inquiry->id }}"
                                       x-model="selected"
                                       class="mt-1 rounded border-gray-300 text-amber-600 focus:ring-amber-500">

                                <!-- Status Indicator -->
                                <div class="flex-shrink-0 pt-0.5">
                                    @switch($inquiry->status)
                                        @case('new')
                                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-500" title="New"></span>
                                            @break
                                        @case('read')
                                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-yellow-500" title="Read"></span>
                                            @break
                                        @case('responded')
                                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500" title="Responded"></span>
                                            @break
                                        @case('closed')
                                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-gray-400" title="Closed"></span>
                                            @break
                                    @endswitch
                                </div>

                                <!-- Content -->
                                <a href="{{ route('admin.hajj.inquiries.show', $inquiry) }}" class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-gray-900 {{ $inquiry->status === 'new' ? 'font-semibold' : '' }}">
                                                {{ $inquiry->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $inquiry->email }}</p>
                                        </div>
                                        <span class="flex-shrink-0 text-xs text-gray-500">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-gray-800">{{ $inquiry->subject }}</p>
                                    <p class="mt-1 line-clamp-2 text-sm text-gray-600">{{ $inquiry->message }}</p>

                                    @if($inquiry->phone)
                                        <p class="mt-2 text-xs text-gray-500">
                                            <svg class="mr-1 inline h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $inquiry->phone }}
                                        </p>
                                    @endif
                                </a>

                                <!-- Quick Actions -->
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.hajj.inquiries.show', $inquiry) }}"
                                       class="rounded p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                                       title="View Details">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.hajj.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirm('Delete this inquiry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded p-2 text-gray-400 hover:bg-red-100 hover:text-red-600" title="Delete">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $inquiries->links() }}
            </div>
        @else
            <x-admin.data.empty-state
                title="No inquiries yet"
                description="Customer inquiries will appear here."
            />
        @endif
    </div>
</x-admin.layouts.app>
