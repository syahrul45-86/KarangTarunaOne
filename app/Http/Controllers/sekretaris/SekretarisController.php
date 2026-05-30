<?php

namespace App\Http\Controllers\sekretaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\AbsensiForm;
use App\Models\Denda;
use App\Models\SettingRT;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use Carbon\Carbon;

class SekretarisController extends Controller
{
    public function index()
    {
        $sekretaris = auth()->user();
        $rt_id = $sekretaris->rt_id;

        // Statistics
        $totalAnggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
            ->count();

        $totalForms = AbsensiForm::count();
        
        // Latest Attendance Form
        $latestForm = AbsensiForm::orderBy('tanggal', 'desc')->first();
        $recentAttendance = 0;
        if ($latestForm) {
            $recentAttendance = Absensi::where('form_id', $latestForm->id)->count();
        }

        // Average Attendance Percentage
        $attendanceCount = Absensi::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })->count();
        
        $averageAttendance = $totalForms > 0 && $totalAnggota > 0 
            ? round(($attendanceCount / ($totalForms * $totalAnggota)) * 100, 1) 
            : 0;

        // Recent Activity
        $recentForms = AbsensiForm::orderBy('created_at', 'desc')->take(5)->get();

        // Anggota Absen Terakhir (Mereka yang tidak hadir & tidak izin pada kegiatan terakhir)
        $absenTerakhir = collect();
        if ($latestForm) {
            $hadirUserIds = Absensi::where('form_id', $latestForm->id)->pluck('user_id');
            $izinUserIds = \App\Models\IzinAbsensi::where('form_id', $latestForm->id)
                            ->where('status', 'approved')
                            ->pluck('user_id');
            
            $absenUsers = User::where('rt_id', $rt_id)
                ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                ->whereNotIn('id', $hadirUserIds)
                ->whereNotIn('id', $izinUserIds)
                ->get();

            foreach ($absenUsers as $user) {
                $absenTerakhir->push((object)[
                    'user' => $user,
                    'form' => $latestForm,
                    'created_at' => $latestForm->tanggal
                ]);
            }
        }

        // ==========================================
        // STATISTIK DENDA & TUNGGAKAN ARISAN (GLOBAL RT)
        // ==========================================
        // 1. Denda Kegiatan
        $dendaBelumBayar = Denda::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })->where('status', 'belum_bayar')->sum('jumlah_denda') ?? 0;

        // 2. Tunggakan Arisan
        $settingRT = SettingRT::where('rt_id', $rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;

        $totalNominalTunggakanArisan = 0;
        $members = User::where('rt_id', $rt_id)->whereIn('role', ['admin', 'anggota', 'sekretaris', 'bendahara'])->get();

        foreach ($members as $member) {
            $expectedForMember = ArisanTanggal::where('tanggal', '>=', $member->created_at->startOfMonth())->count();
            
            $paidForMember = CatatanArisan::where('user_id', $member->id)->count();
            $unpaidForMember = max(0, $expectedForMember - $paidForMember);
            
            $totalNominalTunggakanArisan += ($unpaidForMember * $iuranArisan);
        }

        // 3. Total Keseluruhan
        $totalDendaKeseluruhan = $dendaBelumBayar + $totalNominalTunggakanArisan;

        // ==========================================
        // 4. STATISTIK DENDA & TUNGGAKAN PRIBADI (SEKRETARIS)
        // ==========================================
        $sekretarisUser = auth()->user();
        
        $dendaBelumBayarPribadi = Denda::where('user_id', $sekretarisUser->id)
            ->where('status', 'belum_bayar')
            ->sum('jumlah_denda');

        $expectedForSekretaris = ArisanTanggal::where('tanggal', '>=', $sekretarisUser->created_at->startOfMonth())->count();
        $paidForSekretaris = CatatanArisan::where('user_id', $sekretarisUser->id)->count();
        $unpaidForSekretaris = max(0, $expectedForSekretaris - $paidForSekretaris);
        
        $tunggakanArisanPribadi = $unpaidForSekretaris * $iuranArisan;
        $totalDendaKeseluruhanPribadi = $dendaBelumBayarPribadi + $tunggakanArisanPribadi;

        return view('sekretaris.dashboard', compact(
            'sekretaris', 
            'totalAnggota', 
            'latestForm', 
            'recentAttendance', 
            'averageAttendance',
            'recentForms',
            'absenTerakhir',
            'dendaBelumBayar',
            'totalNominalTunggakanArisan',
            'totalDendaKeseluruhan',
            'dendaBelumBayarPribadi',
            'tunggakanArisanPribadi',
            'totalDendaKeseluruhanPribadi'
        ));
    }
}
