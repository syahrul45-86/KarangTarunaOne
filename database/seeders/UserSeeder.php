<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rt;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan sudah ada minimal 2 RT
        $rt1 = Rt::firstOrCreate(['nama_rt' => 'RT 01'], ['rw' => 'RW 05']);
        $rt2 = Rt::firstOrCreate(['nama_rt' => 'RT 02'], ['rw' => 'RW 05']);

        // Superadmin
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'rt_id' => null,
            ]
        );

        // Admin RT1
        User::firstOrCreate(
            ['email' => 'admin1@example.com'],
            [
                'name' => 'Admin RT 01',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'rt_id' => $rt1->id,
            ]
        );

        // Bendahara RT1
        User::firstOrCreate(
            ['email' => 'bendahara1@example.com'],
            [
                'name' => 'Bendahara RT 01',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
                'rt_id' => $rt1->id,
            ]
        );

        // Sekretaris RT1
        User::firstOrCreate(
            ['email' => 'sekretaris1@example.com'],
            [
                'name' => 'Sekretaris RT 01',
                'password' => Hash::make('password'),
                'role' => 'sekretaris',
                'rt_id' => $rt1->id,
            ]
        );

        // Anggota RT1
        User::firstOrCreate(
            ['email' => 'anggota1@example.com'],
            [
                'name' => 'Anggota RT 01',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'rt_id' => $rt1->id,
            ]
        );

        // Admin RT2
        User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name' => 'Admin RT 02',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'rt_id' => $rt2->id,
            ]
        );
    }
}
