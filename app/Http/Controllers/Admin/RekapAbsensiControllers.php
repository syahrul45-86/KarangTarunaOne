<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiForm;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;   // <-- FIX

class RekapAbsensiControllers extends Controller
{
    public function index()
    {
        // Ambil semua kegiatan absensi
        $forms = AbsensiForm::orderBy('tanggal', 'desc')->get();

        return view('admin.rekap_absensi.index', compact('forms'));
    }

    public function show($id)
    {
        $form = AbsensiForm::findOrFail($id);

        // Daftar hadir
        $hadir = Absensi::where('form_id', $id)
                        ->with('user')
                        ->get();

        // Daftar semua anggota RT admin
        $users = User::where('rt_id', auth()->user()->rt_id)->get();

        // Yang tidak hadir
        $tidakHadir = $users->filter(function ($u) use ($hadir) {
            return !$hadir->contains('user_id', $u->id);
        });

        return view('admin.rekap_absensi.show', compact('form', 'hadir', 'tidakHadir'));
    }


    /* ======================================================
        EXPORT PDF
    ====================================================== */
// =====================================
    // 🔥 EXPORT PDF (HALAMAN SHOW)
    // =====================================
    public function exportPdf($id)
    {
        $form = AbsensiForm::findOrFail($id);

        $hadir = Absensi::where('form_id', $id)->with('user')->get();
        $users = User::where('rt_id', auth()->user()->rt_id)->get();
        $tidakHadir = $users->filter(function($u) use ($hadir) {
            return !$hadir->contains('user_id', $u->id);
        });

        $pdf = Pdf::loadView('admin.rekap_absensi.show_pdf', [
            'form' => $form,
            'hadir' => $hadir,
            'tidakHadir' => $tidakHadir
        ]);

        return $pdf->download('rekap-absensi-'.$form->judul.'.pdf');
    }


    /* ======================================================
        GRAFIK ABSENSI
    ====================================================== */
    public function grafik()
    {
        $forms = AbsensiForm::withCount('absensi')->get();

        return view('admin.rekap_absensi.grafik', compact('forms'));
    }


    /* ======================================================
        REKAP PER ANGGOTA
    ====================================================== */
    public function perAnggota()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->get();
        $totalKegiatan = AbsensiForm::count();

        $data = [];

        foreach ($users as $u) {

            $hadir = Absensi::where('user_id', $u->id)->count();
            $tidak = max($totalKegiatan - $hadir, 0);

            $data[] = [
                'nama' => $u->name,
                'hadir' => $hadir,
                'tidak_hadir' => $tidak,
                'persentase' => $totalKegiatan > 0 ? round(($hadir / $totalKegiatan) * 100, 1) : 0
            ];
        }

        return view('admin.rekap_absensi.per_anggota', compact('data'));
    }
}

