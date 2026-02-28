<x-admin.layouts.app title="{{ $emirate->name }} - Visa Types">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.typing.family-visa.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-purple-600 mb-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Emirates
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $emirate->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">Manage visa types available in {{ $emirate->name }}.</p>
            </div>
            <a href="{{ route('admin.typing.family-visa.types.create', $emirate) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Visa Type
            </a>
        </div>

        <!-- Emirate Info Card -->
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $emirate->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $emirate->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $emirate->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($emirate->description)
                        <p class="mt-3 text-gray-600">{{ $emirate->description }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.typing.family-visa.edit', $emirate) }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Edit Emirate
                </a>
            </div>
        </div>

        <!-- Visa Types List -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Visa Types ({{ $visaTypes->count() }})</h2>

            @if($visaTypes->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No visa types</h3>
                    <p class="mt-1 text-sm text-gray-500">Add visa types available in {{ $emirate->name }}.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.typing.family-visa.types.create', $emirate) }}" class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                            Add Visa Type
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($visaTypes as $visaType)
                        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h3 class="text-base font-semibold text-gray-900">{{ $visaType->name }}</h3>
                                            <form action="{{ route('admin.typing.family-visa.types.toggle-active', [$emirate, $visaType]) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $visaType->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $visaType->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                                    {{ $visaType->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </div>
                                        @if($visaType->short_description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $visaType->short_description }}</p>
                                        @endif
                                        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                            @if($visaType->processing_time)
                                                <span class="flex items-center gap-1">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $visaType->processing_time }}
                                                </span>
                                            @endif
                                            @if($visaType->price_range)
                                                <span class="flex items-center gap-1">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $visaType->price_range }}
                                                </span>
                                            @endif
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                                {{ count($visaType->requirements ?? []) }} requirements
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                {{ count($visaType->documents ?? []) }} documents
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.typing.family-visa.types.edit', [$emirate, $visaType]) }}"
                                           class="p-2 text-gray-400 hover:text-purple-600 rounded-lg hover:bg-gray-50">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.typing.family-visa.types.destroy', [$emirate, $visaType]) }}" method="POST"
                                              onsubmit="return confirm('Delete this visa type?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-50">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
