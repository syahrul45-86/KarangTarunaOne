<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // =========================
    // 1. DENDA KESELURUHAN
    // =========================
    public function index(Request $request)
    {
        $bendahara = auth()->user();
        $search = $request->search;

        $denda = Denda::with('user')
            ->whereHas('user', function ($q) use ($bendahara) {
                $q->where('rt_id', $bendahara->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bendahara.denda.index', compact('denda', 'search'));
    }

    // =========================
    // 2. DENDA ABSENSI
    // =========================
    public function absensi(Request $request)
    {
        $bendahara = auth()->user();
        $search = $request->search;

        $denda = Denda::with('user')
            ->whereIn('jenis', ['absensi', 'manual'])

            ->whereHas('user', function ($q) use ($bendahara) {
                $q->where('rt_id', $bendahara->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->get();

        return view('bendahara.denda.denda-absensi.index', compact('denda', 'search'));
    }

    // =========================
    // 3. DENDA KEGIATAN
    // =========================
    public function kegiatan(Request $request)
    {
        $bendahara = auth()->user();
         $users = User::all();
        $search = $request->search;

        $denda = Denda::with('user')
            ->where('jenis', 'kegiatan')
            ->whereHas('user', function ($q) use ($bendahara) {
                $q->where('rt_id', $bendahara->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->get();

        return view('bendahara.denda.denda-kegiatan.index', compact('denda', 'search', 'users'));
    }
    public function storeKegiatan(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'jumlah_denda' => 'required|numeric|min:0',
    ]);

    $existing = Denda::where('user_id', $request->user_id)
        ->where('status', 'belum_bayar')
        ->first();

    if ($existing) {
        $existing->jumlah_denda += $request->jumlah_denda;
        $existing->save();
    } else {
        Denda::create([
            'user_id' => $request->user_id,
            'jenis' => 'kegiatan',
            'jumlah_denda' => $request->jumlah_denda,
            'status' => 'belum_bayar',
        ]);
    }

    return back()->with('success', 'Denda kegiatan ditambahkan!');
}


    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $bendahara = auth()->user();
        $users = User::where('rt_id', $bendahara->rt_id)->get();

        return view('bendahara.denda.denda-absensi.create', compact('users'));

    }

    // =========================
    // STORE
    // =========================
public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'jumlah_denda' => 'required|numeric|min:0',
    ]);

    // cari denda aktif apapun jenisnya
    $existing = Denda::where('user_id', $request->user_id)
        ->where('status', 'belum_bayar')
        ->first();

    if ($existing) {
        // tambah nominal saja
        $existing->jumlah_denda += $request->jumlah_denda;
        $existing->save();
    } else {
        // baru buat manual
        Denda::create([
            'user_id' => $request->user_id,
            'jenis' => 'manual',
            'jumlah_denda' => $request->jumlah_denda,
            'status' => 'belum_bayar',
        ]);
    }

    return redirect()->route('bendahara.denda.absensi')
        ->with('success', 'Denda manual berhasil ditambahkan!');
}



    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        Denda::findOrFail($id)->delete();

        return back()->with('success', 'Denda dihapus.');
    }


    // =========================
    // 5. UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);

        $request->validate([
            'user_id' => 'required',
            'jumlah_denda' => 'required|numeric|min:0',
            'status' => 'required|in:belum_bayar,lunas',
        ]);

        // Update data
        $denda->update([
            'user_id' => $request->user_id,
            'jumlah_denda' => $request->jumlah_denda,
            'status' => $request->status,
        ]);

        return redirect()->route('bendahara.denda.denda-absensi.index')->with('success', 'Denda berhasil diperbarui.');
    }

}
