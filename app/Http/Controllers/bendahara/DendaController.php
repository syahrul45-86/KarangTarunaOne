<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\User;
use App\Models\SettingRT;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // =========================
    // 1. DENDA KESELURUHAN
    // =========================
    public function index(Request $request)
    {
        $bendahara = auth()->user();
        $search = $request->search;

        $users = User::where('rt_id', $bendahara->rt_id)
            ->when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->get();

        $settingRT = SettingRT::where('rt_id', $bendahara->rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;
        $allDatesCount = ArisanTanggal::count();

        $data = [];
        foreach ($users as $u) {
            $belum = Denda::where('user_id', $u->id)->where('status', '!=', 'lunas')->sum('jumlah_denda');

            $expectedForUser = ArisanTanggal::where('tanggal', '>=', $u->created_at->startOfMonth())->count();
            $paidCount = CatatanArisan::where('user_id', $u->id)->count();
            $unpaidCount = max(0, $expectedForUser - $paidCount);
            $tunggakanArisan = $unpaidCount * $iuranArisan;

            $data[] = [
                'user' => $u,
                'belum_bayar' => $belum,
                'tunggakan_arisan' => $tunggakanArisan,
                'total_semua' => $belum + $tunggakanArisan,
            ];
        }

        return view('bendahara.denda.index', compact('data', 'search'));
    }

    public function exportPdf(Request $request)
    {
        $bendahara = auth()->user();
        $search = $request->search;

        $users = User::where('rt_id', $bendahara->rt_id)
            ->when($search, function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->get();

        $settingRT = SettingRT::where('rt_id', $bendahara->rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;

        $data = [];
        foreach ($users as $u) {
            $belum = Denda::where('user_id', $u->id)->where('status', '!=', 'lunas')->sum('jumlah_denda');

            $expectedForUser = ArisanTanggal::where('tanggal', '>=', $u->created_at->startOfMonth())->count();
            $paidCount = CatatanArisan::where('user_id', $u->id)->count();
            $unpaidCount = max(0, $expectedForUser - $paidCount);
            $tunggakanArisan = $unpaidCount * $iuranArisan;

            $data[] = [
                'user' => $u,
                'belum_bayar' => $belum,
                'tunggakan_arisan' => $tunggakanArisan,
                'total_semua' => $belum + $tunggakanArisan,
            ];
        }

        $rt = \App\Models\Rt::find($bendahara->rt_id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bendahara.denda.pdf', compact('data', 'rt'));
        return $pdf->download('Rekap_Tunggakan_RT.pdf');
    }

    // =========================
    // 2. DENDA ABSENSI
    // =========================
    public function absensi(Request $request)
    {
        $bendahara = auth()->user();
        $search = $request->search;

        $denda = Denda::with('user')
            ->whereIn('jenis', ['absensi', 'manual'])

            ->whereHas('user', function ($q) use ($bendahara) {
                $q->where('rt_id', $bendahara->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->get();

        return view('bendahara.denda.denda-absensi.index', compact('denda', 'search'));
    }

    // =========================
    // 3. DENDA KEGIATAN
    // =========================
    public function kegiatan(Request $request)
    {
        $bendahara = auth()->user();
         $users = User::all();
        $search = $request->search;

        $denda = Denda::with('user')
            ->where('jenis', 'kegiatan')
            ->whereHas('user', function ($q) use ($bendahara) {
                $q->where('rt_id', $bendahara->rt_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->get();

        return view('bendahara.denda.denda-kegiatan.index', compact('denda', 'search', 'users'));
    }
    public function storeKegiatan(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'jumlah_denda' => 'required|numeric|min:0',
    ]);

    $existing = Denda::where('user_id', $request->user_id)
        ->where('status', '!=', 'lunas')
        ->first();

    if ($existing) {
        $existing->jumlah_denda += $request->jumlah_denda;
        $existing->save();
    } else {
        Denda::create([
            'user_id' => $request->user_id,
            'jenis' => 'kegiatan',
            'jumlah_denda' => $request->jumlah_denda,
            'status' => 'belum_bayar',
        ]);
    }

    return back()->with('success', 'Denda kegiatan ditambahkan!');
}


    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $bendahara = auth()->user();
        $users = User::where('rt_id', $bendahara->rt_id)->get();

        return view('bendahara.denda.denda-absensi.create', compact('users'));

    }

    // =========================
    // STORE
    // =========================
public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'jumlah_denda' => 'required|numeric|min:0',
        'jenis' => 'required|string',
    ]);

    // cari denda aktif apapun jenisnya
    $existing = Denda::where('user_id', $request->user_id)
        ->where('status', '!=', 'lunas')
        ->first();

    if ($existing) {
        // tambah nominal saja
        $existing->jumlah_denda += $request->jumlah_denda;
        $existing->save();
    } else {
        // baru buat manual/kegiatan
        Denda::create([
            'user_id' => $request->user_id,
            'jenis' => $request->jenis,
            'jumlah_denda' => $request->jumlah_denda,
            'status' => 'belum_bayar',
        ]);
    }

    if ($request->jenis == 'kegiatan') {
        return redirect()->route('bendahara.denda.kegiatan')->with('success', 'Denda kegiatan berhasil ditambahkan!');
    }

    return redirect()->route('bendahara.denda.absensi')
        ->with('success', 'Denda absensi manual berhasil ditambahkan!');
}



    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        Denda::findOrFail($id)->delete();

        return back()->with('success', 'Denda dihapus.');
    }


    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $denda = Denda::findOrFail($id);
        $bendahara = auth()->user();
        $users = User::where('rt_id', $bendahara->rt_id)->get();

        return view('bendahara.denda.denda-absensi.edit', compact('denda', 'users'));
    }

    // =========================
    // 5. UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);

        $request->validate([
            'nominal_bayar' => 'required|numeric|min:0',
        ]);

        $bayar = $request->nominal_bayar;

        if ($bayar > 0) {
            if ($bayar >= $denda->jumlah_denda) {
                // Lunas
                $denda->update([
                    'jumlah_denda' => 0,
                    'status' => 'lunas',
                ]);
            } else {
                // Cicilan (Sisa denda berkurang)
                $denda->update([
                    'jumlah_denda' => $denda->jumlah_denda - $bayar,
                    'status' => 'belum_bayar',
                ]);
            }
        }

        return redirect()->route('bendahara.denda.absensi')->with('success', 'Pembayaran denda berhasil dicatat.');
    }

}
