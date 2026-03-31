<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class RekapDendaController extends Controller
{
    /** INDEX — RINGKASAN DENDA */
    public function index()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->get();

        // Hitung total denda per user
        $data = [];
        foreach ($users as $u) {
            $total = Denda::where('user_id', $u->id)->sum('jumlah_denda');
            $belum = Denda::where('user_id', $u->id)->where('status', 'belum_bayar')->sum('jumlah_denda');

            $data[] = [
                'user' => $u,
                'total' => $total,
                'belum_bayar' => $belum,
            ];
        }

        return view('admin.rekap_denda.index', compact('data'));
    }

    /** DETAIL DENDA PER ANGGOTA */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $denda = Denda::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        return view('admin.rekap_denda.show', compact('user', 'denda'));
    }

    /** GRAFIK DENDA */
    public function grafik()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->get();

        $grafikData = [];

        foreach ($users as $u) {
            $total = Denda::where('user_id', $u->id)->sum('jumlah_denda');
            $grafikData[] = [
                'nama' => $u->name,
                'total' => $total
            ];
        }

        return view('admin.rekap_denda.grafik', compact('grafikData'));
    }

    /** REKAP PER ANGGOTA */
    public function perAnggota()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->get();
        $data = [];

        foreach ($users as $u) {
            $totalJumlah = Denda::where('user_id', $u->id)->sum('jumlah_denda');
            $totalKasus = Denda::where('user_id', $u->id)->count();
            $belumBayar = Denda::where('user_id', $u->id)->where('status', 'belum_bayar')->count();

            $data[] = [
                'nama' => $u->name,
                'kasus' => $totalKasus,
                'belum' => $belumBayar,
                'total_denda' => $totalJumlah
            ];
        }

        return view('admin.rekap_denda.per_anggota', compact('data'));
    }
}
