<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AbsensiForm;
use App\Models\Denda;
use App\Models\Rt;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Global search untuk dashboard Admin
     */
    public function admin(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query]);
        }

        $user  = Auth::user();
        $rt_id = $user->rt_id;
        $results = [];

        // -- Cari Anggota RT --
        $anggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara', 'admin'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'role', 'image']);

        foreach ($anggota as $a) {
            $results[] = [
                'type'     => 'anggota',
                'icon'     => 'fas fa-user',
                'color'    => '#3b82f6',
                'title'    => $a->name,
                'subtitle' => ucfirst($a->role) . ' — ' . $a->email,
                'url'      => route('admin.AnggotaRT.index'),
            ];
        }

        // -- Cari Kegiatan / Absensi Form --
        $kegiatan = AbsensiForm::where('judul', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'judul', 'tanggal']);

        foreach ($kegiatan as $k) {
            $results[] = [
                'type'     => 'kegiatan',
                'icon'     => 'fas fa-calendar-check',
                'color'    => '#16a34a',
                'title'    => $k->judul,
                'subtitle' => 'Kegiatan — ' . \Carbon\Carbon::parse($k->tanggal)->format('d M Y'),
                'url'      => route('admin.rekap.index'),
            ];
        }

        // -- Cari Denda --
        $dendaList = Denda::with('user')
            ->whereHas('user', fn($q) => $q->where('rt_id', $rt_id))
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(3)
            ->get();

        foreach ($dendaList as $d) {
            $results[] = [
                'type'     => 'denda',
                'icon'     => 'fas fa-exclamation-triangle',
                'color'    => '#dc2626',
                'title'    => 'Denda: ' . ($d->user->name ?? '-'),
                'subtitle' => 'Rp ' . number_format($d->jumlah_denda, 0, ',', '.') . ' — ' . ucfirst($d->status),
                'url'      => route('admin.denda.index'),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $query,
            'total'   => count($results),
        ]);
    }

    /**
     * Global search untuk dashboard Anggota
     */
    public function anggota(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query]);
        }

        $user  = Auth::user();
        $rt_id = $user->rt_id;
        $results = [];

        // -- Cari Anggota RT (sesama RT) --
        $anggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara', 'admin'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        foreach ($anggota as $a) {
            $results[] = [
                'type'     => 'anggota',
                'icon'     => 'fas fa-user',
                'color'    => '#3b82f6',
                'title'    => $a->name,
                'subtitle' => ucfirst($a->role) . ' — ' . $a->email,
                'url'      => route('anggota.profile.index'),
            ];
        }

        // -- Cari Kegiatan --
        $kegiatan = AbsensiForm::where('judul', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'judul', 'tanggal']);

        foreach ($kegiatan as $k) {
            $results[] = [
                'type'     => 'kegiatan',
                'icon'     => 'fas fa-calendar-alt',
                'color'    => '#16a34a',
                'title'    => $k->judul,
                'subtitle' => 'Kegiatan — ' . \Carbon\Carbon::parse($k->tanggal)->format('d M Y'),
                'url'      => route('anggota.dashboard'),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $query,
            'total'   => count($results),
        ]);
    }

    /**
     * Global search untuk dashboard Bendahara
     */
    public function bendahara(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query]);
        }

        $user  = Auth::user();
        $rt_id = $user->rt_id;
        $results = [];

        // -- Cari Anggota --
        $anggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara', 'admin'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        foreach ($anggota as $a) {
            $results[] = [
                'type'     => 'anggota',
                'icon'     => 'fas fa-user',
                'color'    => '#3b82f6',
                'title'    => $a->name,
                'subtitle' => ucfirst($a->role) . ' — ' . $a->email,
                'url'      => route('bendahara.dashboard'),
            ];
        }

        // -- Cari Denda --
        $dendaList = Denda::with('user')
            ->whereHas('user', fn($q) => $q->where('rt_id', $rt_id))
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($dendaList as $d) {
            $results[] = [
                'type'     => 'denda',
                'icon'     => 'fas fa-exclamation-triangle',
                'color'    => '#dc2626',
                'title'    => 'Denda: ' . ($d->user->name ?? '-'),
                'subtitle' => 'Rp ' . number_format($d->jumlah_denda, 0, ',', '.') . ' — ' . ucfirst($d->status),
                'url'      => route('bendahara.denda.index'),
            ];
        }

        // -- Cari Catatan Keuangan (Kas) --
        $kas = \App\Models\Kas::where('keterangan', 'LIKE', "%{$query}%")
            ->limit(4)
            ->get(['id', 'keterangan', 'jumlah', 'jenis']);

        foreach ($kas as $k) {
            $results[] = [
                'type'     => 'kas',
                'icon'     => 'fas fa-wallet',
                'color'    => '#f59e0b',
                'title'    => $k->keterangan,
                'subtitle' => ucfirst($k->jenis) . ' — Rp ' . number_format($k->jumlah, 0, ',', '.'),
                'url'      => route('bendahara.kas.index'),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $query,
            'total'   => count($results),
        ]);
    }

    /**
     * Global search untuk dashboard Sekretaris
     */
    public function sekretaris(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query]);
        }

        $user  = Auth::user();
        $rt_id = $user->rt_id;
        $results = [];

        // -- Cari Anggota --
        $anggota = User::where('rt_id', $rt_id)
            ->whereIn('role', ['anggota', 'sekretaris', 'bendahara', 'admin'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        foreach ($anggota as $a) {
            $results[] = [
                'type'     => 'anggota',
                'icon'     => 'fas fa-user',
                'color'    => '#3b82f6',
                'title'    => $a->name,
                'subtitle' => ucfirst($a->role) . ' — ' . $a->email,
                'url'      => route('sekretaris.dashboard'),
            ];
        }

        // -- Cari Kegiatan / Absensi --
        $kegiatan = AbsensiForm::where('judul', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'judul', 'tanggal']);

        foreach ($kegiatan as $k) {
            $results[] = [
                'type'     => 'kegiatan',
                'icon'     => 'fas fa-clipboard-list',
                'color'    => '#16a34a',
                'title'    => $k->judul,
                'subtitle' => 'Kegiatan — ' . \Carbon\Carbon::parse($k->tanggal)->format('d M Y'),
                'url'      => route('sekretaris.absensi.index'),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $query,
            'total'   => count($results),
        ]);
    }

    /**
     * Global search untuk dashboard Superadmin
     */
    public function superadmin(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'query' => $query]);
        }

        $results = [];

        // -- Cari RT --
        $rtList = Rt::where('nama_rt', 'LIKE', "%{$query}%")
            ->orWhere('rw', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'nama_rt', 'rw']);

        foreach ($rtList as $rt) {
            $results[] = [
                'type'     => 'rt',
                'icon'     => 'fas fa-map-marker-alt',
                'color'    => '#3b82f6',
                'title'    => $rt->nama_rt,
                'subtitle' => 'RT — RW ' . ($rt->rw ?? '-'),
                'url'      => route('superadmin.rt.index'),
            ];
        }

        // -- Cari Admin RT --
        $admins = User::where('role', 'admin')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        foreach ($admins as $a) {
            $results[] = [
                'type'     => 'admin',
                'icon'     => 'fas fa-user-shield',
                'color'    => '#059669',
                'title'    => $a->name,
                'subtitle' => 'Admin RT — ' . $a->email,
                'url'      => route('superadmin.adminrt.index'),
            ];
        }

        // -- Cari Semua User --
        $users = User::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->whereNotIn('role', ['admin'])
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        foreach ($users as $u) {
            $results[] = [
                'type'     => 'user',
                'icon'     => 'fas fa-user',
                'color'    => '#8b5cf6',
                'title'    => $u->name,
                'subtitle' => ucfirst($u->role) . ' — ' . $u->email,
                'url'      => route('superadmin.adminrt.index'),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $query,
            'total'   => count($results),
        ]);
    }
}
