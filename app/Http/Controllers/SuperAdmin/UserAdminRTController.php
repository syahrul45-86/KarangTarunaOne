<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rt;
use Illuminate\Support\Facades\Hash;

class UserAdminRTController extends Controller
{
    /**
     * Tampilkan halaman daftar admin & modal tambah admin.
     */
    public function index()
    {
        // daftar admin untuk tabel
        $users = User::with('rt')->where('role', 'admin')->get();

        // ambil array id RT yang sudah punya admin
        $rtSudahAdaAdmin = User::where('role', 'admin')->pluck('rt_id')->filter()->toArray();

        // ambil RT yang belum punya admin
        $rt = Rt::whereNotIn('id', $rtSudahAdaAdmin)->get();

        return view('superadmin.tambah-user.index', compact('users', 'rt'));
    }

    /**
     * Simpan admin baru untuk RT yg dipilih.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rt_id'    => 'required|exists:rts,id',
        ]);

        // Safety check — pastikan RT belum punya admin
        $exists = User::where('role', 'admin')->where('rt_id', $request->rt_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'RT ini sudah memiliki admin.');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'rt_id'    => $request->rt_id,
        ]);

        return redirect()->route('superadmin.adminrt.index')->with('success', 'Admin RT berhasil ditambahkan!');
    }

    /**
     * Tampilkan data admin untuk diedit (jika kamu pakai modal edit AJAX).
     */
    // public function edit($id)
    // {
    //     $user = User::findOrFail($id);
    //     $rt = Rt::all();
    //     return response()->json(compact('user', 'rt'));
    // }

    // /**
    //  * Update data admin RT.
    //  */
    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $request->validate([
    //         'name'  => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'rt_id' => 'required|exists:rts,id',
    //     ]);

    //     $user->update([
    //         'name'  => $request->name,
    //         'email' => $request->email,
    //         'rt_id' => $request->rt_id,
    //     ]);

    //     return redirect()->route('superadmin.users.index')->with('success', 'Data admin RT berhasil diperbarui!');
    // }

    /**
     * Hapus admin RT.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Data ini bukan admin RT.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Admin RT berhasil dihapus.');
    }
}
