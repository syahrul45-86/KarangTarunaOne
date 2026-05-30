<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\AbsensiForm;
use App\Models\IzinAbsensi;
use App\Models\Denda;
use App\Models\CatatanArisan;
use App\Models\ArisanTanggal;
use Carbon\Carbon;

class NavbarComposer
{
    public function compose(View $view)
    {
        $notifications = collect();
        $user = auth()->user();

        if ($user) {
            $rt_id = $user->rt_id;
            
            // 1. Kegiatan Mendatang
            $kegiatan = AbsensiForm::where('rt_id', $rt_id)
                ->whereDate('tanggal', '>=', now()->toDateString())
                ->orderBy('tanggal', 'asc')
                ->get()
                ->filter(function($keg) {
                    $waktuSelesai = Carbon::parse($keg->tanggal . ' ' . $keg->jam_selesai);
                    return now()->lessThanOrEqualTo($waktuSelesai);
                });
                
            foreach($kegiatan as $keg) {
                $timeDiff = Carbon::parse($keg->tanggal . ' ' . $keg->jam_mulai);
                $isToday = $timeDiff->isToday();
                
                $notifications->push((object)[
                    'icon' => 'fas fa-calendar-alt',
                    'icon_bg' => 'primary',
                    'date' => $isToday ? 'Hari ini, ' . $keg->jam_mulai : $timeDiff->format('d M, H:i'),
                    'text' => 'Kegiatan: ' . $keg->judul . ' akan segera dilaksanakan'
                ]);
            }

            // 2. Izin Pending (Sekretaris)
            if ($user->role == 'sekretaris') {
                $izinPending = IzinAbsensi::where('status', 'pending')
                    ->whereHas('user', function($q) use ($rt_id) {
                        $q->where('rt_id', $rt_id);
                    })->count();
                    
                if ($izinPending > 0) {
                    $notifications->push((object)[
                        'icon' => 'fas fa-envelope-open-text',
                        'icon_bg' => 'warning',
                        'date' => 'Menunggu',
                        'text' => 'Ada ' . $izinPending . ' pengajuan izin yang butuh persetujuan'
                    ]);
                }
            }

            // 3. Notifikasi Denda (Bendahara/Admin)
            if (in_array($user->role, ['bendahara', 'admin'])) {
                $dendaQuery = Denda::where('status', '!=', 'lunas');
                if ($user->role != 'admin' || $rt_id) {
                    $dendaQuery->whereHas('user', function($q) use ($rt_id) {
                        $q->where('rt_id', $rt_id);
                    });
                }
                
                $dendaPending = $dendaQuery->count();
                    
                if ($dendaPending > 0) {
                    $notifications->push((object)[
                        'icon' => 'fas fa-exclamation-triangle',
                        'icon_bg' => 'danger',
                        'date' => 'Tertunggak',
                        'text' => 'Ada ' . $dendaPending . ' denda di RT ini yang belum dibayar'
                    ]);
                }
            } else {
                // Untuk Anggota
                $dendaKu = Denda::where('user_id', $user->id)
                    ->where('status', '!=', 'lunas')->count();
                    
                if ($dendaKu > 0) {
                    $notifications->push((object)[
                        'icon' => 'fas fa-exclamation-circle',
                        'icon_bg' => 'danger',
                        'date' => 'Penting',
                        'text' => 'Kamu memiliki ' . $dendaKu . ' denda yang belum dibayar'
                    ]);
                }
            }

            // 4. Notifikasi Arisan Tunggakan (Untuk setiap user)
            $allDatesIds = ArisanTanggal::pluck('id')->toArray();
            if (count($allDatesIds) > 0) {
                $paidIds = CatatanArisan::where('user_id', $user->id)
                                        ->where('sudah_bayar', true)
                                        ->pluck('tanggal_id')
                                        ->toArray();
                $tunggakanArisan = count(array_diff($allDatesIds, $paidIds));

                if ($tunggakanArisan > 0) {
                    $notifications->push((object)[
                        'icon' => 'fas fa-money-bill-wave',
                        'icon_bg' => 'danger',
                        'date' => 'Tagihan Arisan',
                        'text' => 'Kamu memiliki tunggakan ' . $tunggakanArisan . ' sesi arisan'
                    ]);
                }
            }
        }

        $view->with('notifications', $notifications->take(5));
    }
}

