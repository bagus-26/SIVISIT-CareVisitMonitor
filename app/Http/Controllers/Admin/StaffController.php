<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        try {
            $staff = User::where('role', 'petugas')->paginate(10);
        } catch (\Exception $e) {
            $staff = User::paginate(10);
        }
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:users,nip',
            'phone' => 'required|string',
            'location' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'petugas';
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'nip' => 'required|string|unique:users,nip,' . $staff->id,
            'phone' => 'required|string',
            'location' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Petugas berhasil diperbarui');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Petugas berhasil dihapus');
    }
}
