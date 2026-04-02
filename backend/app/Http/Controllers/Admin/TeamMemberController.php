<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::with('parent')
            ->orderBy('level')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.tentang-kami.team-members.index', compact('teamMembers'));
    }

    public function create()
    {
        $parents = TeamMember::orderBy('level')->orderBy('sort_order')->get();

        return view('admin.tentang-kami.team-members.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:team_members,id',
            'level' => 'required|integer|min:0|max:3',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'position', 'parent_id', 'level', 'sort_order']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team-members', 'public');
        }

        TeamMember::create($data);

        FrontendCacheService::revalidate(['team-members', 'landing-page']);
        Cache::forget('api.landing_page_data_v2');

        return redirect()->route('admin.tentang-kami.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $teamMember)
    {
        $parents = TeamMember::where('id', '!=', $teamMember->id)
            ->orderBy('level')
            ->orderBy('sort_order')
            ->get();

        return view('admin.tentang-kami.team-members.edit', compact('teamMember', 'parents'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:team_members,id',
            'level' => 'required|integer|min:0|max:3',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'position', 'parent_id', 'level', 'sort_order']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($teamMember->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($teamMember->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teamMember->photo);
            }
            $data['photo'] = $request->file('photo')->store('team-members', 'public');
        }

        $teamMember->update($data);

        FrontendCacheService::revalidate(['team-members', 'landing-page']);
        Cache::forget('api.landing_page_data_v2');

        return redirect()->route('admin.tentang-kami.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($teamMember->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($teamMember->photo);
        }

        FrontendCacheService::revalidate(['team-members', 'landing-page']);
        Cache::forget('api.landing_page_data_v2');

        $teamMember->delete();

        return redirect()->route('admin.tentang-kami.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
