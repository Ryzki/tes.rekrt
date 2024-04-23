<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CFIT2AController extends Controller
{

    public function getData($part,$id){
        $soal = Question::where('packet_id','=',79)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);


        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }
    public function getDataB($part,$id){
        $soal = Question::where('packet_id','=',84)->where('number','=',$part)->first();
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

            if($test->id == 65 || $test->id == 70){
                if($request->part == null){
                    $part = 1;
                    $idx = $test->id + 1;
                }else if($request->part > 4){
                    abort(404);
                }
                else{
                    $part = $request->part;
                    $idx = $test->id + $part;
                }
                $soal = Packet::where('test_id','=',$idx)->where('part','=',$part)->where('status','=',1)->first();
                
            }
            else{
                $soal = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
                $part = $soal->part;
            }
            
            return view('test.cfit2a.cfit2a', [
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
        if($request->packet_id == 80 || $request->packet_id == 85){
            $kunci = strtoupper('CDACBEBCCCDA');
        }
        else if($request->packet_id == 81 || $request->packet_id == 86){
            $all_jumlah = array();
            for($i=1;$i <= count($jawaban);$i++){
                $jawaban_dcode[$i] = json_decode($jawaban[$i],true);
                $jumlah[$i] = 0;
                for($j=0;$j < count($jawaban_dcode[$i]);$j++){
                    $jumlah[$i] += $jawaban_dcode[$i][$j];
                }
                $all_jumlah[$i] = $jumlah[$i];
                
            }
            $kunci = [2,4,8,1,4,4,1,16,8,4,4,4,1,8];
            $jawaban = $all_jumlah;
        }
        else if($request->packet_id == 82 || $request->packet_id == 87){
            $kunci = strtoupper('ACBECABDEABB');
        }
        else if($request->packet_id == 83 || $request->packet_id == 88){
            $kunci = strtoupper('CABDCCAB');
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }
        
        if($request->path == 'cfit2a' || $request->path == 'cfit2b'){
            $packet_id = $request->path == 'cfit2a' ? 79 : 84;
            $namess = $request->path == 'cfit2a' ? 'cfit2a' : 'cfit2b';
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
                    $last_save[$namess.'-'.($ls+1)]['score'] = $array[$ls]->result_temp;
                    $last_save[$namess.'-'.($ls+1)]['jawaban'] = $array[$ls]->json;
                }   
                
                $benar_1 = json_decode($last_save[$namess.'-1']['score'],true);
                $benar_2 = json_decode($last_save[$namess.'-2']['score'],true);
                $benar_3 = json_decode($last_save[$namess.'-3']['score'],true);
                $benar_4 = json_decode($last_save[$namess.'-4']['score'],true);
                $jumlah_benar = $benar_1['benar'] + $benar_2['benar'] + $benar_3['benar'] + $benar_4['benar'];
                
                $table = self::cekIQ();
 
                $last_save['total_benar'] = $jumlah_benar;
                $last_save['iq'] = self::norma($jumlah_benar,$table);

                $save = self::resultStore($request,$last_save,$packet_id);
                DB::delete('delete from test_temporary where id_user ='.Auth::user()->id);
                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);

            }
            else{
                return redirect('/tes/'.$namess.'?part='.$part_next);
            }
        }
        else{
            $table = self::cekIQ();
            $last_save['benar'] = $save_value;
            $last_save['jawaban'] = $request->jawaban;
            $last_save['iq'] = self::norma($save_value,$table);

            $save = self::resultStore($request,$last_save,$request->packet_id);
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);

        }
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


    public static function cekIQ(){
        $cu = cek_umur_koma();
        if($cu>'1' && $cu<'7.9'){$tabel_tes='cfit2iqa';} 
        if($cu>'7.8' && $cu<'8.3'){$tabel_tes='cfit2iqb';} 
        if($cu>'8.2' && $cu<'9.3'){$tabel_tes='cfit2iqc';} 
        if($cu>'8.8' && $cu<'9.3'){$tabel_tes='cfit2iqd';} 
        if($cu>'9.2' && $cu<'9.9'){$tabel_tes='cfit2iqe';} 
        if($cu>'9.8' && $cu<'10.3'){$tabel_tes='cfit2iqf';} 
        if($cu>'10.2' && $cu<'10.9'){$tabel_tes='cfit2iqg';} 
        if($cu>'10.8' && $cu<'11.3'){$tabel_tes='cfit2iqh';} 
        if($cu>'11.2' && $cu<'11.9'){$tabel_tes='cfit2iqi';} 
        if($cu>'11.8' && $cu<'12.3'){$tabel_tes='cfit2iqj';} 
        if($cu>'12.2' && $cu<'12.9'){$tabel_tes='cfit2iqk';} 
        if($cu>'12.8' && $cu<'13.3'){$tabel_tes='cfit2iql';} 
        if($cu>'13.2' && $cu<'13.9'){$tabel_tes='cfit2iqm';} 
        if($cu>'13.8' && $cu<'99'){$tabel_tes='cfit2iqn';}
        
        return $tabel_tes;
    }

    public static function norma($data,$table)
    {
        $select = DB::table('norma_aptitude')->select($table)->where('nilai', $data)->first();
        return $select->$table;
    }
}
