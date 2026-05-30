<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Bendaharas;
use App\Models\User;
use App\Models\SettingRT;
use App\Models\ArisanTanggal;
use App\Models\CatatanArisan;
use Carbon\Carbon;

class BendaharaController extends Controller
{

    public function index()
    {
        $bendahara = auth()->user();
        $rt_id = $bendahara->rt_id;

        // ==========================================
        // 1. STATISTIK KEUANGAN
        // ==========================================

        // Total Kas (Saldo Akhir Terbaru)
        $totalKas = Bendaharas::where('rt_id', $rt_id)
            ->latest('tanggal')
            ->value('saldo_akhir') ?? 0;

        // Saldo Bulan Lalu
        $saldoBulanLalu = Bendaharas::where('rt_id', $rt_id)
            ->whereMonth('tanggal', Carbon::now()->subMonth()->month)
            ->whereYear('tanggal', Carbon::now()->subMonth()->year)
            ->latest('tanggal')
            ->value('saldo_akhir') ?? 0;


        // ✅ UBAH: Pemasukan TAHUN INI (bukan bulan ini)
        $pemasukanTahunIni = Bendaharas::where('rt_id', $rt_id)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('pemasukan') ?? 0;

        // ✅ UBAH: Pengeluaran TAHUN INI (bukan bulan ini)
        $pengeluaranTahunIni = Bendaharas::where('rt_id', $rt_id)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('pengeluaran') ?? 0;


        // ==========================================
        // 2. STATISTIK DENDA
        // ==========================================

        // Total Denda Tertunggak
        $totalDendaTertunggak = Denda::whereHas('user', function($q) use ($rt_id) {
                $q->where('rt_id', $rt_id);
            })
            ->where('status', 'belum_bayar')
            ->sum('jumlah_denda') ?? 0;

        // Jumlah Anggota dengan Denda Belum Bayar
        $jumlahAnggotaBelumBayar = Denda::whereHas('user', function($q) use ($rt_id) {
                $q->where('rt_id', $rt_id);
            })
            ->where('status', 'belum_bayar')
            ->distinct('user_id')
            ->count('user_id');

        // Total Anggota di RT
        $totalAnggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
            ->count();

        // ==========================================
        // 3. BREAKDOWN KEUANGAN BULAN INI
        // ==========================================

        // Breakdown berdasarkan keterangan (estimasi)
        $iuranWarga = Bendaharas::where('rt_id', $rt_id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('keterangan', 'LIKE', '%iuran%')
            ->sum('pemasukan') ?? 0;

        $sumbangan = Bendaharas::where('rt_id', $rt_id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('keterangan', 'LIKE', '%sumbangan%')
            ->sum('pemasukan') ?? 0;

        $lainLain = $pemasukanTahunIni - $iuranWarga - $sumbangan;

        $operasional = Bendaharas::where('rt_id', $rt_id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('keterangan', 'LIKE', '%operasional%')
            ->sum('pengeluaran') ?? 0;

        $kegiatan = $pengeluaranTahunIni - $operasional;

        // ==========================================
        // 4. TRANSAKSI TERAKHIR (5 Terbaru)
        // ==========================================

        $transaksiTerbaru = Bendaharas::where('rt_id', $rt_id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // ==========================================
        $dendaTertunggak = Denda::with('user')
            ->whereHas('user', function($q) use ($rt_id) {
                $q->where('rt_id', $rt_id);
            })
            ->where('status', 'belum_bayar')
            ->orderBy('jumlah_denda', 'desc')
            ->take(5)
            ->get();

        // ==========================================
        // 5.5 TUNGGAKAN ARISAN (GLOBAL RT)
        // ==========================================
        $settingRT = SettingRT::where('rt_id', $rt_id)->first();
        $iuranArisan = $settingRT->iuran_arisan ?? 0;

        $allDatesCount = ArisanTanggal::count();
        $members = User::where('rt_id', $rt_id)->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])->get();
        $totalExpectedArisan = $members->count() * $allDatesCount;

        $totalPaidArisan = CatatanArisan::whereHas('user', function($q) use ($rt_id) {
            $q->where('rt_id', $rt_id);
        })->count();

        $totalUnpaidCountArisan = max(0, $totalExpectedArisan - $totalPaidArisan);
        $totalNominalTunggakanArisan = $totalUnpaidCountArisan * $iuranArisan;

        // Top 5 Anggota Penunggak Arisan
        $usersWithTunggakan = [];
        foreach ($members as $member) {
            $paidCount = CatatanArisan::where('user_id', $member->id)->count();
            $unpaidCount = $allDatesCount - $paidCount;
            if ($unpaidCount > 0) {
                $usersWithTunggakan[] = (object)[
                    'user' => $member,
                    'unpaid_count' => $unpaidCount,
                    'nominal' => $unpaidCount * $iuranArisan
                ];
            }
        }
        usort($usersWithTunggakan, function($a, $b) {
            return $b->unpaid_count <=> $a->unpaid_count;
        });
        $topTunggakanArisan = array_slice($usersWithTunggakan, 0, 5);

        // ==========================================
        // 6. PERSENTASE PERUBAHAN
        // ==========================================

        $perubahanKas = 0;
        if ($saldoBulanLalu > 0) {
            $perubahanKas = (($totalKas - $saldoBulanLalu) / $saldoBulanLalu) * 100;
        }

        return view('bendahara.dashboard', compact(
            'bendahara',
            'totalKas',
            'saldoBulanLalu',
            'pemasukanTahunIni',
            'pengeluaranTahunIni',
            'totalDendaTertunggak',
            'jumlahAnggotaBelumBayar',
            'totalAnggota',
            'iuranWarga',
            'sumbangan',
            'lainLain',
            'operasional',
            'kegiatan',
            'transaksiTerbaru',
            'dendaTertunggak',
            'totalNominalTunggakanArisan',
            'topTunggakanArisan',
            'perubahanKas'
        ));
    }
}
