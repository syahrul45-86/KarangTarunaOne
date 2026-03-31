<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AbsensiForm;
use App\Models\Absensi;
use App\Models\ArisanTahun;
use App\Models\CatatanArisan;
use App\Models\Denda;
use App\Models\SettingRT;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        $rt_id = $admin->rt_id;

        // ==========================================
        // 1. STATISTIK ANGGOTA
        // ==========================================
        $totalAnggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
            ->count();

        $jumlahSekretaris = User::where('rt_id', $rt_id)
            ->where('role', 'sekretaris')
            ->count();

        $jumlahBendahara = User::where('rt_id', $rt_id)
            ->where('role', 'bendahara')
            ->count();

        $jumlahAnggotaBiasa = User::where('rt_id', $rt_id)
            ->where('role', 'anggota')
            ->count();

        // ==========================================
        // 2. STATISTIK ABSENSI
        // ==========================================
        // Ambil kegiatan yang dibuat oleh admin dengan rt_id yang sama
        // atau semua kegiatan (karena absensi_forms tidak punya rt_id)
        $totalKegiatan = AbsensiForm::count();

        $kegiatanBulanIni = AbsensiForm::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();

        // Kegiatan terbaru
        $kegiatanTerbaru = AbsensiForm::orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Rata-rata kehadiran (hanya untuk anggota di RT ini)
        $userIds = User::where('rt_id', $rt_id)->pluck('id');

        $totalAbsensi = Absensi::whereIn('user_id', $userIds)->count();

        $rataRataKehadiran = $totalKegiatan > 0 && $totalAnggota > 0
            ? round(($totalAbsensi / ($totalKegiatan * $totalAnggota)) * 100, 1)
            : 0;

        // ==========================================
        // 3. STATISTIK ARISAN
        // ==========================================
        // Ambil tahun arisan terbaru (tidak filter rt_id karena tabel mungkin tidak punya)
        $tahunArisanAktif = ArisanTahun::latest()->first();

        $totalBulanArisan = 0;
        $pembayaranArisanBulanIni = 0;
        $targetArisanBulanIni = 0;

        if ($tahunArisanAktif) {
            $totalBulanArisan = $tahunArisanAktif->tanggal()->count();

            // Pembayaran bulan ini
            $tanggalBulanIni = $tahunArisanAktif->tanggal()
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->first();

            if ($tanggalBulanIni) {
                $pembayaranArisanBulanIni = CatatanArisan::where('tanggal_id', $tanggalBulanIni->id)->count();
                $targetArisanBulanIni = $totalAnggota;
            }
        }

        $persentaseArisanBulanIni = $targetArisanBulanIni > 0
            ? round(($pembayaranArisanBulanIni / $targetArisanBulanIni) * 100, 1)
            : 0;

        // ==========================================
        // 4. STATISTIK DENDA
        // ==========================================
        $totalDenda = Denda::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })->sum('jumlah_denda');

        $dendaBelumBayar = Denda::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })->where('status', 'belum_bayar')->sum('jumlah_denda');

        $dendaLunas = $totalDenda - $dendaBelumBayar;

        $anggotaBermasalah = Denda::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })
        ->where('status', 'belum_bayar')
        ->distinct('user_id')
        ->count('user_id');

        // Top 5 denda tertunggak
        $dendaTertunggak = Denda::with('user')
            ->whereHas('user', function($q) use ($rt_id) {
                $q->where('rt_id', $rt_id);
            })
            ->where('status', 'belum_bayar')
            ->orderBy('jumlah_denda', 'desc')
            ->limit(5)
            ->get();

        // ==========================================
        // 5. PENGATURAN RT
        // ==========================================
        $settingRT = SettingRT::where('rt_id', $rt_id)->first();

        $iuranArisan = $settingRT->iuran_arisan ?? 0;
        $dendaAbsensi = $settingRT->denda_absensi ?? 0;
        $dendaArisan = $settingRT->denda_arisan ?? 0;

        // ==========================================
        // 6. ANGGOTA DENGAN ABSENSI RENDAH (< 50%)
        // ==========================================
        $anggotaAbsensiRendah = [];
        if ($totalKegiatan > 0) {
            $users = User::where('rt_id', $rt_id)
                ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                ->get();

            foreach ($users as $user) {
                $hadirCount = Absensi::where('user_id', $user->id)->count();

                $persentase = ($hadirCount / $totalKegiatan) * 100;

                if ($persentase < 50) {
                    $anggotaAbsensiRendah[] = [
                        'nama' => $user->name,
                        'hadir' => $hadirCount,
                        'total' => $totalKegiatan,
                        'persentase' => round($persentase, 1)
                    ];
                }
            }
        }

        // ==========================================
        // 7. TREND KEGIATAN (6 Bulan Terakhir)
        // ==========================================
        $trendKegiatan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = AbsensiForm::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->count();

            $trendKegiatan[] = [
                'bulan' => $date->format('M Y'),
                'jumlah' => $count
            ];
        }

        // ==========================================
        // 8. ANGGOTA TERBARU (5 Terakhir)
        // ==========================================
        $anggotaTerbaru = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ==========================================
        // RETURN VIEW
        // ==========================================
        return view('admin.dashboard', compact(
            'admin',
            // Statistik Anggota
            'totalAnggota',
            'jumlahSekretaris',
            'jumlahBendahara',
            'jumlahAnggotaBiasa',
            // Statistik Absensi
            'totalKegiatan',
            'kegiatanBulanIni',
            'kegiatanTerbaru',
            'rataRataKehadiran',
            // Statistik Arisan
            'tahunArisanAktif',
            'totalBulanArisan',
            'pembayaranArisanBulanIni',
            'targetArisanBulanIni',
            'persentaseArisanBulanIni',
            // Statistik Denda
            'totalDenda',
            'dendaBelumBayar',
            'dendaLunas',
            'anggotaBermasalah',
            'dendaTertunggak',
            // Pengaturan
            'settingRT',
            'iuranArisan',
            'dendaAbsensi',
            'dendaArisan',
            // Additional Data
            'anggotaAbsensiRendah',
            'trendKegiatan',
            'anggotaTerbaru'
        ));
    }
}
