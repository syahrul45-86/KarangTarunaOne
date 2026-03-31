<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingRT;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingRTController extends Controller
{
    /** ============================
     *  HALAMAN INDEX SETTINGS
     * ============================ */
    public function index()
    {
        $rt_id = Auth::user()->rt_id;

        // Ambil RT yang sedang dikelola admin
        $rt = Rt::findOrFail($rt_id);

        // Ambil pengaturan jika sudah ada
        $setting = SettingRT::where('rt_id', $rt_id)->first();

        return view('admin.setting_rt.index', compact('rt', 'setting'));
    }

    /** ============================
     *  SIMPAN / UPDATE PENGATURAN
     * ============================ */
    public function save(Request $request)
    {
        $request->validate([
            'iuran_arisan'   => 'required|numeric|min:0',
            'denda_absensi'  => 'required|numeric|min:0',
            'denda_arisan'   => 'required|numeric|min:0',

        ]);

        $rt_id = Auth::user()->rt_id;

        // Cek apakah setting sudah ada
        $setting = SettingRT::where('rt_id', $rt_id)->first();

        if ($setting) {
            // UPDATE DATA
            $setting->update([
                'iuran_arisan'  => $request->iuran_arisan,
                'denda_absensi' => $request->denda_absensi,
                'denda_arisan'  => $request->denda_arisan,

            ]);
        } else {
            // BUAT BARU
            SettingRT::create([
                'rt_id'         => $rt_id,
                'iuran_arisan'  => $request->iuran_arisan,
                'denda_absensi' => $request->denda_absensi,
                'denda_arisan'  => $request->denda_arisan,

            ]);
        }

        return back()->with('success', 'Pengaturan RT berhasil disimpan.');
    }
}
