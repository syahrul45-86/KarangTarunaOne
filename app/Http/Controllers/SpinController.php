<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SettingRT;

class SpinController extends Controller
{

   public function index()
   {
       $rtId = auth()->user()->rt_id; // RT sekretaris yang sedang login

       $anggota = User::where('rt_id', $rtId)->get();
       
       $setting = SettingRT::firstOrCreate(['rt_id' => $rtId]);
       $savedMembers = json_decode($setting->spin_members, true) ?? [];

       return view('sekretaris.spin.index', compact('anggota', 'savedMembers'));
   }

   public function save(Request $request)
   {
       try {
           $rtId = auth()->user()->rt_id;
           $setting = SettingRT::updateOrCreate(
               ['rt_id' => $rtId],
               ['spin_members' => json_encode($request->members ?? [])]
           );
           return response()->json(['success' => true, 'count' => count($request->members ?? [])]);
       } catch (\Exception $e) {
           return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
       }
   }

}
