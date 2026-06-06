<?php

namespace App\Http\Controllers\bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\User;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index()
    {
        // Ambil data kas hanya untuk RT yang sama dengan bendahara
        $kas = Kas::with('user')
                  ->where('rt_id', auth()->user()->rt_id)
                  ->orderBy('tanggal', 'desc')
                  ->get();

        return view('bendahara.kas.index', compact('kas'));
    }

    public function create()
    {
        // Ambil daftar anggota di RT yang sama saja
        $anggota = User::where('rt_id', auth()->user()->rt_id)
                       ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                       ->orderBy('name', 'asc')
                       ->get();

        return view('bendahara.kas.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255'
        ]);

        Kas::create([
            'rt_id' => auth()->user()->rt_id,
            'user_id' => $request->user_id,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Setoran kas anggota berhasil dicatat!');
    }

    public function edit($id)
    {
        $kas = Kas::where('id', $id)->where('rt_id', auth()->user()->rt_id)->firstOrFail();
        $anggota = User::where('rt_id', auth()->user()->rt_id)
                       ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                       ->orderBy('name', 'asc')
                       ->get();

        return view('bendahara.kas.edit', compact('kas', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::where('id', $id)->where('rt_id', auth()->user()->rt_id)->firstOrFail();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $kas->update([
            'user_id' => $request->user_id,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Data kas anggota berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kas = Kas::where('id', $id)
                  ->where('rt_id', auth()->user()->rt_id)
                  ->firstOrFail();
        
        $kas->delete();

        return back()->with('success', 'Data kas berhasil dihapus!');
    }
}
