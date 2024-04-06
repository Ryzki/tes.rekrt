<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TikiDController extends Controller
{
    public function getData($part,$id)
    {
        $soal = Question::where('packet_id','=',57)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
        
    }
    public static function index(Request $request, $path, $test, $selection)
    {
        if($request->part == null){
            $part = 1;
            $idx = 44;
        }else{
            $part = $request->part;
            $idx = $test->id + $part;
        }
        $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
        $soal = Packet::select('amount')->where('test_id','=',$idx)->where('part','=',$part)->first();

        return view('test.tikid.tikid',[
            'part' => $part,
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $soal->amount,
        ]);
    }

    public static function store(Request $request){

        $test_id = $request->test_id;
        $cek_test = existTest($test_id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }

        else{
            $path = $request->path;
            $test_id = $request->test_id;
            $selection = $request->selection;
            $packet_id = $request->packet_id;
            $jawaban = json_encode($request->jawaban);

            $cek_dulicate = TesTemporary::select('part')->where('part',$request->part)->first();
            if($cek_dulicate == null){
                //save temptory result
                $save_sementara = new TesTemporary;
                $save_sementara->id_user = Auth::user()->id;
                $save_sementara->test_id = $test_id;
                $save_sementara->packet_id = $packet_id;
                $save_sementara->json = $jawaban;
                $save_sementara->part = $request->part;
                $save_sementara->save();
            }
            //cek penilaian score here

            //
            $part_next = ($request->part) + 1; //next part soal
            if($part_next >= 10){
                $last_save = array();
                $array = TesTemporary::select('result_temp')->where('id_user',Auth::user()->id)
                                    ->where('test_id',$test_id)
                                    ->orderBy('part','desc')
                                    ->get();
                

                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
            }            
            else{
                return redirect('/tes/tikid?part='.$part_next);
            }
        }
    }

    public static function koreksi($part)
    {
        if($part == 1){
            $kunci=strtoupper("DEEDADACABBECCECEDBBCAECBDAAAD");
        }
        else if($part == 2){
            $kunci = [5,18,34,10,18,34,36,33,36,40,18,20,5,17,20,10,17,18,36,10,20,10,34,10,17,40,12,20,36,12];
        }
        else if($part == 3){
            $kunci = [8,1,2,8,1,4,2,1,4,16,8,16,8,4,4,4,8,2,1,4,1,16,4,1,1,1];
        }
        else if($part == 4){
            $kunci = [9,5,5,6,3,12,10,5,5,5,5,3,10,5,3,9,10,3,5,10,9,3,12,9,3,12,10,5,10,6,10,12,5,5,6,9,5,12,3,6];
        }
        else if($part == 5){
            $kunci = [40,36,33,40,9,34,36,40,12,48,20,20,36,10,20,10,17,5,10,10,20,36,10,34,6,34,36,12,18,3,6,36,24,20,36,40,12,33,10,20,33,6,10,20,18,17,20,5,34,9];
        }
        else if($part == 6){
            $kunci = [5,10,18,8,21,10,24,4,5,20,28,3,48,48,7,6,52,9,52,9]; 
        }
        else if($part == 7){
            $kunci=strtoupper("SSSSSBSSSBBBSBSSBSBBSSSSSBBSSSBSSSSBBBBBBBSSBBSSBBSBBBSBBBBB");
        }
        else if($part == 8){
            $kunci=strtoupper("2314545132121255432513132134253454243151154243252354231241234523315415342541235332145142532342124231542414532542251532314325241551534241");
        }
        else if($part == 9){
            $kunci=strtoupper("DBAEBECADDCBBEAADAACDCBEBEECDC");
        }

        return $kunci;
    }


}
