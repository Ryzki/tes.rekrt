<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TikiDController extends Controller
{
    public function getData($part,$id)
    {
        $soal = Question::where('packet_id','=',57)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
        
    }
    public static function index(Request $request, $path, $test, $selection)
    {
        if($request->part == null){
            $part = 1;
            $idx = 44;
        }else{
            $part = $request->part;
            $idx = $test->id + $part;
        }
        $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
        $soal = Packet::select('amount')->where('test_id','=',$idx)->where('part','=',$part)->first();

        return view('test.tikid.tikid',[
            'part' => $part,
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $soal->amount,
        ]);
    }


}
