<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CFIT3BController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',74)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);


        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }
    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            if($request->part == null){
                $part = 1;
                $idx = 61;
            }else if($request->part > 4){
                abort(404);
            }
            else{
                $part = $request->part;
                $idx = $test->id + $part;
            }
            $soal = Packet::where('test_id','=',$idx)->where('part','=',$part)->where('status','=',1)->first();
            
          
            return view('test.cfit3b.cfit3b', [
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'soal' => $soal,
                'part' => $part
            ]);
        }
    }
}
