<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrcodeController extends Controller
{
    /**
     * Tampilkan QR Code milik user yang sedang login (semua role).
     */
    public function show()
    {
        $user = auth()->user();

        // Jika belum punya QR, generate otomatis
        if (!$user->qr_code || !Storage::disk('public')->exists($user->qr_code)) {
            try {
                $qrToken = 'ABSEN-' . $user->id . '-' . Str::random(10);

                $qrImage = QrCode::format('svg')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate($qrToken);

                $qrPath = 'qrcode/user-' . $user->id . '.svg';
                Storage::disk('public')->put($qrPath, $qrImage);

                $user->update([
                    'qr_code'  => $qrPath,
                    'qr_token' => $qrToken,
                ]);

                // Refresh model
                $user = auth()->user()->fresh();
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal generate QR Code: ' . $e->getMessage());
            }
        }

        $qrUrl = asset('storage/' . $user->qr_code);

        // Alias variabel $user ke $anggota agar kompatibel dengan view yang lama
        $anggota = $user;

        return view('qrcode.show', compact('anggota', 'qrUrl'));
    }
}
