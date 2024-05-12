<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CF3KPKController extends Controller
{
    public function getData($part,$id){
        if($part == 1){
            $soal = self::soals('cf3kpk-1');
        }else if($part == 2){
            $soal = self::soals('cf3kpk-2');
        }
        else if($part == 3){
            $soal = self::soals('cf3kpk-3');
        }
        else if($part == 4){
            $soal = self::soals('cf3kpk-4');
        }
        else{
            $soal = null;
        }
        // $decode_soal = json_decode($soal,true);


        return response()->json([
            'quest' => $soal,
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
                $idx = 92;
            }else if($request->part > 4){
                abort(404);
            }
            else{
                $part = $request->part;
                $idx = $test->id + $part;
            }
            $soal = Packet::where('test_id','=',$idx)->where('part','=',$part)->where('status','=',1)->first();
            

            return view('test.cf3kpk',[ 
                'total_part' => 4,
                'part' => $part,               
                'path' => $path,
                'soal' => $soal,
                'test' => $test,
                'selection' => $selection,
            ]);
        }

    }

    public static function store(Request $request)
    {
        // dd($request->all());
        $part = $request->part;
        $jawaban = $request->jawaban;
        if($part == 1){
            $kunci = ["B","C","B","D","E","B","D","B","F","C","A","D","F"];
        }else if($part == 2){
            $all_jumlah = array();
            for($i=1;$i <= count($jawaban);$i++){
                $jawaban_dcode[$i] = json_decode($jawaban[$i],true);
                
                $jumlah[$i] = 0;
                for($j=0;$j < count($jawaban_dcode[$i]);$j++){
                    $jumlah[$i] += $jawaban_dcode[$i][$j];
                }
                $all_jumlah[$i] = $jumlah[$i];
            }

            $jawaban = $all_jumlah;
            $kunci =[18,17,9,20,5,9,18,18,9,5,18,12,24,5];
        }else if($part == 3){
            $kunci = ["E","E","E","B","C","D","E","E","D","A","D","C","E"];
        }else{
            $kunci = ["B","A","D","D","A","B","C","D","A","D"];
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }
        // dd($request->all());

        if($request->path == 'cf3kpk'){
            $packet_id = 106;
            $cek_duplicate = TesTemporary::select('part')->where('part',$request->part)->first();
            if($cek_duplicate == null){

                $cek_koreksi['benar'] = $save_value;

                $tes_temporary = new TesTemporary;
                $tes_temporary->id_user = Auth::user()->id;
                $tes_temporary->test_id = $request->test_id;
                $tes_temporary->packet_id = $request->packet_id;
                $tes_temporary->json = json_encode($request->jawaban);
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
                                    ->orderBy('part','asc')
                                    ->get();
                                    
                for($ls=0;$ls < count($array);$ls++){
                    $last_save['cf3kpk-'.($ls+1)]['score'] = $array[$ls]->result_temp;
                    $last_save['cf3kpk-'.($ls+1)]['jawaban'] = $array[$ls]->json;
                }   
                
                $benar_1 = json_decode($last_save['cf3kpk-1']['score'],true);
                $benar_2 = json_decode($last_save['cf3kpk-2']['score'],true);
                $benar_3 = json_decode($last_save['cf3kpk-3']['score'],true);
                $benar_4 = json_decode($last_save['cf3kpk-4']['score'],true);
                $jumlah_benar = $benar_1['benar'] + $benar_2['benar'] + $benar_3['benar'] + $benar_4['benar'];
                
                $last_save['total_benar'] = $jumlah_benar;
                $last_save['iq'] = self::norma($jumlah_benar,'cfit3biq');
                $last_save['persentil'] = self::norma($jumlah_benar,'cfit3bp');

                $save = self::resultStore($request,$last_save,$packet_id);
                DB::delete('delete from test_temporary where id_user ='.Auth::user()->id);
                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);


            }
            else{
                return redirect('/tes/cf3kpk?part='.$part_next);
            }
        }
        else{

            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);

        }

    }
    
    public static function norma($data,$table)
    {
        $select = DB::table('norma_aptitude')->select($table)->where('nilai', $data)->first();
        return $select->$table;
    }

    public static function resultStore($request,$last_save,$packet_id)
    {
        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $packet_id;
        $result->result = json_encode($last_save);
        $result->save();
    }

    public static function soals($test_name){
        $result = array();

        if($test_name == 'cf3kpk-1'){
            for ($i=0;$i<=12;$i++){
                $soal[$i]="cf3kpk1-".($i+1)."_0.png";
                $jawaba[$i]="cf3kpk1-".($i+1)."_1.png";
                $jawabb[$i]="cf3kpk1-".($i+1)."_2.png";
                $jawabc[$i]="cf3kpk1-".($i+1)."_3.png";
                $jawabd[$i]="cf3kpk1-".($i+1)."_4.png";
                $jawabe[$i]="cf3kpk1-".($i+1)."_5.png";
                $jawabf[$i]="cf3kpk1-".($i+1)."_6.png";    
            }
        }
        else if($test_name == 'cf3kpk-2'){
            for ($i=1;$i<=14;$i++){
                $soal = null;
                $jawaba[$i-1]="cf3kpk2-".$i."_0.png";
                $jawabb[$i-1]="cf3kpk2-".$i."_2.png";
                $jawabc[$i-1]="cf3kpk2-".$i."_4.png";
                $jawabd[$i-1]="cf3kpk2-".$i."_6.png";
                $jawabe[$i-1]="cf3kpk2-".$i."_8.png";
                $jawabf[$i]= null;    
            }
        }
        else if($test_name == 'cf3kpk-3'){
            for ($i=0;$i<=12;$i++){
                $soal[$i]="cf3kpk3_".($i+1).".png";
                $jawaba[$i]="cf3kpk3_".($i+1)."a.png";
                $jawabb[$i]="cf3kpk3_".($i+1)."b.png";
                $jawabc[$i]="cf3kpk3_".($i+1)."c.png";
                $jawabd[$i]="cf3kpk3_".($i+1)."d.png";
                $jawabe[$i]="cf3kpk3_".($i+1)."e.png";
                $jawabf[$i]="cf3kpk3_".($i+1)."f.png";
            }
        }
        else if($test_name == 'cf3kpk-4'){
            for ($i=0;$i<=10;$i++){
                $soal[$i] = "cf3kpk4_".($i+1).".png";
                $jawaba[$i] = "cf3kpk4_".($i+1)."a.png";
                $jawabb[$i] = "cf3kpk4_".($i+1)."b.png";
                $jawabc[$i] = "cf3kpk4_".($i+1)."c.png";
                $jawabd[$i] = "cf3kpk4_".($i+1)."d.png";
                $jawabe[$i] = "cf3kpk4_".($i+1)."e.png";   
                $jawabf[$i]= null;    

            }
        }
        $result[0]['soal'] = $soal;
        $result[1]['jawabA'] = $jawaba;
        $result[2]['jawabB'] = $jawabb;
        $result[3]['jawabC'] = $jawabc;
        $result[4]['jawabD'] = $jawabd;
        $result[5]['jawabE'] = $jawabe;
        $result[6]['jawabF'] = $jawabf;

        return $result;
    }

}
