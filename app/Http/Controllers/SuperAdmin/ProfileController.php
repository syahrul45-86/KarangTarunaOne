<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $superadmin = auth()->user();
        return view('superadmin.profile.index', compact('superadmin'));
    }

    // ✅ Tambahan edit()
    public function edit()
    {
        $superadmin = auth()->user();
        return view('superadmin.profile.edit', compact('superadmin'));
    }

    public function update(Request $request)
    {
        $superadmin = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $superadmin->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $superadmin->name  = $request->name;
        $superadmin->email = $request->email;

        if ($request->hasFile('image')) {
            if ($superadmin->image && Storage::disk('public')->exists($superadmin->image)) {
                Storage::disk('public')->delete($superadmin->image);
            }
            $superadmin->image = $request->file('image')->store('profile_images', 'public');
        }

        $superadmin->save();

        return redirect()->route('superadmin.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $superadmin = auth()->user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $superadmin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $superadmin->password = Hash::make($request->password);
        $superadmin->save();

        return redirect()->route('superadmin.profile.index')->with('success', 'Password updated successfully.');
    }
}
