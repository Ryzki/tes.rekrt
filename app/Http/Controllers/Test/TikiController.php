<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\TempTes;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TikiController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);
        $jumlah_soal = count($decode_soal[0]['soal']);

        return response()->json([
            'quest' => $decode_soal,
            'jumlah_soal'=>$jumlah_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }


    public static function index(Request $request, $path, $test, $selection)
    {
        $packet = Packet::where('test_id','=',23)->where('status','=',1)->first();
       
        

        // $soal_c = self::soal();
        
        return view('test.tiki.tiki', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet
        ]);
    }

    public static function store(Request $request){
        
        dd($request->all());
    }

    
    
    
    
    
    
    
    
    
    
    
        
    
}
