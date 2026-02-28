<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\User;
use App\Services\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserService $adminUserService
    ) {}

    /**
     * Display a listing of admin users.
     */
    public function index(Request $request): View
    {
        $role = $request->get('role');
        $admins = $this->adminUserService->list($role ? ['role' => $role] : []);

        return view('admin.pages.admins.index', [
            'admins' => $admins,
            'currentRole' => $role,
        ]);
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        return view('admin.pages.admins.create');
    }

    /**
     * Store a newly created admin user.
     */
    public function store(AdminUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $sections = $data['sections'] ?? [];
        unset($data['sections']);

        $this->adminUserService->create($data, $sections);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $admin): View
    {
        $admin->load('assignedSections');

        return view('admin.pages.admins.edit', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update the specified admin user.
     */
    public function update(AdminUserRequest $request, User $admin): RedirectResponse
    {
        $data = $request->validated();
        $sections = $data['sections'] ?? [];
        unset($data['sections']);

        $this->adminUserService->update($admin, $data, $sections);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user updated successfully.');
    }

    /**
     * Remove the specified admin user.
     */
    public function destroy(User $admin): RedirectResponse
    {
        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $this->adminUserService->delete($admin);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(User $admin): RedirectResponse
    {
        // Prevent deactivating yourself
        if ($admin->id === auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        $this->adminUserService->toggleActive($admin);

        return redirect()
            ->back()
            ->with('success', 'Admin status updated.');
    }
}
