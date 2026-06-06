<?php

namespace App\Http\Controllers\bendahara;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil riwayat tabungan sesuai RT bendahara
        $tabungan = Tabungan::with('user')
            ->where('rt_id', $user->rt_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Hitung rekap saldo tiap anggota
        $rekapSaldo = User::where('rt_id', $user->rt_id)
            ->whereIn('role', ['anggota', 'bendahara', 'sekretaris', 'admin'])
            ->with(['tabungan' => function($query) {
                $query->select('user_id', 'jenis_transaksi', 'nominal');
            }])
            ->get()
            ->map(function ($anggota) {
                $setoran = $anggota->tabungan->where('jenis_transaksi', 'setoran')->sum('nominal');
                $penarikan = $anggota->tabungan->where('jenis_transaksi', 'penarikan')->sum('nominal');
                $anggota->saldo = $setoran - $penarikan;
                return $anggota;
            });

        return view('bendahara.tabungan.index', compact('tabungan', 'rekapSaldo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $anggota = User::where('rt_id', $user->rt_id)->whereIn('role', ['anggota', 'bendahara', 'sekretaris', 'admin'])->get();
        return view('bendahara.tabungan.create', compact('anggota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_transaksi' => 'required|in:setoran,penarikan',
            'nominal' => 'required|numeric|min:100',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Jika penarikan, cek saldo cukup atau tidak
        if ($request->jenis_transaksi == 'penarikan') {
            $setoran = Tabungan::where('user_id', $request->user_id)->where('jenis_transaksi', 'setoran')->sum('nominal');
            $penarikan = Tabungan::where('user_id', $request->user_id)->where('jenis_transaksi', 'penarikan')->sum('nominal');
            $saldoSaatIni = $setoran - $penarikan;

            if ($request->nominal > $saldoSaatIni) {
                return redirect()->back()->withInput()->with('error', 'Saldo tabungan tidak mencukupi untuk penarikan ini. Sisa saldo: Rp ' . number_format($saldoSaatIni, 0, ',', '.'));
            }
        }

        Tabungan::create([
            'rt_id' => $user->rt_id,
            'user_id' => $request->user_id,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('bendahara.tabungan.index')->with('success', 'Transaksi tabungan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $tabungan = Tabungan::where('rt_id', $user->rt_id)->findOrFail($id);
        $anggota = User::where('rt_id', $user->rt_id)->whereIn('role', ['anggota', 'bendahara', 'sekretaris', 'admin'])->get();

        return view('bendahara.tabungan.edit', compact('tabungan', 'anggota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_transaksi' => 'required|in:setoran,penarikan',
            'nominal' => 'required|numeric|min:100',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $tabungan = Tabungan::where('rt_id', $user->rt_id)->findOrFail($id);

        // Validasi penarikan jika nominal diubah menjadi lebih besar dari saldo
        if ($request->jenis_transaksi == 'penarikan') {
            $setoran = Tabungan::where('user_id', $request->user_id)
                ->where('jenis_transaksi', 'setoran')
                ->where('id', '!=', $tabungan->id)
                ->sum('nominal');
            $penarikan = Tabungan::where('user_id', $request->user_id)
                ->where('jenis_transaksi', 'penarikan')
                ->where('id', '!=', $tabungan->id)
                ->sum('nominal');
            $saldoTanpaTransaksiIni = $setoran - $penarikan;

            if ($request->nominal > $saldoTanpaTransaksiIni) {
                return redirect()->back()->withInput()->with('error', 'Saldo tidak mencukupi untuk penarikan ini. Sisa saldo di luar transaksi ini: Rp ' . number_format($saldoTanpaTransaksiIni, 0, ',', '.'));
            }
        }

        $tabungan->update([
            'user_id' => $request->user_id,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('bendahara.tabungan.index')->with('success', 'Data tabungan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $tabungan = Tabungan::where('rt_id', $user->rt_id)->findOrFail($id);
        $tabungan->delete();

        return redirect()->route('bendahara.tabungan.index')->with('success', 'Data tabungan berhasil dihapus!');
    }
}
