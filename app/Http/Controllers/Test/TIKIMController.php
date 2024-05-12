<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TIKIMController extends Controller
{
    public function getdata($part,$id){
        $soal = Question::where('packet_id','=',92)->where('number','=',$part)->first();
        $decode_soals = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soals,
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
            $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();

            if($path == 'tikim'){
                if($request->part == null){
                    $part = 1;
                    $idx = 79;
                }else{
                    $part = $request->part;
                    $idx = $test->id + $part;
                }
            }else{
                $idx = $packet->test_id;
                $part = $packet->part;
            }
        $soal = Packet::select('id','amount')->where('test_id','=',$idx)->where('part','=',$part)->first();

        return view('test.tiki.tikim',[
            'part' => $part,
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'soal' => $soal,
            'total_part' => 12
        ]);

        }
    }

    public static function store(Request $request)
    {
        
        $umur = cekUmur();
        $jawaban = $request->jawaban;
        $part = $request->part;
        if($part == 1){
            $kunci=strtoupper("daccdcdeccdebcabeddedccbbdebbaedcccaabed");
        }else if($part == 2){
            $kunci = [5,34,18,18,34,40,33,36,20,36,5,10,18,17,17,10,20,10,33,36,18,10,20,34,10,40,17,12,36,12];
        }else if($part==3){
            $Kunci = [9,5,5,5,3,9,5,3,5,12,3,5,9,12,10,10,5,10,9,6,9,3,5,5,10,12,5,9,6,5,10,12,6,6,9,12,10,5,6,6];
        }else if($part==4){
            $kunci = [1,8,1,2,1,8,4,4,2,16,4,16,4,8,4,8,1,8,2,4,1,1,4,1,16,1];
        }else if($part==5){
            $kunci=strtoupper("cebccaadbcbbcaceadbdac");
        }else if($part==6){
            $kunci=strtoupper("SSBBSBSSSBSSBSSSSBBBSBSBBBBSSBBSSBBSSSBSBBBBBSSSBBSBBBBBBBBBBBBBBBBBBBBBBBBSSBBBSSSSBSSBBSBBSBSSSBBB");
        }else if($part==7){
            $kunci=strtoupper("baaaabcaddaddaddadcbacbbcaccad");
        }else if($part==8){
            $kunci=strtoupper("dbaebecaddcbbeaadaacdcbebeecdc");
        }else if($part==9){
            $kunci = [34,34,20,20,36,10,9,12,34,20,17,9,9,36,20,17,24,18,20,40,12,9,18,36,20,10,36,33,12,17];
        }
        else if($part==10){
            $kunci=strtoupper("BSSSBSBBBSSBBSSSBSBBSBSSBSBSBBBSSBSSSBBBSSBSSSBBSSSBBBBSBBSB");
        }
        else if($part==11){
            $kunci=strtoupper("dbbcbcaadbbaaaaacaacbdbaaccdaadaddcdcdddddddcbcd");
        }
        else if($part == 12){
            $kunci=strtoupper("CBCDBADCABDABAABDBDBAAACDDCACDBCBCDDCBBBBABBDAABDBABADCCACCC");
        }
        else{
            abort(404);
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }

        if($request->path == 'tikim'){
            $cek_duplicate = TesTemporary::select('part')->where('part',$request->part)->first();
            if($cek_duplicate == null){

                $cek_koreksi['benar'] = $save_value;
                $cek_koreksi['iq'] = self::norma_tiki($save_value,$request->part,$umur);

                

                $tes_temporary = new TesTemporary;
                $tes_temporary->id_user = Auth::user()->id;
                $tes_temporary->test_id = $request->test_id;
                $tes_temporary->packet_id = $request->packet_id;
                $tes_temporary->json = json_encode($request->jawaban);
                $tes_temporary->part = $request->part;
                $tes_temporary->result_temp = json_encode($cek_koreksi);
                $tes_temporary->save();
            }
            
            $part_next = $part + 1; //next part soal
            if($part_next >= 13){
                $test_id = $request->test_id;
                $last_save = array();
                $array = TesTemporary::select('result_temp','json','part')->where('id_user',Auth::user()->id)
                                    ->where('test_id',$test_id)
                                    ->orderBy('part','asc')
                                    ->get();
                                    
                for($ls=0;$ls < count($array);$ls++){
                    $last_save['tikim-'.($array[$ls]->part)]['score'] = $array[$ls]->result_temp;
                    $last_save['tikim-'.($array[$ls]->part)]['jawaban'] = $array[$ls]->json;
                }   

                $result = new Result;
                $result->user_id = Auth::user()->id;
                $result->company_id = Auth::user()->attribute->company_id;
                $result->test_id = $request->test_id;
                $result->packet_id = $request->packet_id;
                $result->result = json_encode($last_save);
                $result->save();
                
               
                DB::delete('delete from test_temporary where id_user ='.Auth::user()->id);
                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);

            }
            else{
                return redirect('/tes/tikim?part='.$part_next);
            }
        }
        else{
            $cek_koreksi['benar'] = $save_value;
                $cek_koreksi['iq'] = self::norma_tiki($save_value,$request->part,$umur);

                dd($cek_koreksi);
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

    
    public static function norma_tiki($data,$part,$usia)
    {
        if($usia>=19){
            $tabel_tes='tikimb'.$part;
            $category='tikimb'.$part;
        }   
        //umur dibawah 19 tahun
        else{
            $tabel_tes='tikima'.$part;
            $category='tikima'.$part;
        }  

        $select = DB::table('norma_aptitude')->select($tabel_tes)->where('nilai', $data)->first();
        return $select->$tabel_tes;
    }

}
