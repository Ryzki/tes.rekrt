<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\TempTes;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TikiController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
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
        }else{
            $part = $request->part;
        }

        $packet = Packet::where('test_id','=',23)->where('status','=',1)->first();
        $soal = Packet::select('amount')->where('test_id','=',23)->where('part','=',$part)->first();
        // $soal = Question::where('packet_id','=',38)->where('number','=',$part)->first();
        // $decode_soal = json_decode($soal->description,true);

        // dd($decode_soal);
        // $jumlah_soal = count($decode_soal[0]['soal']);
        // $soal_c = self::soal();
        $jumlah_soal = $soal->amount;
        
        
        return view('test.tiki.tiki', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $jumlah_soal,
            'part'=>$part
        ]);
    }

    public static function store(Request $request){
        
        $path = $request->path;
        $test_id = $request->test_id;
        $selection = $request->selection;
        $part = ($request->part) + 1;
        $packet_id = $request->packet_id;
        $jawaban = json_encode($request->jawaban);

        dd($request->jawaban);
        
        $save_sementara = new TesTemporary;
        $save_sementara->id_user = Auth::user()->id;
        $save_sementara->test_id = $test_id;
        $save_sementara->packet_id = $packet_id;
        $save_sementara->json = $jawaban;
        $save_sementara->part = $part;
        $save_sementara->save();

        $temporary_result1 = self::getTempt1(Auth::user()->id,$test_id,$packet_id,$part);
        // dd($temporary_result1);

        if($part >= 12){
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
        }
        else{
            
            return redirect('/tes/tiki?part='.$part);
        }
    }

    public static function getTempt1($id_user,$test_id,$packet_id,$part){
        $save_value_1 = 0;
        $data = TesTemporary::where('id_user',$id_user)->where('test_id',$test_id)
                            ->where('packet_id',$packet_id)->where('part',$part)
                            ->first();
        //soal part 1
        $kunci_1=strtoupper("bccabddadabcbdbccdaaacdaabbccabbbdababad");
        $jawaban = json_decode($data->json,true);
        for($i=1;$i<=strlen($kunci_1);$i++){
            if($kunci_1[$i-1]==$jawaban[$i]){
                $save_value_1++;
            }
        }
           
        return $save_value_1;
    }

    public static function kunci_2($id_user,$test_id,$packet_id,$part){
        $save_value_1 = 0;
        $data = TesTemporary::where('id_user',$id_user)->where('test_id',$test_id)
                            ->where('packet_id',$packet_id)->where('part',$part)
                            ->first();

        $kunc[1]=5;
        $kunc[2]=9;
        $kunc[3]=40;
        $kunc[4]=18;
        $kunc[5]=34;
        $kunc[6]=18;
        $kunc[7]=18;
        $kunc[8]=5;
        $kunc[9]=10;
        $kunc[10]=10;
        $kunc[11]=17;
        $kunc[12]=40;
        $kunc[13]=10;
        $kunc[14]=36;
        $kunc[15]=36;
        $kunc[16]=20;
        $kunc[17]=34;
        $kunc[18]=20;
        $kunc[19]=36;
        $kunc[20]=34;
        $kunc[21]=10;
        $kunc[22]=20;
        $kunc[23]=17;
        $kunc[24]=12;
        $kunc[25]=33;
        $kunc[26]=36;

    return $kunc;
    }



    
    
    
    
    
    
    
    
    
    
    
        
    
}
