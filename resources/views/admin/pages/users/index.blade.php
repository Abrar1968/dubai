@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manage Users</h1>
            <p class="mt-1 text-sm text-slate-600">View and manage registered users</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Total Users</p>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">Inactive</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-600">With Bookings</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['with_bookings'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <form method="GET" class="p-4 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone..."
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500" />
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600">Status:</span>
                <a href="{{ route('admin.users.index', ['search' => $search]) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ !$currentStatus ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('admin.users.index', ['search' => $search, 'status' => 'active']) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ $currentStatus === 'active' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Active
                </a>
                <a href="{{ route('admin.users.index', ['search' => $search, 'status' => 'inactive']) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ $currentStatus === 'inactive' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Inactive
                </a>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">
                Search
            </button>
        </form>
    </div>

    {{-- Users Table --}}
    @if($users->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bookings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                                            <span class="text-sm font-semibold text-amber-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $user->phone ?? '—' }}</div>
                                <div class="text-sm text-slate-500">{{ $user->nationality ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ $user->bookings_count }} booking(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg" title="View">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-{{ $user->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $user->is_active ? 'red' : 'green' }}-50 rounded-lg" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($user->is_active)
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-slate-900">No users found</h3>
            <p class="mt-1 text-sm text-slate-500">Users will appear here when they register.</p>
        </div>
    @endif
@endsection
