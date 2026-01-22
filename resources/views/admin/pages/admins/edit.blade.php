<x-admin.layouts.app title="Edit Admin">
    <div class="mx-auto max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Admins
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Edit Admin User</h1>
        </div>

        <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                </x-slot:header>

                <div class="space-y-4">
                    <x-admin.ui.input
                        name="name"
                        label="Full Name"
                        :value="old('name', $admin->name)"
                        required
                    />

                    <x-admin.ui.input
                        type="email"
                        name="email"
                        label="Email Address"
                        :value="old('email', $admin->email)"
                        required
                    />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-admin.ui.input
                            type="password"
                            name="password"
                            label="New Password"
                            hint="Leave blank to keep current password"
                        />

                        <x-admin.ui.input
                            type="password"
                            name="password_confirmation"
                            label="Confirm Password"
                        />
                    </div>
                </div>
            </x-admin.ui.card>

            @php
                $existingSections = $admin->assignedSections->pluck('section')->toArray();
            @endphp

            <x-admin.ui.card>
                <x-slot:header>
                    <h3 class="text-lg font-medium text-gray-900">Role & Permissions</h3>
                </x-slot:header>

                <div class="space-y-4" x-data="{ role: '{{ old('role', $admin->role->value) }}' }">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="role" value="admin" x-model="role" class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700">Admin</span>
                                <span class="ml-2 text-xs text-gray-500">- Access to assigned sections only</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="role" value="super_admin" x-model="role" class="text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700">Super Admin</span>
                                <span class="ml-2 text-xs text-gray-500">- Full system access</span>
                            </label>
                        </div>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Assignments (only for admin role) -->
                    <div x-show="role === 'admin'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700">Section Assignments</label>
                        <p class="text-xs text-gray-500">Select which sections this admin can access</p>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="sections[]" value="hajj" {{ in_array('hajj', old('sections', $existingSections)) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700">Hajj & Umrah</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="sections[]" value="tour" {{ in_array('tour', old('sections', $existingSections)) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700">Tour & Travel</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="sections[]" value="typing" {{ in_array('typing', old('sections', $existingSections)) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700">Typing Services</span>
                            </label>
                        </div>
                    </div>

                    @if($admin->id !== auth()->id())
                        <x-admin.ui.toggle
                            name="is_active"
                            label="Active"
                            :checked="old('is_active', $admin->is_active)"
                            description="Allow this user to access the admin panel"
                        />
                    @else
                        <input type="hidden" name="is_active" value="1">
                        <p class="text-sm text-gray-500">You cannot deactivate your own account.</p>
                    @endif
                </div>
            </x-admin.ui.card>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.admins.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                    Update Admin
                </button>
            </div>
        </form>
    </div>
</x-admin.layouts.app>
