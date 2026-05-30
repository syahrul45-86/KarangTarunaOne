<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiForm;
use App\Models\ArisanTahun;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use App\Models\Denda;
use App\Models\SettingRT;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = auth()->user();
        $rt_id   = $anggota->rt_id;

        // ============================================
        // 1. STATISTIK ABSENSI PRIBADI
        // ============================================
        $totalKegiatan  = AbsensiForm::where('rt_id', $rt_id)->count();
        $totalHadir     = Absensi::where('user_id', $anggota->id)->count();
        $totalIzin      = \App\Models\IzinAbsensi::where('user_id', $anggota->id)->where('status', 'approved')->count();
        $totalTidakHadir = max($totalKegiatan - $totalHadir - $totalIzin, 0);
        $persentaseHadir = $totalKegiatan > 0
            ? round((($totalHadir + $totalIzin) / $totalKegiatan) * 100, 1)
            : 0;

        // Kegiatan terbaru (pagination) & status kehadiran/izin
        $kegiatanTerbaru = AbsensiForm::where('rt_id', $rt_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(5);
            
        $kegiatanTerbaru->getCollection()->transform(function ($form) use ($anggota) {
            $form->sudah_hadir = Absensi::where('form_id', $form->id)
                ->where('user_id', $anggota->id)
                ->exists();
            
            $form->izin = \App\Models\IzinAbsensi::where('form_id', $form->id)
                ->where('user_id', $anggota->id)
                ->first();
                
            return $form;
        });

        // ============================================
        // 2. STATISTIK ARISAN PRIBADI
        // ============================================
        $tahunArisan     = ArisanTahun::latest()->first();
        $totalBulanArisan = 0;
        $sudahBayarArisan = 0;
        $belumBayarArisan = 0;
        $persentaseBayarArisan = 0;

        if ($tahunArisan) {
            $totalBulanArisan = $tahunArisan->tanggal()->count();
            $sudahBayarArisan = CatatanArisan::where('user_id', $anggota->id)
                ->whereIn('tanggal_id', $tahunArisan->tanggal()->pluck('id'))
                ->count();
            $belumBayarArisan = $totalBulanArisan - $sudahBayarArisan;
            $persentaseBayarArisan = $totalBulanArisan > 0
                ? round(($sudahBayarArisan / $totalBulanArisan) * 100, 1)
                : 0;
        }

        // ============================================
        // 4. SETTING RT (Pindah ke atas agar iuran bisa dipakai untuk hitung tunggakan)
        // ============================================
        $settingRT   = SettingRT::where('rt_id', $rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;

        // Hitung Tunggakan Arisan (Seluruh tahun, yang belum dibayar, sejak user dibuat)
        $allDates = ArisanTanggal::where('tanggal', '>=', $anggota->created_at->startOfMonth())
          ->orderBy('tanggal', 'desc')->get();
          
        $paidDatesIds = CatatanArisan::where('user_id', $anggota->id)->pluck('tanggal_id')->toArray();
        
        $tunggakanArisanList = $allDates->filter(function($date) use ($paidDatesIds) {
            return !in_array($date->id, $paidDatesIds);
        })->take(5); // Ambil 5 tunggakan terbaru

        $totalBulanTunggakan = max(0, $allDates->count() - count($paidDatesIds));
        $totalNominalTunggakanArisan = $totalBulanTunggakan * $iuranArisan;

        // ============================================
        // 3. STATISTIK DENDA PRIBADI
        // ============================================
        $totalDenda    = Denda::where('user_id', $anggota->id)->sum('jumlah_denda');
        $dendaBelumBayar = Denda::where('user_id', $anggota->id)
            ->where('status', 'belum_bayar')
            ->sum('jumlah_denda');
        $dendaLunas    = $totalDenda - $dendaBelumBayar;

        $riwayatDenda = Denda::where('user_id', $anggota->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // Setting sudah dipindah ke atas

        // ============================================
        // 5. TOTAL KESELURUHAN (DENDA + TUNGGAKAN ARISAN PRIBADI)
        // ============================================
        $totalDendaKeseluruhan = $dendaBelumBayar + $totalNominalTunggakanArisan;

        return view('anggota.dashboard', compact(
            'anggota',
            // Absensi
            'totalKegiatan',
            'totalHadir',
            'totalTidakHadir',
            'persentaseHadir',
            'kegiatanTerbaru',
            // Arisan
            'tahunArisan',
            'totalBulanArisan',
            'sudahBayarArisan',
            'belumBayarArisan',
            'persentaseBayarArisan',
            // Denda
            'totalDenda',
            'dendaBelumBayar',
            'dendaLunas',
            'riwayatDenda',
            // Tunggakan Arisan
            'tunggakanArisanList',
            'totalNominalTunggakanArisan',
            // Total Keseluruhan
            'totalDendaKeseluruhan',
            // Setting
            'iuranArisan'
        ));
    }
}

