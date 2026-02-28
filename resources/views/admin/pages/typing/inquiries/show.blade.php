<x-admin.layouts.app title="View Inquiry">
    <div class="mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.typing.inquiries.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-purple-600">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Inquiries
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $inquiry->subject }}</h2>
                            @switch($inquiry->status->value)
                                @case('new')
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">New</span>
                                    @break
                                @case('read')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Read</span>
                                    @break
                                @case('responded')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Responded</span>
                                    @break
                                @case('closed')
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Closed</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="prose max-w-none">
                            <p class="whitespace-pre-wrap text-gray-700">{{ $inquiry->message }}</p>
                        </div>

                        @if($inquiry->service_interested)
                            <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                                <p class="text-sm text-purple-800">
                                    <span class="font-medium">Service Interested:</span> {{ $inquiry->service_interested }}
                                </p>
                            </div>
                        @endif

                        <div class="mt-6 border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-500">
                                Received {{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reply Section -->
                <div class="mt-6">
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                        <div class="p-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Quick Reply via Email</h3>
                        </div>
                        <div class="p-5 text-center text-gray-600">
                            <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ urlencode($inquiry->subject) }}"
                               class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Open in Email Client
                            </a>
                            <p class="mt-2 text-sm">
                                Opens your default email client with pre-filled recipient and subject.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $inquiry->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</dt>
                                <dd class="mt-1">
                                    <a href="mailto:{{ $inquiry->email }}" class="text-sm text-purple-600 hover:text-purple-500">
                                        {{ $inquiry->email }}
                                    </a>
                                </dd>
                            </div>
                            @if($inquiry->phone)
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Phone</dt>
                                    <dd class="mt-1">
                                        <a href="tel:{{ $inquiry->phone }}" class="text-sm text-purple-600 hover:text-purple-500">
                                            {{ $inquiry->phone }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @if($inquiry->status->value !== 'responded')
                            <form action="{{ route('admin.typing.inquiries.mark-responded', $inquiry) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-green-100 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-200">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Mark as Responded
                                </button>
                            </form>
                        @endif

                        @if($inquiry->status->value !== 'closed')
                            <form action="{{ route('admin.typing.inquiries.close', $inquiry) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Close Inquiry
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.typing.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-red-100 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-200">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
