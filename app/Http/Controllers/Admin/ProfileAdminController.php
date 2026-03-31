<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Rt;

class ProfileAdminController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        return view('admin.profile.index', compact('admin'));
    }

    public function edit()
    {
        $admin = auth()->user();
        // ambil daftar RT untuk dropdown
        $rts = Rt::orderBy('rw')->orderBy('nama_rt')->get();
        return view('admin.profile.edit', compact('admin','rts'));
    }

    public function update(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'rt_id' => 'nullable|integer|exists:rts,id', // rt_id boleh null
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name  = $request->name;
        $admin->email = $request->email;
        $admin->rt_id = $request->rt_id; // simpan id rt

        if ($request->hasFile('image')) {
            if ($admin->image && Storage::disk('public')->exists($admin->image)) {
                Storage::disk('public')->delete($admin->image);
            }
            $admin->image = $request->file('image')->store('profile_images', 'public');
        }

        $admin->save();

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.profile.index')->with('success', 'Password updated successfully.');
    }
}
