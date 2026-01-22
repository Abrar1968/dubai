<x-admin.layouts.app title="Admin Users">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Users</h1>
                <p class="mt-1 text-sm text-gray-500">Manage system administrators and their permissions.</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Admin
            </a>
        </div>

        <!-- Role Filters -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.admins.index') }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ !$currentRole ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All Admins
            </a>
            <a href="{{ route('admin.admins.index', ['role' => 'super_admin']) }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $currentRole === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Super Admins
            </a>
            <a href="{{ route('admin.admins.index', ['role' => 'admin']) }}"
               class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $currentRole === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Admins
            </a>
        </div>

        <!-- Admin List -->
        @if($admins->count() > 0)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sections</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Created</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($admins as $admin)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                                                <span class="text-sm font-medium text-amber-600">{{ substr($admin->name, 0, 2) }}</span>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($admin->role->value === 'super_admin')
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                            Super Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($admin->role->value === 'super_admin')
                                        <span class="text-xs text-gray-500">All sections</span>
                                    @elseif($admin->assignedSections->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($admin->assignedSections as $section)
                                                <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                                    {{ ucfirst($section->section) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">No sections assigned</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($admin->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $admin->created_at->format('M j, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="text-amber-600 hover:text-amber-900">Edit</a>
                                        @if($admin->id !== auth()->id())
                                            <form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-gray-600 hover:text-gray-900">
                                                    {{ $admin->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('Delete this admin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">(You)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <x-admin.data.empty-state
                icon="users"
                title="No admin users"
                description="Start by adding an admin user."
                :actionUrl="route('admin.admins.create')"
                actionLabel="Add Admin"
            />
        @endif
    </div>
</x-admin.layouts.app>
