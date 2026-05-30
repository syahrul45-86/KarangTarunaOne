<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\IzinAbsensi;
use App\Models\AbsensiForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinAbsensiController extends Controller
{
    // Show form list for current user to submit izin
    public function index()
    {
        $now = now();
        $activeForms = AbsensiForm::where('rt_id', auth()->user()->rt_id)
            ->whereDate('tanggal', '>=', $now->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get()
            ->filter(function ($form) use ($now) {
                // Hilangkan dari daftar jika waktu kegiatan sudah selesai
                $waktuSelesai = \Carbon\Carbon::parse($form->tanggal . ' ' . $form->jam_selesai);
                if ($now->greaterThan($waktuSelesai)) {
                    return false;
                }

                // Hilangkan dari daftar jika user sudah mengajukan izin
                $sudahIzin = \App\Models\IzinAbsensi::where('form_id', $form->id)
                    ->where('user_id', auth()->id())
                    ->exists();
                
                return !$sudahIzin;
            });

        // Ambil izin yang sudah diajukan user ini
        $myIzin = IzinAbsensi::where('user_id', auth()->id())
            ->with('form')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('anggota.izin.index', compact('activeForms', 'myIzin'));
    }

    // Store izin (with optional image upload)
    public function store(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:absensi_forms,id',
            'alasan' => 'required|string|max:500',
            'foto_bukti' => 'nullable|image|max:4096',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'form_id' => $request->form_id,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ];

        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('izin_bukti', 'public');
            $data['foto_path'] = $path;
        }

        IzinAbsensi::create($data);

        return back()->with('success', 'Pengajuan izin berhasil dikirim, menunggu persetujuan.');
    }
}
