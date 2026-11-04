<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::when(request('search'), fn($query, $search) => $query->where('nama', 'like', "%{$search}%"))->paginate(10);

        return view('members.index', compact('members'));
    }

    public function show(string $id)
    {
        $member = Member::with(['loans.loanItems.book', 'loans.user'])->findOrFail($id);

        return view('members.show', compact('member'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {

        $validated = $request->validated();


        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', "Member \"{$validated['nama']}\" berhasil ditambahkan.");
    }

    public function edit(string $id)
    {
        $member = Member::findOrFail($id);

        return view('members.edit', compact('member'));
    }

    public function update(Request $request, string $id)
    {
        $Member = Member::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|numeric|unique:members,nim,' . $Member->id,
            'email' => 'required|email|unique:members,email,' . $Member->id,
            'nomor_telepon' => 'required|numeric',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $Member->update($validated);

        return redirect()->route('members.index')
            ->with('success', "Member \"{$validated['nama']}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $Member = Member::findOrFail($id);
        $Member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member berhasil dihapus.');
    }
}
