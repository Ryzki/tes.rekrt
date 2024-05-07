<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Test\CF3KPKController;
use App\Models\Test;
use App\Models\Hasil;
use App\Models\Selection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MMPIController;
use App\Http\Controllers\CFIT2AController;
use App\Http\Controllers\Cfit3aController;
use App\Http\Controllers\Test\ISTController;
use App\Http\Controllers\Test\NVAController;
use App\Http\Controllers\Test\SDIController;
use App\Http\Controllers\Test\TIUController;
use App\Http\Controllers\Test\WPTController;
use App\Http\Controllers\Test\EPPSController;
use App\Http\Controllers\Test\LAFFController;
use App\Http\Controllers\Test\MBTIController;
use App\Http\Controllers\Test\MSDTController;
use App\Http\Controllers\Test\RMIBController;
use App\Http\Controllers\Test\TikiController;
use App\Http\Controllers\Test\TikiDController;
use App\Http\Controllers\Test\TIKIMController;
use App\Http\Controllers\Test\CFIT3BController;
use App\Http\Controllers\Test\DISC24Controller;
use App\Http\Controllers\Test\DISC40Controller;
use App\Http\Controllers\Test\PapikostickController;

class TestController extends Controller
{    
    public function __construct()
    {
        //inisialisasi parameter
        $test_tikid = ['tikid','tikid-1','tikid-2','tikid-3','tikid-4','tikid-5','tikid-6','tikid-7','tikid-8','tikid-9'];
        $test_tikim = ['tikim','tikim-1','tikim-2','tikim-3','tikim-4','tikim-5','tikim-6','tikim-7','tikim-8','tikim-9','tikim-10','tikim-11','tikim-12'];
        $test_cfit3b = ['cfit3b','cfit3b-1','cfit3b-2','cfit3b-3','cfit3b-4'];
        $test_cf3kpk = ['cf3kpk','cf3kpk-1','cf3kpk-2','cf3kpk-3','cf3kpk-4'];
        $test_cfit2A = ['cfit2a','cfit2a-1','cfit2a-2','cfit2a-3','cfit2a-4','cfit2b','cfit2b-1','cfit2b-2','cfit2b-3','cfit2b-4'];
        $test_nva = ['numerik-40','verbal60','abstraksi24','16p'];
        $test_laff = ['logika_verbal','apm','a1'];
        $test_mmpi = ['mmpi','mmpi-2','mmpi-3'];

        $this->test_tikid = $test_tikid;
        $this->test_nva = $test_nva;
        $this->test_laff = $test_laff;
        $this->test_cfit3b = $test_cfit3b;
        $this->test_cfit2A = $test_cfit2A;
        $this->test_mmpi = $test_mmpi;
        $this->test_tikim = $test_tikim;
        $this->test_cf3kpk = $test_cf3kpk;
    }
    /**
     * Display test page
     * 
     * @param  string $path
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request, $path)
    {
        // Variables
        // $part = null;
        $selection = false;

        // Get the test
        $test = Test::where('code','=',$path)->firstOrFail(); // Get tes
        // $check = Auth::user()->role_id == 6 ? Hasil::where('id_user','=',Auth::user()->id)->first() : null; // Check
        
        // Get the selection
        if(Auth::user()->role_id == role('applicant')) {
            $selection = Selection::where('user_id','=',Auth::user()->id)->first();
        }
            
        // Test DISC 40
        if($path == 'disc-40-soal')
            return DISC40Controller::index($request, $path, $test, $selection);
        // Test DISC 24
        elseif($path == 'disc-24-soal' || $path == 'disc-24-soal-1')
            return DISC24Controller::index($request, $path, $test, $selection);
        // Test Papikostick
        elseif($path == 'papikostick' || $path == 'papikostick-1')
            return PapikostickController::index($request, $path, $test, $selection);
        // Test SDI
        elseif($path == 'sdi')
            return SDIController::index($request, $path, $test, $selection);
        // Test MSDT
        elseif($path == 'msdt' || $path == 'msdt-1')
            return MSDTController::index($request, $path, $test, $selection);
        // Test Assesment
        elseif($path == 'assesment' || $path == 'assesment-01')
            return \App\Http\Controllers\Test\AssesmentController::index($request, $path, $test, $selection);
		// Test Assesment 1.0
        elseif($path == 'assesment-10' || $path == 'assesment-11')
            return \App\Http\Controllers\Test\Assesment10Controller::index($request, $path, $test, $selection);
		// Test Assesment 2.0
        elseif($path == 'assesment-20' || $path == 'assesment-21')
            return \App\Http\Controllers\Test\Assesment20Controller::index($request, $path, $test, $selection);
        // Test IST
        elseif($path == 'ist')
            // return \App\Http\Controllers\Test\ISTController::index($request, $path, $tes, $selection);
            return ISTController::try($request, $path, $test, $selection);
        // Test RMIB
        elseif($path == 'rmib' || $path == 'rmib-2')
            return RMIBController::index($request, $path, $test, $selection);
        elseif($path == 'tiu')
            return TIUController::index($request, $path, $test, $selection);
        elseif($path == 'wpt')
            return WPTController::index($request, $path, $test, $selection);
        elseif($path == 'mbti')
            return MBTIController::index($request, $path, $test, $selection);
        elseif($path == 'tiki' )
            return TikiController::index($request, $path, $test, $selection);
        elseif($path == 'tiki-1' || $path =='tiki-2'|| $path =='tiki-3'|| $path =='tiki-4'|| $path =='tiki-5'|| $path =='tiki-6'|| $path =='tiki-7'|| $path =='tiki-8'|| $path =='tiki-9'|| $path =='tiki-10'|| $path =='tiki-11')
            return TikiController::indexPart($request, $path, $test, $selection);
        elseif($path == 'cfit3A')
            return Cfit3aController::index($request, $path, $test, $selection);
        elseif($path == 'cfit3A-1' || $path == 'cfit3A-2' || $path == 'cfit3A-3' || $path == 'cfit3A-4')
            return Cfit3aController::indexPart($request, $path, $test, $selection);
        elseif($path == 'epps')
            return EPPSController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_tikid))
            return TikiDController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_tikim))
            return TIKIMController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_nva))
            return NVAController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_laff))
            return LAFFController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_cfit3b))
            return CFIT3BController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_cfit2A))
            return CFIT2AController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_mmpi))
            return MMPIController::index($request, $path, $test, $selection);
        elseif(in_array($path, $this->test_cf3kpk))
            return CF3KPKController::index($request, $path, $test, $selection);
        else
            abort(404);
    }
    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Tes DISC 40
        if($request->path == 'disc-40-soal')
            return \App\Http\Controllers\Test\DISC40Controller::store($request);
        // Tes DISC 24
        elseif($request->path == 'disc-24-soal' || $request->path == 'disc-24-soal-1')
            return \App\Http\Controllers\Test\DISC24Controller::store($request);
        // Tes Papikostick
        elseif($request->path == 'papikostick' || $request->path == 'papikostick-1')
            return \App\Http\Controllers\Test\PapikostickController::store($request);
        // Tes SDI
        elseif($request->path == 'sdi')
            return \App\Http\Controllers\Test\SDIController::store($request);
        // Tes MSDT
        elseif($request->path == 'msdt' || $request->path == 'msdt-1')
            return \App\Http\Controllers\Test\MSDTController::store($request);
        // Tes Assesment
        elseif($request->path == 'assesment' || $request->path == 'assesment-01')
            return \App\Http\Controllers\Test\AssesmentController::store($request);
        // Tes Assesment 1.0
        elseif($request->path == 'assesment-10' || $request->path == 'assesment-11')
            return \App\Http\Controllers\Test\Assesment10Controller::store($request);
        // Tes Assesment 2.0
        elseif($request->path == 'assesment-20' || $request->path == 'assesment-21')
            return \App\Http\Controllers\Test\Assesment20Controller::store($request);
        // Tes IST        
        elseif($request->path == 'ist')
            return \App\Http\Controllers\Test\ISTController::store($request);
        // Tes RMIB
        elseif($request->path == 'rmib' || $request->path == 'rmib-2')
            return \App\Http\Controllers\Test\RMIBController::store($request);
        // Tes TIU
        elseif($request->path == 'tiu')
            return \App\Http\Controllers\Test\TIUController::store($request);
        elseif($request->path == 'wpt')
            return \App\Http\Controllers\Test\WPTController::store($request);
        elseif($request->path == 'mbti')
            return \App\Http\Controllers\Test\MBTIController::store($request);
        elseif($request->path == 'tiki')
            return \App\Http\Controllers\Test\TikiController::store($request);
        elseif($request->path == 'tiki-1' ||$request->path == 'tiki-2'||$request->path == 'tiki-3'||$request->path == 'tiki-4'||$request->path == 'tiki-5'||$request->path == 'tiki-6'||$request->path == 'tiki-7'||$request->path == 'tiki-8'||$request->path == 'tiki-9'||$request->path == 'tiki-10'||$request->path == 'tiki-11')
            return \App\Http\Controllers\Test\TikiController::storePart($request);
        elseif($request->path == 'cfit3A')
            return \App\Http\Controllers\Cfit3aController::store($request);
        elseif($request->path == 'epps')
            return \App\Http\Controllers\Test\EPPSController::store($request);
        elseif(in_array($request->path,$this->test_nva))
            return \App\Http\Controllers\Test\NVAController::store($request);
        elseif(in_array($request->path,$this->test_tikid))
            return \App\Http\Controllers\Test\TikiDController::store($request);
        elseif(in_array($request->path,$this->test_laff))
            return LAFFController::store($request);
        elseif(in_array($request->path,$this->test_tikim))
            return TIKIMController::store($request);
        elseif(in_array($request->path,$this->test_cfit3b))
            return CFIT3BController::store($request);
        elseif(in_array($request->path,$this->test_cfit2A))
            return CFIT2AController::store($request);
        elseif(in_array($request->path,$this->test_mmpi))
            return MMPIController::store($request);
        elseif(in_array($request->path,$this->test_cf3kpk))
            return CF3KPKController::store($request);
    }

    /**
     * Delete test temp
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Tes IST
        if($request->path == 'ist')
            return \App\Http\Controllers\Test\ISTController::delete($request);
    }

    public function try(Request $request)
    {
        // Variables
        $path = 'ist';
        $part = null;
        $selection = false;

        // Get tes
        $tes = Tes::where('path','=',$path)->firstOrFail(); // Get tes
        $check = Auth::user()->role_id == 6 ? Hasil::where('id_user','=',Auth::user()->id)->first() : null; // Check
        
        // Jika role pelamar
        if(Auth::user()->role_id == 4){
        	$akun = Pelamar::where('id_user','=',Auth::user()->id)->first(); // Get akun
            $selection = $akun ? selection::where('id_pelamar','=',$akun->id_pelamar)->first() : false; // Get selection
        }

        // Return
        return \App\Http\Controllers\Test\ISTController::try($request, $path, $tes, $selection);
    }
}