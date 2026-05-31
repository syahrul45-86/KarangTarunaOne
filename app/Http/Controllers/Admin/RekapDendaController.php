<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SettingRT;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use PDF;

class RekapDendaController extends Controller
{
    /** INDEX — RINGKASAN DENDA */
    public function index()
    {
        $users = User::where('rt_id', auth()->user()->rt_id)->get();

        $settingRT = SettingRT::where('rt_id', auth()->user()->rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;
        $allDatesCount = ArisanTanggal::count();

        // Hitung total denda per user
        $data = [];
        foreach ($users as $u) {
            $total = Denda::where('user_id', $u->id)->sum('jumlah_denda');
            $belum = Denda::where('user_id', $u->id)->where('status', '!=', 'lunas')->sum('jumlah_denda');

            $paidCount = CatatanArisan::where('user_id', $u->id)->count();
            $unpaidCount = max(0, $allDatesCount - $paidCount);
            $tunggakanArisan = $unpaidCount * $iuranArisan;

            $data[] = [
                'user' => $u,
                'belum_bayar' => $belum,
                'tunggakan_arisan' => $tunggakanArisan,
                'total_semua' => $belum + $tunggakanArisan,
            ];
        }

        return view('admin.rekap_denda.index', compact('data'));
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

}
