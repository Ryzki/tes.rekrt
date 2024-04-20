<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public static function store(Request $request)
    {
        $jawaban = $request->jawaban;
        
        if($request->packet_id == 75){
            $kunci = strtoupper('BCBDEBDBFCBBE');
        }
        else if($request->packet_id == 76){
            dd(count($jawaban[1]));
            for($i=1;$i <= count($jawaban);$i++){
                for($j=0;$j <= count($jawaban[$i]);$j++){
                    $jumlah[$i] += $jawaban[$i][$j];
                }
            }
            dd($jumlah);
            $kunci = [18,17,9,20,18,9,18,18,9,10,17,12,6,3];
        }
        else if($request->packet_id == 77){
            $kunci = strtoupper('EEEBCDEEAAFCC');
        }
        else if($request->packet_id == 78){
            $kunci = strtoupper('BADDABCDAD');
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }
        
        if($request->path == 'cfit3b'){
            $cek_duplicate = TesTemporary::select('part')->where('part',$request->part)->first();
            if($cek_duplicate == null){

                $cek_koreksi['benar'] = $save_value;

                $tes_temporary = new TesTemporary;
                $tes_temporary->id_user = Auth::user()->id;
                $tes_temporary->test_id = $request->test_id;
                $tes_temporary->packet_id = $request->packet_id;
                $tes_temporary->json = json_encode($jawaban);
                $tes_temporary->part = $request->part;
                $tes_temporary->result_temp = json_encode($cek_koreksi);
                $tes_temporary->save();
            }
            
            $part_next = ($request->part) + 1; //next part soal
            if($part_next >= 5){
                $test_id = $request->test_id;
                $last_save = array();
                $array = TesTemporary::select('result_temp','json')->where('id_user',Auth::user()->id)
                                    ->where('test_id',$test_id)
                                    ->orderBy('part','desc')
                                    ->get();
                                    
                for($ls=0;$ls < count($array);$ls++){
                    $last_save['cfit3b-'.($ls+1)]['score'] = $array[$ls]->result_temp;
                    $last_save['cfit3b-'.($ls+1)]['jawaban'] = $array[$ls]->json;
                }


                dd($last_save);

                

                DB::delete('delete from test_temporary where id_user ='.Auth::user()->id);
                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);

            }
            else{
                return redirect('/tes/cfit3b?part='.$part_next);
            }
        }


    }

    public static function norma($data,$table)
    {
        $select = DB::table('norma_aptitude')->select($table)->where('nilai', $data)->first();
        return $select->$table;
    }
}
