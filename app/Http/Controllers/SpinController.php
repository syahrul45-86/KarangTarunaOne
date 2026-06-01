<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SpinAnggota;

class SpinController extends Controller
{
    /**
     * Tampilkan halaman spin dengan data anggota yang sudah tersimpan di DB.
     */
    public function index()
    {
        $rtId = auth()->user()->rt_id;

        // Semua anggota RT untuk dropdown
        $anggota = User::where('rt_id', $rtId)->get();

        // Anggota yang sudah disimpan di roda untuk RT ini
        $savedMembers = SpinAnggota::with('user')
            ->where('rt_id', $rtId)
            ->get()
            ->map(fn($item) => [
                'id'   => $item->user_id,
                'name' => $item->user->name,
            ])
            ->values()
            ->toArray();

        return view('sekretaris.spin.index', compact('anggota', 'savedMembers'));
    }

    /**
     * Simpan/sync daftar anggota roda ke database.
     * Menggantikan semua data lama milik RT ini.
     */
    public function save(Request $request)
    {
        $request->validate([
            'members'   => 'nullable|array',
            'members.*.id'   => 'required|exists:users,id',
            'members.*.name' => 'required|string',
        ]);

        try {
            $rtId   = auth()->user()->rt_id;
            $members = $request->members ?? [];

            // Hapus semua data lama, ganti dengan yang baru (sync)
            SpinAnggota::where('rt_id', $rtId)->delete();

            foreach ($members as $member) {
                SpinAnggota::create([
                    'rt_id'   => $rtId,
                    'user_id' => $member['id'],
                ]);
            }

            return response()->json([
                'success' => true,
                'count'   => count($members),
                'message' => 'Data berhasil disimpan!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
