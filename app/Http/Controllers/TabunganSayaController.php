<?php

namespace App\Http\Controllers;

use App\Models\Tabungan;
use Illuminate\Support\Facades\Auth;

class TabunganSayaController extends Controller
{
    /**
     * Tampilkan saldo & riwayat tabungan user yang sedang login,
     * difilter berdasarkan rt_id user tersebut.
     */
    public function index()
    {
        $user = Auth::user();

        $riwayat = Tabungan::where('user_id', $user->id)
            ->where('rt_id', $user->rt_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalSetoran  = $riwayat->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalPenarikan = $riwayat->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $saldo          = $totalSetoran - $totalPenarikan;

        return view('shared.tabungan_saya', compact('riwayat', 'totalSetoran', 'totalPenarikan', 'saldo'));
    }
}
