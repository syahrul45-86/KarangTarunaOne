<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiForm;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // =========================
    // LIST FORM ABSENSI
    // =========================
    public function index()
    {
        $forms = AbsensiForm::orderBy('created_at', 'desc')->get();

        return view('sekretaris.absensi.index', compact('forms'));
    }

    // =========================
    // FORM BUAT ABSENSI BARU
    // =========================
    public function create()
    {
        return view('sekretaris.absensi.create');
    }

    // =========================
    // SIMPAN FORM ABSENSI BARU
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        AbsensiForm::create([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('sekretaris.absensi.index')
            ->with('success', 'Form absensi berhasil dibuat!');
    }

    // =========================
    // HALAMAN SCAN QR USER
    // =========================
    public function scanPage($id)
    {
        $form = AbsensiForm::findOrFail($id);

        // CEK BATAS WAKTU ABSENSI
        $now = Carbon::now();
        $mulai = Carbon::parse($form->tanggal . ' ' . $form->jam_mulai);
        $selesai = Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);

        if ($now->lt($mulai)) {
            return redirect()->route('sekretaris.absensi.index')
                ->with('error', 'Absensi belum dibuka! Waktu mulai: ' . $form->jam_mulai);
        }

        if ($now->gt($selesai)) {
            return redirect()->route('sekretaris.absensi.index')
                ->with('error', 'Waktu absensi sudah berakhir! Waktu selesai: ' . $form->jam_selesai);
        }

        return view('sekretaris.absensi.scan', compact('form'));
    }

    // =========================
    // PROSES SCAN QR USER
    // Menerima qr_token dari QR Code user yang di-scan
    // Format token: ABSEN-{user_id}-{random}
    // =========================
    public function processScan(Request $request, $formId)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        $form = AbsensiForm::findOrFail($formId);

        // CARI USER BERDASARKAN QR TOKEN
        // QR Token ini adalah token yang digenerate saat admin tambah user
        $user = User::where('qr_token', $request->qr_token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid! Pastikan QR code adalah QR user yang terdaftar.'
            ], 404);
        }

        // VALIDASI 1: CEK APAKAH USER DI RT YANG SAMA
        if ($user->rt_id !== auth()->user()->rt_id) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' tidak terdaftar di RT ini!'
            ], 403);
        }

        // VALIDASI 2: CEK SUDAH ABSEN ATAU BELUM
        $sudahAbsen = Absensi::where('form_id', $form->id)
                    ->where('user_id', $user->id)
                    ->first();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah melakukan absensi pada ' .
                           Carbon::parse($sudahAbsen->waktu_absen)->format('H:i:s')
            ], 400);
        }

        // VALIDASI 3: CEK BATAS WAKTU ABSENSI
        $now = Carbon::now();
        $mulai = Carbon::parse($form->tanggal . ' ' . $form->jam_mulai);
        $selesai = Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);

        if ($now->lt($mulai)) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi belum dibuka! Waktu mulai: ' . $form->jam_mulai
            ], 400);
        }

        if ($now->gt($selesai)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absensi sudah berakhir! Waktu selesai: ' . $form->jam_selesai
            ], 400);
        }

        // SEMUA VALIDASI LOLOS - SIMPAN ABSENSI
        Absensi::create([
            'form_id' => $form->id,
            'user_id' => $user->id,
            'status' => 'hadir',
            'waktu_absen' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat untuk ' . $user->name . '!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'waktu_absen' => now()->format('H:i:s')
            ]
        ]);
    }

    // =========================
    // GET DAFTAR ABSENSI (JSON)
    // Untuk real-time update di halaman scan
    // =========================
    public function getAbsensiList($formId)
    {
        $form = AbsensiForm::findOrFail($formId);

        $absenList = Absensi::where('form_id', $formId)
            ->with('user:id,name,email')
            ->orderBy('waktu_absen', 'desc')
            ->get()
            ->map(function($absen) {
                return [
                    'user' => [
                        'id' => $absen->user->id,
                        'name' => $absen->user->name,
                        'email' => $absen->user->email,
                    ],
                    'waktu_absen' => Carbon::parse($absen->waktu_absen)->format('H:i:s'),
                    'status' => $absen->status
                ];
            });

        // Hitung total user di RT
        $totalUser = User::where('rt_id', auth()->user()->rt_id)->count();
        $totalHadir = $absenList->count();

        return response()->json([
            'success' => true,
            'form' => [
                'id' => $form->id,
                'judul' => $form->judul,
                'tanggal' => $form->tanggal,
                'jam_mulai' => $form->jam_mulai,
                'jam_selesai' => $form->jam_selesai,
            ],
            'absensi' => $absenList,
            'statistik' => [
                'total_user' => $totalUser,
                'total_hadir' => $totalHadir,
                'total_tidak_hadir' => $totalUser - $totalHadir,
                'persentase_hadir' => $totalUser > 0 ? round(($totalHadir / $totalUser) * 100, 2) : 0
            ]
        ]);
    }

    // =========================
    // CEK ABSENSI (VIEW)
    // Untuk melihat daftar yang sudah absen
    // =========================
    public function cekAbsensi($id)
    {
        $form = AbsensiForm::findOrFail($id);

        // Ambil daftar yang sudah absen
        $absenList = Absensi::where('form_id', $id)
            ->with('user')
            ->orderBy('waktu_absen', 'asc')
            ->get();

        // Ambil total user di RT
        $totalUser = User::where('rt_id', auth()->user()->rt_id)->count();
        $totalHadir = $absenList->count();
        $totalTidakHadir = $totalUser - $totalHadir;

        return view('sekretaris.absensi.cek', compact('form', 'absenList', 'totalUser', 'totalHadir', 'totalTidakHadir'));
    }

    // =========================
    // PROSES DENDA OTOMATIS
    // Untuk user yang tidak hadir
    // =========================
    public function prosesDenda($id)
    {
        $form = AbsensiForm::findOrFail($id);

        // CEK APAKAH DENDA SUDAH PERNAH DIPROSES
        $sudahProses = Denda::where('form_id', $form->id)->exists();

        if ($sudahProses) {
            return back()->with('error', 'Denda untuk absensi "' . $form->judul . '" sudah diproses sebelumnya!');
        }

        // Ambil setting denda dari RT
        $setting = \App\Models\SettingRT::where('rt_id', auth()->user()->rt_id)->first();
        $dendaAbsensi = $setting ? $setting->denda_absensi : 10000;

        // Ambil seluruh user di RT ini
        $users = User::where('rt_id', auth()->user()->rt_id)
                     ->whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                     ->get();

        $jumlahDenda = 0;
        $userKenaDenda = [];

        foreach ($users as $user) {
            // Cek apakah user sudah absen
            $sudahAbsen = Absensi::where('form_id', $form->id)
                                ->where('user_id', $user->id)
                                ->exists();

            // Jika TIDAK HADIR → proses denda
            if (!$sudahAbsen) {
                // Cek apakah user sudah punya denda yang belum dibayar
                $dendaLama = Denda::where('user_id', $user->id)
                                  ->where('status', 'belum_bayar')
                                  ->first();

                if ($dendaLama) {
                    // Tambahkan ke denda yang sudah ada
                    $dendaLama->jumlah_denda += $dendaAbsensi;
                    $dendaLama->alasan .= ' | Tidak absen: ' . $form->judul;
                    $dendaLama->save();
                } else {
                    // Buat denda baru
                    Denda::create([
                        'user_id' => $user->id,
                        'form_id' => $form->id,
                        'jenis' => 'absensi',
                        'alasan' => 'Tidak absen: ' . $form->judul,
                        'jumlah_denda' => $dendaAbsensi,
                        'status' => 'belum_bayar',
                    ]);
                }

                $jumlahDenda++;
                $userKenaDenda[] = $user->name;
            }
        }

        if ($jumlahDenda > 0) {
            return back()->with('success',
                'Denda berhasil diproses untuk ' . $jumlahDenda . ' orang yang tidak hadir. ' .
                'Total denda: Rp ' . number_format($dendaAbsensi * $jumlahDenda, 0, ',', '.')
            );
        } else {
            return back()->with('info', 'Semua anggota sudah hadir. Tidak ada denda yang diproses.');
        }
    }

    // =========================
    // HAPUS FORM ABSENSI
    // =========================
    public function destroy($id)
    {
        $form = AbsensiForm::findOrFail($id);

        // Cek apakah ada absensi yang sudah tercatat
        $jumlahAbsen = Absensi::where('form_id', $id)->count();

        if ($jumlahAbsen > 0) {
            return back()->with('error',
                'Form absensi tidak dapat dihapus karena sudah ada ' . $jumlahAbsen . ' data absensi.'
            );
        }

        $form->delete();

        return back()->with('success', 'Form absensi "' . $form->judul . '" berhasil dihapus!');
    }
}
