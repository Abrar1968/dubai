@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-4">User Information</h2>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('phone') border-red-500 @enderror"
                        placeholder="+971 50 123 4567">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Nationality --}}
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-slate-700 mb-1">Nationality</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $user->nationality) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('nationality') border-red-500 @enderror"
                            placeholder="e.g., United Arab Emirates">
                        @error('nationality')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Passport Number --}}
                    <div>
                        <label for="passport_number" class="block text-sm font-medium text-slate-700 mb-1">Passport Number</label>
                        <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number', $user->passport_number) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('passport_number') border-red-500 @enderror"
                            placeholder="AB1234567">
                        @error('passport_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Date of Birth --}}
                <div class="sm:w-1/2">
                    <label for="date_of_birth" class="block text-sm font-medium text-slate-700 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth"
                        value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Account Status --}}
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Account Status</h3>
                    <div class="flex items-start gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500 mt-0.5">
                        <div>
                            <label for="is_active" class="text-sm font-medium text-slate-700">Account Active</label>
                            <p class="text-sm text-slate-500">Deactivating the account will prevent the user from logging in.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between rounded-b-xl">
                <button type="button" onclick="document.getElementById('delete-modal').showModal()" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    Delete User
                </button>
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.show', $user) }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Delete Modal --}}
    <dialog id="delete-modal" class="rounded-xl shadow-xl p-0 backdrop:bg-slate-900/50">
        <div class="p-6 max-w-md">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Delete User</h3>
                    <p class="text-sm text-slate-500">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button onclick="document.getElementById('delete-modal').close()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                    Cancel
                </button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </dialog>
@endsection
