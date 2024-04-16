<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LAFFController extends Controller
{
    public function getData($test,$part,$id){
        if($test == 'logika_verbal'){$test_id = 71;}
        else if($test == 'apm'){$test_id = 72;}

        $soal = Question::where('packet_id','=',$test_id)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part,
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            $part = 1;
            $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();

            $soal = Packet::select('amount')->where('test_id','=',$test->id)->where('part','=',$part)->first();

            return view('test.'.$path,[
                'part' => $part,
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'packet' => $packet,
                'jumlah_soal' => $soal->amount,
            ]);
        }
    }
    public static function store(Request $request)
    {
        $jawaban = $request->jawaban;
        if($request->packet_id == 71){
            $kunci=strtoupper("CDBCBDBBACABBCB");
            $table='logika';
        }
        if($request->packet_id == 72){
            $kunci=strtoupper("EAGDCAFAHDEFBABDFGCHHGFCGBGEFEDHEACB");
            $table='apm';
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }

        $last_save['jawaban'] = $request->jawaban;
        $last_save['benar'] = $save_value;

        if($request->packet_id != 72){
            $select = DB::table('norma_aptitude')->select($table)->where('nilai', $save_value)->first();
            $last_save['iq'] = $select->$table;
        }
        else{
            if($save_value<5)$ws=0;
            else if($save_value<12){$ws=5;}
            else if($save_value<17){$ws=10;}
            else if($save_value<22){$ws=25;}
            else if($save_value<27){$ws=50;}
            else if($save_value<31){$ws=75;}
            else if($save_value<33){$ws=90;}
            else {$ws=95;}

            $last_save['iq'] = $ws;
        }

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($last_save);
        $result->save();

        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
    }
}
