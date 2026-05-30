<?php

namespace App\Http\Controllers\sekretaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\AbsensiForm;
use App\Models\Denda;
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

        return view('sekretaris.dashboard', compact(
            'sekretaris', 
            'totalAnggota', 
            'latestForm', 
            'recentAttendance', 
            'averageAttendance',
            'recentForms'
        ));
    }
}
