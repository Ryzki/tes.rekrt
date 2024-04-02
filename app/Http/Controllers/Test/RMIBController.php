<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RMIBController extends Controller
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
            $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
            $questions = $packet ? $packet->questions()->orderBy('number','asc')->get() : [];

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
            
            // Answers
            $array = [];
            $array['answers'] = $request->score;
            $array['occupations'] = $request->occupations;
    
            // Sort array answers by key
            foreach($array['answers'] as $key=>$answer) {
                ksort($array['answers'][$key]);
            }
    
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