<x-admin.layouts.user title="Profile">
    <div class="mx-auto max-w-3xl space-y-6">
        <!-- Profile Information -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
            </div>

            <form action="{{ route('user.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
                <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
            </div>

            <form action="{{ route('user.profile.password') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.user>
