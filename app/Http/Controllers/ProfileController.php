<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $team = [
            [
                'nama' => 'Bapak Kepala Sekolah',
                'jabatan' => 'Penanggung Jawab',
                'foto' => 'images/team/kepsek.jpg', // Ganti dengan foto asli
            ],
            [
                'nama' => 'Nama Ketua TEFA',
                'jabatan' => 'Direktur TEFA',
                'foto' => 'images/team/ketua.jpg', // Ganti dengan foto asli
            ],
            [
                'nama' => 'Nama Kabeng',
                'jabatan' => 'Manajer Operasional',
                'foto' => 'images/team/kabeng.jpg', // Ganti dengan foto asli
            ],
            [
                'nama' => 'Nama Bendahara',
                'jabatan' => 'Keuangan',
                'foto' => 'images/team/bendahara.jpg', // Ganti dengan foto asli
            ],
        ];

        return view('profile', compact('team'));
    }
}
