<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArisanTahun;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use App\Models\User;
use PDF;

class RekapArisanController extends Controller
{
    /** INDEX — LIST TAHUN */
    public function index()
    {
        $tahuns = ArisanTahun::with('tanggal')->orderBy('tahun', 'desc')->get();
        return view('admin.rekap_arisan.index', compact('tahuns'));
    }

    /** SHOW DETAIL PER TAHUN */
    public function show($id)
    {
        // Ambil tahun
        $tahun = ArisanTahun::with('tanggal')->findOrFail($id);

        // Ambil anggota dalam RT admin
        $anggota = User::where('rt_id', auth()->user()->rt_id)->get();

        // Ambil semua catatan dalam tahun ini
        $catatan = CatatanArisan::whereIn('tanggal_id', $tahun->tanggal->pluck('id'))
                    ->get();

        // Buat array checklist [user_id][tanggal_id] = true/false
        $checklist = [];
        foreach ($catatan as $c) {
            $checklist[$c->user_id][$c->tanggal_id] = true;
        }

        // Hitung total bayar per user
        $totalBayar = [];
        foreach ($anggota as $user) {
            $totalBayar[$user->id] = isset($checklist[$user->id])
                ? count($checklist[$user->id])
                : 0;
        }

        return view('admin.rekap_arisan.show', compact(
            'tahun',
            'anggota',
            'checklist',
            'totalBayar'
        ));
    }


    /** EXPORT PDF */
   public function pdf($id)
    {
        $tahun = ArisanTahun::with('tanggal')->findOrFail($id);
        $anggota = User::where('rt_id', auth()->user()->rt_id)->get();

        $catatan = CatatanArisan::whereIn('tanggal_id', $tahun->tanggal->pluck('id'))->get();

        $checklist = [];
        foreach ($catatan as $c) {
            $checklist[$c->user_id][$c->tanggal_id] = true;
        }

        $totalBayar = [];
        foreach ($anggota as $user) {
            $totalBayar[$user->id] = isset($checklist[$user->id])
                ? count($checklist[$user->id])
                : 0;
        }

        $pdf = PDF::loadView('admin.rekap_arisan.pdf', [
            'tahun' => $tahun,
            'anggota' => $anggota,
            'checklist' => $checklist,
            'totalBayar' => $totalBayar,
        ]);

        return $pdf->download('rekap-arisan-'.$tahun->tahun.'.pdf');
    }


    /** GRAFIK PEMBAYARAN */
    public function grafik()
    {
        $tahun = ArisanTahun::with('tanggal')->latest()->first();

        if (!$tahun) {
            return back()->with('error', 'Belum ada data arisan.');
        }

        $tanggal = $tahun->tanggal;

        // Hitung jumlah sudah bayar tiap tanggal
        $grafikData = [];

        foreach ($tanggal as $tgl) {
            $countBayar = CatatanArisan::where('tanggal_id', $tgl->id)->count();

            $grafikData[] = [
                'tanggal' => $tgl->tanggal,
                'bayar' => $countBayar
            ];
        }

        return view('admin.rekap_arisan.grafik', compact('tahun', 'grafikData'));
    }

    // =========================
    // REKAP PER ANGGOTA
    // =========================
    public function perAnggota()
    {
        $tahun = ArisanTahun::with('tanggal')->latest()->first();

        if (!$tahun) {
            return back()->with('error', 'Data arisan tidak ditemukan.');
        }

        $anggota = User::where('rt_id', auth()->user()->rt_id)->get();

        $data = [];

        foreach ($anggota as $user) {
            $totalBulan = count($tahun->tanggal);
            $sudahBayar = CatatanArisan::where('user_id', $user->id)
                                       ->whereIn('tanggal_id', $tahun->tanggal->pluck('id'))
                                       ->count();

            $persen = $totalBulan > 0 ? round(($sudahBayar / $totalBulan) * 100, 1) : 0;

            $data[] = [
                'nama' => $user->name,
                'sudah' => $sudahBayar,
                'belum' => $totalBulan - $sudahBayar,
                'persentase' => $persen
            ];
        }

        return view('admin.rekap_arisan.per_anggota', compact('tahun', 'data'));
    }
}
