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
        $kas = Kas::with('user')
                  ->whereIn('user_id', User::where('rt_id', auth()->user()->rt_id)->pluck('id'))
                  ->orderBy('tanggal', 'desc')
                  ->get();

        return view('bendahara.kas.index', compact('kas'));
    }

    public function create()
    {
        $anggota = User::where('rt_id', auth()->user()->rt_id)->get();
        return view('bendahara.kas.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_setoran' => 'required|integer|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        Kas::create($request->all());

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Setoran kas berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Kas::findOrFail($id)->delete();

        return back()->with('success', 'Data kas berhasil dihapus!');
    }
}
