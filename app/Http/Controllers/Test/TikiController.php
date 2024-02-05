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
        if($request->part == null){
            $part = 1;
        }else{
            $part = $request->part;
        }


        $packet = Packet::where('test_id','=',23)->where('status','=',1)->first();
        $soal = Packet::select('amount')->where('test_id','=',23)->where('part','=',$part)->first();
        // $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
        // $decode_soal = json_decode($soal->description,true);

        // dd($decode_soal);
        // $jumlah_soal = count($decode_soal[0]['soal']);
        // $soal_c = self::soal();
        $jumlah_soal = $soal->amount;
        
        
        return view('test.tiki.tiki', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $jumlah_soal
        ]);
    }

    public static function store(Request $request){
        
        $path = $request->path;
        $test_id = $request->test_id;
        $selection = $request->selection;
        $part = ($request->part) + 1;

        return redirect('/tes/tiki?part='.$part);
    }

    
    
    
    
    
    
    
    
    
    
    
        
    
}
