<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiForm;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiScanController extends Controller
{
    public function scanQr()
    {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'superadmin') {
            abort(403);
        }

        $layouts = [
            'anggota' => 'anggota.layouts.master',
            'sekretaris' => 'sekretaris.layouts.master',
            'bendahara' => 'bendahara.layouts.master',
            'admin' => 'admin.layouts.master'
        ];

        $layout = $layouts[$role] ?? 'layouts.master';

        return view('scan.scanner', compact('layout'));
    }

    // ================================
    //       PROSES ABSENSI QR
    // ================================
    public function prosesAbsen($token)
    {
        // cari form berdasarkan token QR
        $form = AbsensiForm::where('qr_token', $token)->first();

        if (!$form) {
            return redirect()->route('scan.qr')
                ->with('error', 'QR tidak valid atau tidak ditemukan.');
        }

        $user = Auth::user();

        // ===========================
        //  1. CEK SUDAH ABSEN ATAU BELUM
        // ===========================
        $cekAbsen = Absensi::where('user_id', $user->id)
            ->where('form_id', $form->id)
            ->exists();

        if ($cekAbsen) {
            return redirect()->route('scan.qr')
                ->with('error', 'Kamu sudah absen hari ini untuk kegiatan ini.');
        }

        // ===========================
        //  2. CEK WAKTU ABSENSI (jam_mulai - jam_selesai)
        // ===========================
        $now = Carbon::now();
        $tanggal = Carbon::parse($form->tanggal);

        $mulai = Carbon::parse($form->tanggal . ' ' . $form->jam_mulai);
        $selesai = Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);

        if ($now->lt($mulai)) {
            return redirect()->route('scan.qr')
                ->with('error', 'Absensi belum dibuka.');
        }

        if ($now->gt($selesai)) {
            return redirect()->route('scan.qr')
                ->with('error', 'Waktu absensi sudah berakhir.');
        }

        // ===========================
        //  3. SIMPAN ABSENSI
        // ===========================
        Absensi::create([
            'user_id' => $user->id,
            'form_id' => $form->id,
            'status'  => 'hadir',
            'waktu_absen' => now(),
        ]);

        return redirect()->route('scan.qr')
            ->with('success', 'Absensi berhasil dicatat!');
    }

    // ================================
    //        UPLOAD GAMBAR QR
    // ================================
    public function uploadQr(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|max:2048',
        ]);

        $image = $request->file('qr_image');

        $qr = new \Zxing\QrReader($image->getPathname());
        $text = $qr->text();

        if (!$text) {
            return redirect()->route('scan.qr')
                ->with('error', 'QR tidak terbaca, pastikan gambar jelas.');
        }

        // Ambil token dari URL QR
        $token = basename($text);

        return redirect()->route('proses.absen', $token);
    }
}

