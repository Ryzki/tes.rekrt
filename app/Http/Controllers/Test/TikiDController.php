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

        
    }
    public static function index(Request $request, $path, $test, $selection)
    {
        $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();

        return view('test.tikid.tikid',[
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,

        ]);
    }


}
