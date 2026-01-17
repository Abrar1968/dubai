<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\MediaService;
use App\Services\TeamMemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function __construct(
        protected TeamMemberService $teamMemberService,
        protected MediaService $mediaService
    ) {}

    /**
     * Display a listing of team members.
     */
    public function index(): View
    {
        $members = $this->teamMemberService->list();

        return view('admin.pages.team.index', compact('members'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create(): View
    {
        return view('admin.pages.team.create');
    }

    /**
     * Store a newly created team member.
     */
    public function store(TeamMemberRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->mediaService->uploadImage(
                $request->file('photo'),
                'team',
                ['width' => 400, 'height' => 400]
            );
        }

        // Parse social links from JSON
        if (isset($data['social_links']) && is_string($data['social_links'])) {
            $data['social_links'] = json_decode($data['social_links'], true);
        }

        $this->teamMemberService->create($data);

        return redirect()->route('admin.hajj.team.index')
            ->with('success', 'Team member created successfully.');
    }

    /**
     * Show the form for editing the specified team member.
     */
    public function edit(TeamMember $team): View
    {
        return view('admin.pages.team.edit', ['member' => $team]);
    }

    /**
     * Update the specified team member.
     */
    public function update(TeamMemberRequest $request, TeamMember $team): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($team->photo) {
                $this->mediaService->deleteImage($team->photo);
            }

            $data['photo'] = $this->mediaService->uploadImage(
                $request->file('photo'),
                'team',
                ['width' => 400, 'height' => 400]
            );
        }

        // Parse social links from JSON
        if (isset($data['social_links']) && is_string($data['social_links'])) {
            $data['social_links'] = json_decode($data['social_links'], true);
        }

        $this->teamMemberService->update($team, $data);

        return redirect()->route('admin.hajj.team.index')
            ->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy(TeamMember $team): RedirectResponse
    {
        // Delete photo
        if ($team->photo) {
            $this->mediaService->deleteImage($team->photo);
        }

        $this->teamMemberService->delete($team);

        return redirect()->route('admin.hajj.team.index')
            ->with('success', 'Team member deleted successfully.');
    }

    /**
     * Toggle the active status of a team member.
     */
    public function toggleActive(TeamMember $team): RedirectResponse
    {
        $this->teamMemberService->toggleActive($team);

        $status = $team->fresh()->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Team member {$status} successfully.");
    }

    /**
     * Reorder team members.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:team_members,id',
        ]);

        $this->teamMemberService->reorder($request->order);

        return redirect()->back()
            ->with('success', 'Team members reordered successfully.');
    }
}
