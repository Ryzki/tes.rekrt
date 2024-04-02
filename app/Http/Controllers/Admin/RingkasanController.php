<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class RingkasanController extends Controller
{    
    /**
     * Menampilkan data ringkasan
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Data tes
        $tes = Tes::count();

        // Data paket soal
        $paket = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->orderBy('status','asc')->orderBy('paket_soal.id_tes','asc')->orderBy('paket_soal.part','asc')->count();

        // Data hasil
        $hasil = Hasil::join('users','hasil.id_user','=','users.id_user')->count();

        // View
        return view('admin/ringkasan/index', [
            'hasil' => $hasil,
            'paket' => $paket,
            'tes' => $tes,
        ]);
    }
}
