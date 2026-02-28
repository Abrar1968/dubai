<x-admin.layouts.app title="FAQs">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FAQs</h1>
                <p class="mt-1 text-sm text-gray-500">Manage frequently asked questions.</p>
            </div>
            <a href="{{ route('admin.hajj.faqs.create') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add FAQ
            </a>
        </div>

        <!-- Section Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.hajj.faqs.index', ['section' => 'hajj']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentSection === 'hajj' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Hajj
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['hajj'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.hajj.faqs.index', ['section' => 'global']) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $currentSection === 'global' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                General
                <span class="rounded-full bg-white px-1.5 text-xs">{{ $counts['global'] ?? 0 }}</span>
            </a>
        </div>

        <!-- FAQs List -->
        @if($faqs->count() > 0)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <ul class="divide-y divide-gray-200" x-data="{ expanded: null }">
                    @foreach($faqs as $index => $faq)
                        <li class="hover:bg-gray-50">
                            <div class="flex items-start gap-4 p-4">
                                <!-- Sort Handle -->
                                <div class="mt-1 cursor-move text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </div>

                                <!-- Content -->
                                <div class="min-w-0 flex-1">
                                    <button type="button"
                                            @click="expanded = expanded === {{ $index }} ? null : {{ $index }}"
                                            class="w-full text-left">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ $faq->question }}</span>
                                            @if(!$faq->is_active)
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inactive</span>
                                            @endif
                                        </div>
                                    </button>

                                    <div x-show="expanded === {{ $index }}" x-collapse class="mt-3">
                                        <p class="text-sm text-gray-600">{{ $faq->answer }}</p>
                                    </div>
                                </div>

                                <!-- Expand Icon -->
                                <button type="button"
                                        @click="expanded = expanded === {{ $index }} ? null : {{ $index }}"
                                        class="mt-1 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': expanded === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Actions -->
                                <div class="flex items-center gap-1">
                                    <form action="{{ route('admin.hajj.faqs.toggle-active', $faq) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($faq->is_active)
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.hajj.faqs.edit', $faq) }}"
                                       class="rounded p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                                       title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.hajj.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?')">
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
        @else
            <x-admin.data.empty-state
                icon="question-mark-circle"
                title="No FAQs yet"
                description="Start by adding your first FAQ."
                :actionUrl="route('admin.hajj.faqs.create')"
                actionLabel="Add FAQ"
            />
        @endif
    </div>
</x-admin.layouts.app>
