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

        // Ambil anggota sesuai RT sekretaris yang login (termasuk admin)
        $anggota = User::where('rt_id', auth()->user()->rt_id)
            ->whereIn('role', ['admin', 'anggota', 'sekretaris', 'bendahara'])
            ->orderBy('name', 'asc')
            ->get();

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
            // If exists, delete (toggle off)
            $catatan->delete();
            return response()->json([
                'status' => 'deleted',
                'message' => 'Checklist dihapus!'
            ]);
        }

        // Create new catatan with optional keterangan
        CatatanArisan::create([
            'user_id' => $request->user_id,
            'tanggal_id' => $request->tanggal_id,
            'sudah_bayar' => true,
            'keterangan' => $request->filled('keterangan') ? $request->keterangan : null,
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

    /* =============================
        HAPUS TAHUN
    ==============================*/
    public function deleteTahun($id)
    {
        $tahun = ArisanTahun::find($id);

        if (!$tahun) {
            return back()->with('error', 'Tahun tidak ditemukan!');
        }

        // Hapus juga semua tanggal yang berkaitan (beserta catatan arisannya jika on cascade belum diset)
        $tanggalIds = $tahun->tanggal()->pluck('id')->toArray();
        CatatanArisan::whereIn('tanggal_id', $tanggalIds)->delete();
        $tahun->tanggal()->delete();
        $tahun->delete();

        return back()->with('success', 'Tahun arisan beserta seluruh isinya berhasil dihapus!');
    }

    /* =============================
        REKAP ARISAN KESELURUHAN
    ==============================*/
    public function recap()
    {
        $rt_id = auth()->user()->rt_id;
        $members = User::where('rt_id', $rt_id)
            ->whereIn('role', ['admin', 'anggota', 'sekretaris', 'bendahara'])
            ->get();
        
        $allDates = ArisanTanggal::all();
        $allDatesIds = $allDates->pluck('id')->toArray();

        foreach ($members as $member) {
            $paidIds = CatatanArisan::where('user_id', $member->id)
                ->whereIn('tanggal_id', $allDatesIds)
                ->pluck('tanggal_id')
                ->toArray();
            
            $member->unpaid_dates = array_diff($allDatesIds, $paidIds);
            $member->unpaid_count = count($member->unpaid_dates);
        }

        // Statistics
        $totalOutstandingCount = $members->sum('unpaid_count');
        $mostDelinquent = $members->sortByDesc('unpaid_count')->first();

        return view('sekretaris.catatan_arisan.recap', compact('members', 'totalOutstandingCount', 'mostDelinquent'));
    }

    /* =============================
        PAY ALL (BULK PAY)
    ==============================*/
    public function payAll(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user_id = $request->user_id;
        $allDatesIds = ArisanTanggal::pluck('id')->toArray();
        $paidIds = CatatanArisan::where('user_id', $user_id)->pluck('tanggal_id')->toArray();
        $missingIds = array_diff($allDatesIds, $paidIds);

        if (empty($missingIds)) {
            return back()->with('info', 'Anggota ini sudah membayar semua arisan.');
        }

        foreach ($missingIds as $tanggalId) {
            CatatanArisan::create([
                'user_id' => $user_id,
                'tanggal_id' => $tanggalId,
                'sudah_bayar' => true
            ]);
        }

        return back()->with('success', 'Berhasil melunasi ' . count($missingIds) . ' sesi arisan untuk anggota tersebut.');
    }
}
