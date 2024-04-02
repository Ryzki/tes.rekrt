<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WPTController extends Controller
{
    public function getData($num){
        
        
        $data = json_decode(file_get_contents(public_path() . "/assets/js/wpt.json"), true);

        return response()->json([
            'quest' => $data,
            'num' => $num
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            $packet = Packet::where('test_id','=',21)->where('status','=',1)->first();

            return view('test.wpt', [
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'packet' => $packet
            ]);
        }

        
    }

    public static function store(Request $request){
        $test_id = $request->test_id;
        $cek_test = existTest($test_id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }

        else{
            $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
            $wpt_kunci = self::data();
            $raw_score = 0;
            $usia = $request->jawaban[51];
    
            for($id=1;$id<=50;$id++){
                if(in_array($request->jawaban[$id],$wpt_kunci[$id]) == true){
                    $raw_score++;
                }
            }
            $array = array();
            $rs = 0;
    
            if($usia>60){$rs+=5;}
            else if ($usia>54){$rs+=4;}
            else if ($usia>49){$rs+=4;}
            else if ($usia>39){$rs+=2;}
            else if ($usia>29){$rs+=1;}
    
            if($rs>29){$ws=5;}
            else if ($rs>24){$ws=4;}
            else if ($rs>14){$ws=3;}
            else if ($rs>14){$ws=2;}
            else $ws=1;
          
            $array['RS'] = $raw_score;
            $array['jawaban'] = $request->jawaban;
            $array['ws'] = $ws;
            $array['usia'] = $usia;
    
            $result = new Result;
            $result->user_id = Auth::user()->id;
            $result->company_id = Auth::user()->attribute->company_id;
            $result->test_id = $request->test_id;
            $result->packet_id = $request->packet_id;
            $result->result = json_encode($array);
    
            $result->save();
    
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);

        }
    }

    public static function data()
    {
        $wpt = [];
        $wpt[1]=["5"];
        $wpt[2]=["1"];
        $wpt[3]=["4"];
        $wpt[4]=["2"];
        $wpt[5]=["3"];
        $wpt[6]=["1"];
        $wpt[7]=["3"];
        $wpt[8]=["1/27"];
        $wpt[9]=["1"];
        $wpt[10]=["4"];
        $wpt[11]=["5"];
        $wpt[12]=["4000"];
        $wpt[13]=["1"];
        $wpt[14]=["3"];
        $wpt[15]=["3000"];
        $wpt[16]=["3"];
        $wpt[17]=["a", "A"];
        $wpt[18]=["14"];
        $wpt[19]=["1"];
        $wpt[20]=["2"];
        $wpt[21]=["20"];
        $wpt[22]=["s" , "S"];
        $wpt[23]=["3&4" , "4&3", "4-3", "4/3", "43", "34", "3-4", "3/4" ];
        $wpt[24]=["3"];
        $wpt[25]=["3"];
        $wpt[26]=["1"];
        $wpt[27]=["5"];
        $wpt[28]=["3"];
        $wpt[29]=["8"];
        $wpt[30]=["10"];
        $wpt[31]=["1/12"];
        $wpt[32]=["1"];
        $wpt[33]=["3"];
        $wpt[34]=["24"];
        $wpt[35]=["1/4" , "0,25", "0.25", ",25", ".25"];
        $wpt[36]=["27"];
        $wpt[37]=["0.1024", ",1024", ".1024", "0,1024"];
        $wpt[38]=["7&10", "10&7", "10/7", "10-7", "107", "710"];
        $wpt[39]=["3"];
        $wpt[40]=["2"];
        $wpt[41]=["1&4", "4&1", "4/1", "4-1", "41", "14", "1/4", "1-4"];
        $wpt[42]=["4&23", "23&4", "23/4", "23-4", "234", "423", "4/23", "4-23"];
        $wpt[43]=["0.44", ",44", ".44", "0,44"];
        $wpt[44]=["2"];
        $wpt[45]=["16000"];
        $wpt[46]=["4"];
        $wpt[47]=["3"];
        $wpt[48]=["500"];
        $wpt[49]=["1,2,3,5", "1235", "1-2-3-5"];
        $wpt[50]=["67"];

    return $wpt;
    }
}
