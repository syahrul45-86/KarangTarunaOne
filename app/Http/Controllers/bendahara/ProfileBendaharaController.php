<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileBendaharaController extends Controller
{

    public function index()
    {
        $bendahara = auth()->user();
        return view('bendahara.profile.index', compact('bendahara'));
    }

    // ✅ Tambahan edit()
    public function edit()
    {
        $bendahara = auth()->user();
        return view('bendahara.profile.edit', compact('bendahara'));
    }


    public function update(Request $request)
    {
        $bendahara = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $bendahara->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $bendahara->name  = $request->name;
        $bendahara->email = $request->email;

        if ($request->hasFile('image')) {
            if ($bendahara->image && Storage::disk('public')->exists($bendahara->image)) {
                Storage::disk('public')->delete($bendahara->image);
            }
            $bendahara->image = $request->file('image')->store('profile_images', 'public');
        }

        $bendahara->save();

        return redirect()->route('bendahara.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $bendahara = auth()->user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $bendahara->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $bendahara->password = Hash::make($request->password);
        $bendahara->save();

        return redirect()->route('bendahara.profile.index')->with('success', 'Password updated successfully.');
    }
}
