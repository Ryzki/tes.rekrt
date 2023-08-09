<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Hasil;
use App\Models\Selection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Test\ISTController;
use App\Http\Controllers\Test\SDIController;
use App\Http\Controllers\Test\MSDTController;
use App\Http\Controllers\Test\RMIBController;
use App\Http\Controllers\Test\DISC24Controller;
use App\Http\Controllers\Test\DISC40Controller;
use App\Http\Controllers\Test\PapikostickController;

class TestController extends Controller
{    
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
        elseif($path == 'disc-24-soal')
            return DISC24Controller::index($request, $path, $test, $selection);
        // Test Papikostick
        elseif($path == 'papikostick')
            return PapikostickController::index($request, $path, $test, $selection);
        // Test SDI
        elseif($path == 'sdi')
            return SDIController::index($request, $path, $test, $selection);
        // Test MSDT
        elseif($path == 'msdt')
            return MSDTController::index($request, $path, $test, $selection);
        // Test Assesment
        elseif($path == 'assesment')
            return \App\Http\Controllers\Test\AssesmentController::index($request, $path, $test, $selection);
		// Test Assesment 1.0
        elseif($path == 'assesment-10')
            return \App\Http\Controllers\Test\Assesment10Controller::index($request, $path, $test, $selection);
		// Test Assesment 2.0
        elseif($path == 'assesment-20')
            return \App\Http\Controllers\Test\Assesment20Controller::index($request, $path, $test, $selection);
        // Test IST
        elseif($path == 'ist')
            // return \App\Http\Controllers\Test\ISTController::index($request, $path, $tes, $selection);
            return ISTController::try($request, $path, $test, $selection);
        // Test RMIB
        elseif($path == 'rmib' || $path == 'rmib-2')
            return RMIBController::index($request, $path, $test, $selection);
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
        elseif($request->path == 'disc-24-soal')
            return \App\Http\Controllers\Test\DISC24Controller::store($request);
        // Tes Papikostick
        elseif($request->path == 'papikostick')
            return \App\Http\Controllers\Test\PapikostickController::store($request);
        // Tes SDI
        elseif($request->path == 'sdi')
            return \App\Http\Controllers\Test\SDIController::store($request);
        // Tes MSDT
        elseif($request->path == 'msdt')
            return \App\Http\Controllers\Test\MSDTController::store($request);
        // Tes Assesment
        elseif($request->path == 'assesment')
            return \App\Http\Controllers\Test\AssesmentController::store($request);
        // Tes Assesment 1.0
        elseif($request->path == 'assesment-10')
            return \App\Http\Controllers\Test\Assesment10Controller::store($request);
        // Tes Assesment 2.0
        elseif($request->path == 'assesment-20')
            return \App\Http\Controllers\Test\Assesment20Controller::store($request);
        // Tes IST        
        elseif($request->path == 'ist')
            return \App\Http\Controllers\Test\ISTController::store($request);
        // Tes RMIB
        elseif($request->path == 'rmib' || $request->path == 'rmib-2')
            return \App\Http\Controllers\Test\RMIBController::store($request);
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