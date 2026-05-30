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

        $totalNominalTunggakanArisan = 0;
        $members = User::where('rt_id', $rt_id)->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])->get();
        $usersWithTunggakan = [];

        foreach ($members as $member) {
            $expectedForMember = ArisanTanggal::where('tanggal', '>=', $member->created_at->startOfMonth())->count();
            
            $paidForMember = CatatanArisan::where('user_id', $member->id)->count();
            $unpaidForMember = max(0, $expectedForMember - $paidForMember);
            
            $nominalUnpaid = $unpaidForMember * $iuranArisan;
            $totalNominalTunggakanArisan += $nominalUnpaid;

            if ($unpaidForMember > 0) {
                $usersWithTunggakan[] = (object)[
                    'user' => $member,
                    'unpaid_count' => $unpaidForMember,
                    'nominal' => $nominalUnpaid
                ];
            }
        }

        // ==========================================
        // 5.6 STATISTIK DENDA & TUNGGAKAN PRIBADI (BENDAHARA)
        // ==========================================
        $bendaharaUser = auth()->user();
        
        $dendaBelumBayarPribadi = Denda::where('user_id', $bendaharaUser->id)
            ->where('status', 'belum_bayar')
            ->sum('jumlah_denda');

        $expectedForBendahara = ArisanTanggal::where('tanggal', '>=', $bendaharaUser->created_at->startOfMonth())->count();
        $paidForBendahara = CatatanArisan::where('user_id', $bendaharaUser->id)->count();
        $unpaidForBendahara = max(0, $expectedForBendahara - $paidForBendahara);
        
        $tunggakanArisanPribadi = $unpaidForBendahara * $iuranArisan;
        $totalDendaKeseluruhanPribadi = $dendaBelumBayarPribadi + $tunggakanArisanPribadi;

        // Top 5 Anggota Penunggak Arisan
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

        // ==========================================
        // 7. TOTAL KESELURUHAN (DENDA KEGIATAN + ARISAN)
        // ==========================================
        $totalDendaKeseluruhan = $totalDendaTertunggak + $totalNominalTunggakanArisan;

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
            'topTunggakanArisan',
            'iuranArisan',
            'totalNominalTunggakanArisan',
            'dendaBelumBayarPribadi',
            'tunggakanArisanPribadi',
            'totalDendaKeseluruhanPribadi',
            'totalDendaKeseluruhan',
            'perubahanKas'
        ));
    }
}
