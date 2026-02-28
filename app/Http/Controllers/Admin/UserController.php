<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of regular users.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', UserRole::USER)
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount('bookings')->paginate(15);

        // Stats
        $stats = [
            'total' => User::where('role', UserRole::USER)->count(),
            'active' => User::where('role', UserRole::USER)->where('is_active', true)->count(),
            'inactive' => User::where('role', UserRole::USER)->where('is_active', false)->count(),
            'with_bookings' => User::where('role', UserRole::USER)->has('bookings')->count(),
        ];

        return view('admin.pages.users.index', [
            'users' => $users,
            'stats' => $stats,
            'search' => $request->search,
            'currentStatus' => $request->status,
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        // Ensure we're only viewing regular users
        if ($user->role !== UserRole::USER) {
            abort(404);
        }

        $user->load(['bookings.package', 'bookings.travelers']);

        return view('admin.pages.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        if ($user->role !== UserRole::USER) {
            abort(404);
        }

        return view('admin.pages.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== UserRole::USER) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->role !== UserRole::USER) {
            abort(404);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role !== UserRole::USER) {
            abort(404);
        }

        // Soft delete to preserve booking history
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
