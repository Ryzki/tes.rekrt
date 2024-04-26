<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MMPIController extends Controller
{
    public function getData($test,$part,$id){
        $packet_id = Packet::select('id','name')->where('name','=',$test)->first();
        $soal = Question::where('packet_id','=',$packet_id->id)->where('number','=',$part)->first();
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
                $soal = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
                $part = $soal->part;
            
            
            return view('test.mmpi', [
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'soal' => $soal,
                'part' => $part
            ]);
        }
    }
    public static function store(Request $request)
    {
        dd($request->all());
    }
}
