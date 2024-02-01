<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TIUController extends Controller
{
    public function getData($num){
        $data = json_decode(file_get_contents(public_path() . "/assets/js/tiu5.json"), true);

        return response()->json([
            'quest' => $data,
            'num' => $num
        ]);
    }
    public static function index(Request $request, $path, $test, $selection)
    {
        $packet = Packet::where('test_id','=',20)->where('status','=',1)->first();


        return view('test.tiu', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet
        ]);
    }

    public static function store(Request $request)
    {

        $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();

        $kunci_jawab = ['b','e','d','d','e','d','e','a','e','c','e','d','e','c','e','c','b','c','d','d','a','e','d','d','e','a','b','e','c','b'];
        $opsi_jawaban = $request->jawaban; 
        $raw_score = 0;
        for($i=0;$i<30;$i++){
            if($opsi_jawaban[$i+1] == strtoupper($kunci_jawab[$i])){
                $raw_score++;
            }
        }
        $array = array();
        $standart_score = self::standart_score($raw_score);
        $array['jawaban'] = $opsi_jawaban;
        $array['rs'] = $raw_score;
        $array['ws'] = $standart_score;

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($array);
        $result->save();

        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);
    }

    public static function standart_score($value){
        if($value < 3){ $score = 0; }
        else if($value >= 3 && $value < 5){ $score = 1;}
        else if($value >= 5 && $value < 7){ $score = 2;}
        else if($value == 7 ){ $score = 3;}
        else if($value >= 8  && $value <10 ){ $score = 4;}
        else if($value >= 10 && $value <12 ){ $score = 5;}
        else if($value >= 12 && $value <14 ){ $score = 6;}
        else if($value == 14){ $score = 7;}
        else if($value >= 15 && $value <17 ){ $score = 8;}
        else if($value > 17 && $value <19 ){ $score = 9;}
        else if($value == 19){ $score = 10;}
        else if($value >=20 && $value <22 ){ $score = 11;}
        else if($value >=22  && $value <24 ){ $score = 12;}
        else if($value >=24  && $value <26 ){ $score = 13;}
        else if($value == 26){ $score = 14;}
        else if($value >=27  && $value <29 ){ $score = 15;}
        else if($value >=29  && $value <=30 ){ $score = 16;}

        return $score;
    }
}
