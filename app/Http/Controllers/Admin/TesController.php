<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use Illuminate\Http\Request;

class TesController extends Controller
{    
    /**
     * Menampilkan data tes
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Data tes
        $tes = Tes::all();

        // View
        return view('admin/tes/index', [
            'tes' => $tes
        ]);
    }
}