<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rt;

class RtController extends Controller
{
    public function index()
    {
        $rts = Rt::all();
        return view('superadmin.rt.index', compact('rts'));
    }

    // 🟢 Menyimpan data RT baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_rt' => 'required|string|max:50',
            'rw' => 'required|string|max:50',
        ]);

        Rt::create([
            'nama_rt' => $request->nama_rt,
            'rw' => $request->rw,
        ]);

        return redirect()->route('superadmin.rt.index')->with('success', 'Data RT berhasil ditambahkan.');
    }

    // 🟢 Ambil data RT untuk edit (OPSIONAL - jika mau pakai AJAX)
    public function edit($id)
    {
        $rt = Rt::findOrFail($id);
        return response()->json($rt); // Return JSON untuk AJAX
    }

    // 🟢 Update data RT
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_rt' => 'required|string|max:50',
            'rw' => 'required|string|max:50',
        ]);

        $rt = Rt::findOrFail($id);
        $rt->update([
            'nama_rt' => $request->nama_rt,
            'rw' => $request->rw,
        ]);

        return redirect()->route('superadmin.rt.index')->with('success', 'Data RT berhasil diperbarui.');
    }

    // 🟢 Hapus data RT
    public function destroy($id)
    {
        $rt = Rt::findOrFail($id);
        $rt->delete();

        return redirect()->route('superadmin.rt.index')->with('success', 'Data RT berhasil dihapus.');
    }
}
