<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DISC24Controller extends Controller
{    
    /**
     * Display
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            // Get the packet and questions
            $packet = Packet::where('test_id','=',3)->where('status','=',1)->first();
            $questions = $packet ? $packet->questions()->orderBy('number','asc')->get() : [];
    
            // dd($questions);
    
            // View
            return view('test/'.$path, [
                'packet' => $packet,
                'path' => $path,
                'questions' => $questions,
                'selection' => $selection,
                'test' => $test,
            ]);

        }
    }

    public function getData($num){

        $questions = Question::with('packet')
                                ->whereHas('packet', function($query){
                                    return $query->where('test_id','=',3)->where('status','=',1);
                                })
                                ->where('number','=',$num)
                                ->orderBy('number', 'asc')
                                ->get();
        foreach($questions as $question) {
            $question->description = json_decode($question->description, true);
        }

        return response()->json([
            'quest' => $questions,
        ]);
    }

    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        $test_id = $request->test_id;
        $cek_test = existTest($test_id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }

        else{

            // Get the packet
            $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
            
            // Set array
            $array = [
                'dm' => $request->Dm,
                'im' => $request->Im,
                'sm' => $request->Sm,
                'cm' => $request->Cm,
                'bm' => $request->Bm,
                'dl' => $request->Dl,
                'il' => $request->Il,
                'sl' => $request->Sl,
                'cl' => $request->Cl,
                'bl' => $request->Bl
            ];
    
            //save jawaban berdasarkan yes(m) dan no(l)
            if($request->path == 'disc-24-soal-1'){
                for($i=1;$i<=24;$i++){
                    $array['answers']['m'][$i] = $request['y'.$i];
                    $array['answers']['l'][$i] = $request['n'.$i];
                }
            }
            if($request->path == 'disc-24-soal'){
                $array['answers']['m'] = $request->y;
                $array['answers']['l'] = $request->n;            
            }
    
            // dd($array['answers']);
    
            // $array['answers']['m'] = $request->y;
            // $array['answers']['l'] = $request->n;
    
            // Save the result
            $result = new Result;
            $result->user_id = Auth::user()->id;
            $result->company_id = Auth::user()->attribute->company_id;
            $result->test_id = $request->test_id;
            $result->packet_id = $request->packet_id;
            $result->result = json_encode($array);
            $result->save();
    
            // Return
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);
        }
    }
}