<?php

namespace App\Http\Controllers\admin;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TambahAnggotaController extends Controller
{
    public function index()
    {
        $rt_id = Auth::user()->rt_id;

        // Ambil semua user di RT ini, termasuk anggota, sekretaris, bendahara
        $anggotaSemua = User::whereIn('role', ['anggota', 'sekretaris', 'bendahara'])
                             ->where('rt_id', $rt_id)
                             ->orderBy('id', 'asc')
                             ->get();

        // Cek apakah sekretaris dan bendahara sudah ada
        $sekretaris = $anggotaSemua->where('role', 'sekretaris')->first();
        $bendahara  = $anggotaSemua->where('role', 'bendahara')->first();

        return view('admin.Tambah_anggota.index', compact('anggotaSemua', 'sekretaris', 'bendahara'));
    }

    public function store(Request $request)
    {
        $rt_id = Auth::user()->rt_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:anggota,sekretaris,bendahara',
        ]);

        // 🔍 CEK NAMA SUDAH ADA DI RT YANG SAMA
        $cekNama = User::where('name', $request->name)
                       ->where('rt_id', $rt_id)
                       ->exists();

        if ($cekNama) {
            return redirect()->route('admin.AnggotaRT.index')
                             ->with('error', 'Nama tersebut sudah terdaftar di RT ini.')
                             ->withInput();
        }

        // 🔍 CEK EMAIL SUDAH ADA GLOBAL
        if (User::where('email', $request->email)->exists()) {
            return redirect()->route('admin.AnggotaRT.index')
                             ->with('error', 'Email sudah terdaftar! Gunakan email lain.')
                             ->withInput();
        }

        // 🔍 CEK SEKRETARIS
        if ($request->role === 'sekretaris') {
            if (User::where('role', 'sekretaris')->where('rt_id', $rt_id)->exists()) {
                return redirect()->route('admin.AnggotaRT.index')
                                 ->with('error', 'Sekretaris sudah ada di RT ini.')
                                 ->withInput();
            }
        }

        // 🔍 CEK BENDAHARA
        if ($request->role === 'bendahara') {
            if (User::where('role', 'bendahara')->where('rt_id', $rt_id)->exists()) {
                return redirect()->route('admin.AnggotaRT.index')
                                 ->with('error', 'Bendahara sudah ada di RT ini.')
                                 ->withInput();
            }
        }

        // 🔍 CEK ANGGOTA SUDAH ADA DI RT
        if ($request->role === 'anggota') {
            $cekAnggota = User::where('name', $request->name)
                              ->where('rt_id', $rt_id)
                              ->where('role', 'anggota')
                              ->exists();

            if ($cekAnggota) {
                return redirect()->route('admin.AnggotaRT.index')
                                 ->with('error', 'Anggota ini sudah terdaftar dalam RT Anda.')
                                 ->withInput();
            }
        }

        // ====================================
        // SIMPAN DATA USER DULU (TANPA QR)
        // ====================================
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'rt_id' => $rt_id,
        ]);

        // CEK kalau user gagal dibuat
        if (!$user) {
            return back()->with('error', 'Gagal membuat user');
        }

        // ====================================
        // GENERATE QR CODE PERMANEN (SVG)
        // ====================================
        try {
            // Format: ABSEN-{user_id}-{random_token}
            $qrToken = 'ABSEN-' . $user->id . '-' . Str::random(10);

            // Generate QR code sebagai SVG
            $qrImage = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H') // High error correction
                ->generate($qrToken);

            // Path untuk menyimpan QR code
            $qrPath = 'qrcode/user-' . $user->id . '.svg';

            // Simpan ke storage/app/public/qrcode/
            Storage::disk('public')->put($qrPath, $qrImage);

            // Update user dengan path QR code
            $user->update([
                'qr_code' => $qrPath,
                'qr_token' => $qrToken // Simpan token juga untuk validasi
            ]);

        } catch (\Exception $e) {
            // Jika QR code gagal, tetap lanjutkan tapi kasih warning
            return redirect()->route('admin.AnggotaRT.index')
                ->with('warning', 'User berhasil dibuat, tapi QR code gagal di-generate. Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.AnggotaRT.index')
            ->with('success', 'User berhasil dibuat dengan QR code!');
    }

    public function idCardForm()
    {
        $rt_id = Auth::user()->rt_id;

        $users = User::where('rt_id', $rt_id)->get();

        return view('admin.idcard.form', compact('users'));
    }

    public function generateIdCard(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'template' => 'required|image',
        ]);

        $user = User::findOrFail($request->user_id);

        // ====================================
        // GENERATE QR KALAU BELUM ADA (SVG)
        // ====================================
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
                    'qr_code' => $qrPath,
                    'qr_token' => $qrToken
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal generate QR code: ' . $e->getMessage());
            }
        }

        $templatePath = $request->file('template')
            ->store('temp/template', 'public');

        // SIMPAN KE SESSION
        session([
            'idcard_preview' => [
                'user_id' => $user->id,
                'templatePath' => $templatePath,
            ]
        ]);

        // REDIRECT KE GET
        return redirect()->route('admin.idcard.preview');
    }

     // ====================================
    // VIEW ID CARD (Tampilkan ID Card yang sudah disimpan)
    // ====================================
    public function viewIdCard($userId)
    {
        $user = User::findOrFail($userId);

        // Cek apakah user sudah punya ID Card
        if (!$user->id_card_path || !Storage::disk('public')->exists($user->id_card_path)) {
            return redirect()
                ->route('admin.idcard.form')
                ->with('error', 'ID Card belum dibuat. Silakan generate ID Card terlebih dahulu.');
        }

        return view('admin.idcard.view', [
            'user' => $user,
            'idCardPath' => $user->id_card_path
        ]);
    }

    public function saveIdCard(Request $request)
{
    try {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'image_data' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);

        // Decode base64 image
        $imageData = $request->image_data;
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedImage = base64_decode($imageData);

        // Generate filename
        $fileName = 'idcard_' . $user->id . '_' . time() . '.png';
        $path = 'idcards/' . $fileName;

        // Simpan ke storage
        Storage::disk('public')->put($path, $decodedImage);

        // Update user dengan path ID card
        $user->update([
            'id_card_path' => $path
        ]);

        // Hapus template temporary jika ada
        $sessionData = session('idcard_preview');
        if ($sessionData && isset($sessionData['templatePath'])) {
            Storage::disk('public')->delete($sessionData['templatePath']);
        }

        // Clear session
        session()->forget('idcard_preview');

        return response()->json([
            'success' => true,
            'message' => 'ID Card berhasil disimpan',
            'path' => $path
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan ID Card: ' . $e->getMessage()
        ], 500);
    }
}
    public function previewIdCard()
    {
        $data = session('idcard_preview');

        if (!$data) {
            return redirect()
                ->route('admin.idcard.form')
                ->with('error', 'Preview ID Card tidak ditemukan');
        }

        $user = User::findOrFail($data['user_id']);

        return view('admin.idcard.preview', [
            'user' => $user,
            'templatePath' => $data['templatePath'],
            'qrPath' => $user->qr_code,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rt_id = Auth::user()->rt_id;
        $user = User::findOrFail($id);

        // Validasi hanya bisa update anggota di RT yang sama
        if ($user->rt_id !== $rt_id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:anggota,sekretaris,bendahara',
        ]);

        // 🔴 Cek jika role diganti ke sekretaris/bendahara
        if ($request->role !== $user->role) {
            if ($request->role === 'sekretaris') {
                $cekSekretaris = User::where('role', 'sekretaris')
                                     ->where('rt_id', $rt_id)
                                     ->where('id', '!=', $id)
                                     ->exists();

                if ($cekSekretaris) {
                    return back()->with('error', 'Sekretaris sudah ada! Hanya boleh 1 sekretaris per RT.');
                }
            }

            if ($request->role === 'bendahara') {
                $cekBendahara = User::where('role', 'bendahara')
                                    ->where('rt_id', $rt_id)
                                    ->where('id', '!=', $id)
                                    ->exists();

                if ($cekBendahara) {
                    return back()->with('error', 'Bendahara sudah ada! Hanya boleh 1 bendahara per RT.');
                }
            }
        }

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rt_id = Auth::user()->rt_id;
        $user = User::findOrFail($id);

        // Validasi hanya bisa hapus anggota di RT yang sama
        if ($user->rt_id !== $rt_id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // Validasi role (hanya anggota, sekretaris, bendahara yang bisa dihapus)
        if (in_array($user->role, ['anggota', 'sekretaris', 'bendahara'])) {

            // Hapus QR code file jika ada
            if ($user->qr_code && Storage::disk('public')->exists($user->qr_code)) {
                Storage::disk('public')->delete($user->qr_code);
            }

            $user->delete();
            return back()->with('success', ucfirst($user->role) . ' berhasil dihapus.');
        }

        return back()->with('error', 'Tidak dapat menghapus user ini.');
    }



    /**
 * API: Get User QR Code
 */
public function getUserQR($userId)
{
    $user = User::findOrFail($userId);

    if (!$user->qr_code || !Storage::disk('public')->exists($user->qr_code)) {
        return response()->json(['error' => 'QR tidak ditemukan'], 404);
    }

    return response()->json([
        'qr' => asset('storage/' . $user->qr_code),
        'token' => $user->qr_token
    ]);
}
}
