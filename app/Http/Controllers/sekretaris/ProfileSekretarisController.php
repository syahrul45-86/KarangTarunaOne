<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileSekretarisController extends Controller
{

    public function index()
    {
        $sekretaris= auth()->user();
        return view('sekretaris.profile.index', compact('sekretaris'));
    }

    // ✅ Tambahan edit()
    public function edit()
    {
        $sekretaris = auth()->user();
        return view('sekretaris.profile.edit', compact('sekretaris'));
    }


    public function update(Request $request)
    {
        $sekretaris = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $sekretaris->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $sekretaris->name  = $request->name;
        $sekretaris->email = $request->email;

        if ($request->hasFile('image')) {
            if ($sekretaris->image && Storage::disk('public')->exists($sekretaris->image)) {
                Storage::disk('public')->delete($sekretaris->image);
            }
            $sekretaris->image = $request->file('image')->store('profile_images', 'public');
        }

        $sekretaris->save();

        return redirect()->route('sekretaris.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $sekretaris = auth()->user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $sekretaris->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $sekretaris->password = Hash::make($request->password);
        $sekretaris->save();

        return redirect()->route('sekretaris.profile.index')->with('success', 'Password updated successfully.');
    }
}
