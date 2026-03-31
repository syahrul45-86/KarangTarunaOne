<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class ProfileAnggotaController extends Controller
{
    public function index()
    {
        $anggota = auth()->user();
        return view('anggota.profile.index', compact('anggota'));
    }

    // ✅ Tambahan edit()
    public function edit()
    {
        $anggota = auth()->user();
        return view('anggota.profile.edit', compact('anggota'));
    }


    public function update(Request $request)
    {
        $anggota = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $anggota->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $anggota->name  = $request->name;
        $anggota->email = $request->email;

        if ($request->hasFile('image')) {
            if ($anggota->image && Storage::disk('public')->exists($anggota->image)) {
                Storage::disk('public')->delete($anggota->image);
            }
            $anggota->image = $request->file('image')->store('profile_images', 'public');
        }

        $anggota->save();

        return redirect()->route('anggota.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $anggota = auth()->user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $anggota->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $anggota->password = Hash::make($request->password);
        $anggota->save();

        return redirect()->route('anggota.profile.index')->with('success', 'Password updated successfully.');
    }
}

