<?php

namespace App\Http\Controllers\bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bendaharas;
use Illuminate\Support\Facades\DB;

class CatatanKeuanganController extends Controller
{
public function index()
{
    $bendahara = auth()->user();
    $rt_id = $bendahara->rt_id;

    // Query dasar (untuk list yang akan ditampilkan)
    $query = Bendaharas::where('rt_id', $rt_id)->orderBy('tanggal', 'asc');

    // Jika filter tanggal dipakai
    if (request('tanggal')) {
        $query->whereDate('tanggal', request('tanggal'));
    }

    $bendaharas = $query->get();

    // INFORMASI: apakah RT ini punya catatan sama sekali (tanpa filter)
    $hasAny = Bendaharas::where('rt_id', $rt_id)->exists();

    return view('bendahara.catatan-keuangan.index', compact('bendahara','bendaharas','hasAny'));
}



    // CREATE
    public function create()
    {
        // CEK apakah rt_id ini sudah punya saldo awal atau belum
        $hasSaldoAwal = Bendaharas::where('rt_id', auth()->user()->rt_id)->exists();

        if (!$hasSaldoAwal) {
            // kalau BELUM ada → tampilkan form saldo awal pertama kali
            return view('bendahara.catatan-keuangan.create_saldo_awal');
        }

        // kalau SUDAH ada → tampilkan form pemasukan/pengeluaran
        return view('bendahara.catatan-keuangan.create');
    }


    // EDIT SALDO AWAL
    public function editSaldoAwal()
    {
        $saldo_awal = Bendaharas::where('rt_id', auth()->user()->rt_id)
                                ->orderBy('id', 'asc')
                                ->value('saldo_awal');

        return view('bendahara.catatan-keuangan.edit_saldo_awal', compact('saldo_awal'));
    }


    // STORE SALDO AWAL
        public function storeSaldoAwal(Request $request)
        {
            $request->validate([
                'saldo_awal' => 'required|numeric|min:0',
            ]);

            Bendaharas::create([
                'rt_id' => auth()->user()->rt_id,
                'tanggal' => now(),
                'keterangan' => 'Saldo Awal',
                'saldo_awal' => $request->saldo_awal,
                'saldo_akhir' => $request->saldo_awal,
                'pemasukan' => 0,
                'pengeluaran' => 0,
            ]);

            return redirect()->route('bendahara.catatan-keuangan.index');
        }



    // STORE KEUANGAN
public function storeKeuangan(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'keterangan' => 'required|string|max:255',
        'jenis' => 'required|in:pemasukan,pengeluaran',
        'jumlah' => 'required|numeric|min:0',
    ]);

    $rt_id = auth()->user()->rt_id;

    $saldo_awal = Bendaharas::where('rt_id', $rt_id)
                            ->latest()
                            ->value('saldo_akhir') ?? 0;

    // Tentukan nominal pemasukan / pengeluaran
    if ($request->jenis == 'pemasukan') {
        $pemasukan = $request->jumlah;
        $pengeluaran = 0;
    } else {
        $pemasukan = 0;
        $pengeluaran = $request->jumlah;
    }

    // Hitung saldo akhir
    $saldo_akhir = $saldo_awal + $pemasukan - $pengeluaran;

    // Simpan catatan
    Bendaharas::create([
        'rt_id' => $rt_id,
        'tanggal' => $request->tanggal,
        'keterangan' => $request->keterangan,
        'pemasukan' => $pemasukan,
        'pengeluaran' => $pengeluaran,
        'saldo_awal' => $saldo_awal,
        'saldo_akhir' => $saldo_akhir,
    ]);

    return redirect()->route('bendahara.catatan-keuangan.index');
}


    // EDIT TRANSAKSI
    public function edit($id)
    {
        $bendahara = Bendaharas::findOrFail($id);

        return view('bendahara.catatan-keuangan.edit', compact('bendahara'));
    }


    // UPDATE TRANSAKSI
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'pemasukan' => 'nullable|numeric|min:0',
            'pengeluaran' => 'nullable|numeric|min:0',
        ]);

        $bendahara = Bendaharas::findOrFail($id);

        $saldo_awal = $bendahara->saldo_awal;
        $pemasukan = $request->pemasukan ?? 0;
        $pengeluaran = $request->pengeluaran ?? 0;
        $saldo_akhir = $saldo_awal + $pemasukan - $pengeluaran;

        $bendahara->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo_akhir' => $saldo_akhir,
        ]);

        return redirect()->route('bendahara.catatan-keuangan.index');
    }


    // UPDATE SALDO AWAL
    public function updateSaldoAwal(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        $rt_id = auth()->user()->rt_id;

        $bendaharas = Bendaharas::where('rt_id', $rt_id)
                                ->orderBy('tanggal', 'asc')
                                ->get();

        if ($bendaharas->isNotEmpty()) {
            $saldo_sebelumnya = $request->saldo_awal;

            foreach ($bendaharas as $bendahara) {
                $bendahara->saldo_awal = $saldo_sebelumnya;
                $bendahara->saldo_akhir = $saldo_sebelumnya + $bendahara->pemasukan - $bendahara->pengeluaran;

                $saldo_sebelumnya = $bendahara->saldo_akhir;

                $bendahara->save();
            }
        }

        return redirect()->route('bendahara.catatan-keuangan.index');
    }


    // DELETE
    public function destroy($id)
    {
        Bendaharas::findOrFail($id)->delete();

        return redirect()->route('bendahara.catatan-keuangan.index');
    }
}
