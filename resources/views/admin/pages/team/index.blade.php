<x-admin.layouts.app title="Team Members">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Team Members</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your team members displayed on the website.</p>
            </div>
            <a href="{{ route('admin.hajj.team.create') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Team Member
            </a>
        </div>

        <!-- Team Grid -->
        @if($members->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" x-data="{ dragging: null }">
                @foreach($members as $member)
                    <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
                        <!-- Photo -->
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            @if($member->photo)
                                <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <svg class="h-20 w-20 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute right-2 top-2">
                            @if($member->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900">{{ $member->name }}</h3>
                            <p class="text-sm text-amber-600">{{ $member->designation }}</p>

                            @if($member->email || $member->phone)
                                <div class="mt-2 space-y-1 text-xs text-gray-500">
                                    @if($member->email)
                                        <p class="flex items-center">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $member->email }}
                                        </p>
                                    @endif
                                    @if($member->phone)
                                        <p class="flex items-center">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $member->phone }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- Social Links -->
                            @if($member->social_links && count($member->social_links) > 0)
                                <div class="mt-3 flex gap-2">
                                    @foreach($member->social_links as $platform => $url)
                                        <a href="{{ $url }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                            @switch($platform)
                                                @case('linkedin')
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                                    @break
                                                @case('twitter')
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                                    @break
                                                @case('instagram')
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                    @break
                                                @default
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 16.057v-3.057h2.994c-.059 1.143-.212 2.24-.456 3.057h-2.538zm-1-3.057v3.057h-2.538c-.244-.817-.397-1.914-.456-3.057h2.994zm0-1h-2.994c.059-1.143.212-2.24.456-3.057h2.538v3.057zm1 0v-3.057h2.538c.244.817.397 1.914.456 3.057h-2.994zm-1-4.057v-2.943h2.166c.399.618.732 1.377.967 2.262.096.358.175.728.24 1.11-.001-.143-.001.428-.001.571h-3.372zm-1 0h-3.372v-.571c.065-.382.144-.752.24-1.11.235-.885.568-1.644.967-2.262h2.165v3.943zm0 8.114v2.886h-2.143c-.386-.577-.711-1.285-.945-2.094-.097-.333-.18-.683-.25-1.052.001.087.001-.653.001-.74h3.337zm1 0h3.337v.74c-.07.369-.153.719-.25 1.052-.234.809-.559 1.517-.945 2.094h-2.142v-2.886zm3.633-1h2.091c.178.553.305 1.132.369 1.729h-2.317c.051-.564.093-1.137.093-1.729h-.236zm.236-1h-.236c0-.592-.042-1.165-.093-1.729h2.317c-.064.597-.191 1.176-.369 1.729h-1.619zm1.232-2.729h-2.024c-.183-.632-.417-1.225-.7-1.757.971.398 1.841 1.012 2.559 1.757h.165zm-8.337-1.757c-.283.532-.517 1.125-.7 1.757h-2.024c.883-.988 1.979-1.757 3.224-2.177-.281.123-.355.261-.5.42zm-3.224 7.486h2.024c.183.632.417 1.225.7 1.757-.971-.398-1.841-1.012-2.559-1.757h-.165zm8.337 1.757c.283-.532.517-1.125.7-1.757h2.024c-.883.988-1.979 1.757-3.224 2.177.281-.123.355-.261.5-.42z"/></svg>
                                            @endswitch
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-4 flex gap-2 border-t border-gray-100 pt-4">
                                <a href="{{ route('admin.hajj.team.edit', $member) }}" class="flex-1 rounded-lg bg-gray-100 px-3 py-2 text-center text-xs font-medium text-gray-700 hover:bg-gray-200">
                                    Edit
                                </a>
                                <form action="{{ route('admin.hajj.team.toggle-active', $member) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                                        {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.hajj.team.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-200">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-admin.data.empty-state
                icon="users"
                title="No team members yet"
                description="Start by adding your first team member."
                :actionUrl="route('admin.hajj.team.create')"
                actionLabel="Add Team Member"
            />
        @endif
    </div>
</x-admin.layouts.app>
