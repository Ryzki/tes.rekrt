<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CactController extends Controller
{

    public function getSoal($path,$part,$id){

        $data = json_decode(file_get_contents(public_path() . "/assets/js/cact.json"), true);
        if($path == 'eas4'){        
            $soal = $data['eas4'];
            for($i=0;$i<150;$i++){
                $soal[0]['soal'][$i] = $data['eas4']['jawabA'][$i].' - '.$data['eas4']['jawabB'][$i];
            }
            
        }else if($path == 'eas5'){  
            $soal = $data['eas5'];
            // for($i=0;$i<50;$i++){
            //     $soal[0]['soal'][$i] = $data['eas5']['soal'][$i];
            // }
        }else if($path == 'frt'){   $soal = $data['frt'];
        }else if($path == 'cact'){  $soal = $data['cact'];
        }else{                      $soal = $data['c2'];
        }

        return response()->json([
            'quest' => $soal,
            'num' => $id,
            'part'=> 1
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {

        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            $data = json_decode(file_get_contents(public_path() . "/assets/js/cact.json"), true);
            $soal = Packet::select('id','test_id','amount','part','name')->where('name','=',$path)->where('part','=',1)->where('status','=',1)->first();
    
            if($path == 'eas4' || $path == 'eas5'){
                $path_file = 'eas';
            }
            else if($path == 'frt'){
                $path_file = 'frt';
            }
            else if($path == 'cact'){
                $path_file = 'cact';
            }
            else if($path == 'c2'){
                $path_file = 'c2';
            }
            else{
                abort(404);
            }
            
            return view('test.cact.'.$path_file,[ 
                'soal' => $soal,
                'part' => $soal->part,
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
            ]);
        }
        
    }

    public static function store(Request $request)
    {
        dd($request->all());
        $path = $request->path;
        $jawaban = $request->jawaban;
        if($path == 'eas4'){
            $kunci=strtoupper("SSBBSBBBBBSBBSSBBBBBBBBSBSSSSBSBBBBSBBSSSBBSBSSBSBSSBSSBBBBSBBSBBBSSSBBBSBSBSSSSBBSBSSBBBBBSSSBSBBSSSSSBSSSSSSSSBBBSBBSBBSSBSBSSSBBSSBBBSBBSBBBSSSBBBB");

        }
        else if($path == 'eas4'){
            $kunci=strtoupper("35432454244635357564246453544456475545744625765677");
            
        }
        else if($path == 'frt'){
            $kunci=strtoupper("fdbbdcbdbeebcdedeaebfacfccdfbcdecbfadbfabdaeb");
            
        }
        else if($path == 'cact'){
            $kunci=strtoupper("1321323231133321121331213333323223133231");
            
        }
        else if($path == 'c2'){
            $kunci=strtoupper("dbceaaaebeadbcbcecdddeaacebacbbcdceadbed");
            
        }
        else{
            abort(404);
        }


        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }

        // dd($save_value);

        $last_save['benar'] = $save_value;
        $last_save['ws'] = self::koreksi($path,$save_value);

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($last_save);
        $result->save();
        
        dd($save_value);
    }

    public static function koreksi($path,$nilai){
        if($path == 'c2'){
            if($nilai<8)$ws=1;
            else if($nilai<15)$ws=2;
            else if($nilai<30)$ws=3;
            else if($nilai<38)$ws=4;
            else $ws=5;
        }
        else if($path == 'eas4'){
            if($nilai<59)$ws=1;
            else if($nilai<77)$ws=2;
            else if($nilai<114)$ws=3;
            else if($nilai<132)$ws=4;
            else $ws=5;
        }
        else if($path == 'eas5'){
            if($nilai<7)$ws=1;
            else if($nilai<17)$ws=2;
            else if($nilai<35)$ws=3;
            else if($nilai<44)$ws=4;
            else $ws=5;
        }
        else if($path == 'frt'){
            if($nilai<8)$ws=1;
            else if($nilai<15)$ws=2;
            else if($nilai<30)$ws=3;
            else if($nilai<38)$ws=4;
            else $ws=5;
        }
        else{
            $select = DB::table('norma_aptitude')->select('cact')->where('nilai', $nilai)->first();
            $ws = $select->cact;
        }

        return $ws;

    }



}
