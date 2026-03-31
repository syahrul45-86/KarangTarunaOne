<?php

namespace App\Http\Controllers\sekretaris;

use App\Http\Controllers\Controller;
use App\Models\ArisanTahun;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use App\Models\User;
use Illuminate\Http\Request;

class CatatanArisanController extends Controller
{
    /* =============================
        HALAMAN INDEX
    ==============================*/
    public function index()
    {
        $tahuns = ArisanTahun::with('tanggal')->get();
        return view('sekretaris.catatan_arisan.index', compact('tahuns'));
    }

    /* =============================
        HALAMAN INPUT TAHUN
    ==============================*/
    public function create()
    {
        $tahuns = ArisanTahun::all();
        return view('sekretaris.catatan_arisan.create_tahun', compact('tahuns'));
    }

    /* =============================
        SIMPAN TAHUN
    ==============================*/
    public function storeTahun(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|unique:arisan_tahun,tahun'
        ]);

        ArisanTahun::create([
            'tahun' => $request->tahun
        ]);

        return redirect()
            ->route('sekretaris.catatan.index')
            ->with('success', 'Tahun arisan berhasil ditambahkan!');
    }

    /* =============================
        SIMPAN TANGGAL
    ==============================*/
    public function storeTanggal(Request $request)
    {
        $request->validate([
            'arisan_tahun_id' => 'required|exists:arisan_tahun,id',
            'tanggal'  => 'required|date'
        ]);

        ArisanTanggal::create([
            'arisan_tahun_id' => $request->arisan_tahun_id,
            'tanggal'  => $request->tanggal
        ]);

        return back()->with('success', 'Tanggal arisan berhasil ditambahkan!');
    }

    /* =============================
        TAMPILKAN DETAIL TAHUN
    ==============================*/
    public function showTahun(Request $request)
    {
        $tahun = ArisanTahun::with('tanggal')->findOrFail($request->tahun_id);

        // Ambil anggota sesuai RT sekretaris yang login
        $anggota = User::where('rt_id', auth()->user()->rt_id)->get();

        return view('sekretaris.catatan_arisan.show_tahun', compact('tahun', 'anggota'));
    }

    /* =============================
        TOGGLE CHECKLIST PEMBAYARAN
    ==============================*/
    public function toggleChecklist(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'tanggal_id' => 'required|exists:arisan_tanggal,id', // FIXED
        ]);

        // cek apakah sudah ada catatan
        $catatan = CatatanArisan::where('user_id', $request->user_id)
                                 ->where('tanggal_id', $request->tanggal_id)
                                 ->first();

        if ($catatan) {
            $catatan->delete();
            return response()->json([
                'status' => 'deleted',
                'message' => 'Checklist dihapus!'
            ]);
        }

        CatatanArisan::create([
            'user_id'     => $request->user_id,
            'tanggal_id'  => $request->tanggal_id,
            'sudah_bayar' => true
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Checklist ditambahkan!'
        ]);
    }

    /* =============================
        HAPUS TANGGAL
    ==============================*/
    public function deleteTanggal($id)
    {
        $tanggal = ArisanTanggal::find($id);

        if (!$tanggal) {
            return back()->with('error', 'Tanggal tidak ditemukan!');
        }

        $tanggal->delete();
        return back()->with('success', 'Tanggal berhasil dihapus!');
    }
}
