<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Cfit3aController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',51)->where('number','=',$part)->first();
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
            $idx = 38;
        }else if($request->part > 4){
            abort(404);
        }
        else{
            $part = $request->part;
            $idx = $test->id + $part;
        }
        $soal = Packet::where('test_id','=',$idx)->where('part','=',$part)->where('status','=',1)->first();
        

        return view('test.cfit.cfit3A', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'soal' => $soal,
            'part' => $part
        ]);
    }

    public static function indexPart(Request $request, $path, $test, $selection)
    {
        $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
   
        $part = $packet->part;
        $soal = Packet::where('test_id','=',$test->id)->where('part','=',$part)->where('status','=',1)->first();
        // $soal = Packet::select('amount')->where('test_id','=',$test->id)->where('part','=',$part)->first();
        // dd($soal->amount);

        return view('test.cfit.cfit3A-part', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'soal' => $soal,
            'part'=>$part
        ]);
    }

    public static function store(Request $request)
    {
        $test_id = $request->test_id;
        $part = $request->part;
        $nilai1 = self::koreksi($request,$part);
        
        $jawaban = json_encode($request->jawaban);

        $save_sementara = new TesTemporary;
        $save_sementara->id_user = Auth::user()->id;
        $save_sementara->test_id = $test_id;
        $save_sementara->packet_id = $request->packet_id;
        $save_sementara->json = $jawaban;
        $save_sementara->part = $request->part;
        $save_sementara->result_temp = json_encode($nilai1);
        $save_sementara->save();
        

        if($part+1 > 4){
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
        }
        else{
            return redirect('/tes/cfit3A?part='.$part+1);
        }
    }

   public static function koreksi($request,$part){
        $kunci_part = self::kunci($part);
        $save_value = 0;
      
        for($i=1;$i <= count($request->jawaban);$i++){
            if($kunci_part[$i] == $request->jawaban[$i]){
                $save_value++;
            }
        }

        $array_save_koreksi = array();
        $array_save_koreksi['benar'] = $save_value;
        $array_save_koreksi['score'] = self::convert_nilai($save_value);
        // $array_save_koreksi['score'] = $koreksi_ws;
        return $array_save_koreksi;
   }

   public static function convert_nilai($nilai){
        $array_nilai = array();

        if($nilai == 0){
            $iq = 0;
            $bp = 0;
        }
        else if($nilai <= 10){
            $iq = 40;
            if($nilai == 1){$bp = 0;}
            else if($nilai == 2){$bp = 5;}
            else if($nilai == 3){$bp = 10;}
            else if($nilai == 4){$bp = 15;}
            else if($nilai == 5){$bp = 20;}
            else if($nilai == 6){$bp = 25;}
            else if($nilai == 7){$bp = 28;}
            else if($nilai == 8){$bp = 29;}
            else if($nilai == 9){$bp = 30;}
            else if($nilai == 10){$bp = 32;}
        }
        else if($nilai < 13){
            $iq = 50;
            if($nilai == 11){$bp = 34;}
            else if($nilai == 12){$bp = 35;}
        }
        else if( $nilai == 13){
            $bp = 36;
            $iq = 57;
        }
        else if( $nilai == 14){
            $bp = 38;
            $iq = 62;
        }
        else if( $nilai == 15){
            $bp = 39;
            $iq = 67;
        }
        else if( $nilai == 16){
            $bp = 42;
            $iq = 72;
        }
        else if( $nilai == 17){
            $bpq = 43;
            $iq = 78;
        }
        else if( $nilai == 18){
            $bp = 44;
            $iq = 82;
        }
        else if( $nilai == 19){
            $bp = 46;
            $iq = 85;
        }
        else if( $nilai == 20){
            $bp = 47;
            $iq = 87;
        }
        else if( $nilai == 21){
            $bp = 49;
            $iq = 89;
        }
        else if( $nilai == 22){
            $bp = 50;
            $iq = 92;
        }
        else if( $nilai == 23){
            $bp = 52;
            $iq = 94;
        }
        else if( $nilai == 24){
            $bp = 53;
            $iq = 97;
        }
        else if( $nilai == 25){
            $bp = 55;
            $iq = 100;
        }
        else if( $nilai == 26){
            $bp = 57;
            $iq = 101;
        }
        else if( $nilai == 27){
            $bp = 58;
            $iq = 104;
        }
        else if( $nilai == 28){
            $bp = 59;
            $iq = 107;
        }
        else if( $nilai == 29){
            $bp = 61;
            $iq = 111;
        }
        else if( $nilai == 30){
            $bp = 62;
            $iq = 116;
        }
        else if( $nilai == 31){
            $bp = 64;
            $iq = 121;
        }
        else if( $nilai == 32){
            $bp = 66;
            $iq = 126;
        }
        else if( $nilai == 33){
            $bp = 67;
            $iq = 131;
        }
        else if( $nilai == 34){
            $bp = 69;
            $iq = 138;
        }
        else if( $nilai == 35){
            $bp = 70;
            $iq = 146;
        }
        else if( $nilai == 36){
            $bp = 71;
            $iq = 153;
        }
        else if( $nilai == 37){
            $bp = 73;
            $iq = 161;
        }
        else if( $nilai == 38){
            $bp = 75;
            $iq = 168;
        }
        else if( $nilai == 39){
           $bp = 76;
           $iq = 175; 
        }
        else if( $nilai <= 44){
            $iq = 182;
            if($nilai == 40){$bp = 78;}
            else if($nilai == 41){$bp = 79;}
            else if($nilai == 42){$bp = 82;}
            else if($nilai == 43){$bp = 83;}
            else if($nilai == 44){$bp = 84;}
        }
        else if($nilai <=47 ){
            $iq = 207;
            if($nilai == 45){$bp = 85;}
            else if($nilai == 46){$bp = 87;}
            else if($nilai == 47){$bp = 88;}
        }
        else if($nilai <= 50){
            $iq = 219;
            if($nilai == 48){$bp = 89;}
            else if($nilai == 49){$bp = 92;}
            else if($nilai == 50){$bp = 100;}
        }
        else{
            $iq = 0;
            $bp = 0;
        }
       $array_nilai['bp'] = $bp;
       $array_nilai['iq'] = $iq;
       return $array_nilai;
   }
    public static function kunci($part)
    {
        if($part ==1){
            $key[1]="B";
            $key[2]="C";
            $key[3]="B";
            $key[4]="D";
            $key[5]="E";
            $key[6]="B";
            $key[7]="D";
            $key[8]="B";
            $key[9]="F";
            $key[10]="C";
            $key[11]="A";
            $key[12]="D";
            $key[13]="F";
        }
        elseif($part==2){
            $key[1]=18;
            $key[2]=17;
            $key[3]=9;
            $key[4]=20;
            $key[5]=5;
            $key[6]=9;
            $key[7]=18;
            $key[8]=18;
            $key[9]=9;
            $key[10]=5;
            $key[11]=18;
            $key[12]=12;
            $key[13]=24;
            $key[14]=5;
    
        }elseif($part==3){
            $key[1]="E";
            $key[2]="E";
            $key[3]="E";
            $key[4]="B";
            $key[5]="C";
            $key[6]="D";
            $key[7]="E";
            $key[8]="E";
            $key[9]="D";
            $key[10]="A";
            $key[11]="D";
            $key[12]="C";
            $key[13]="E";
        }elseif($part==4){
            $key[1]="B";
            $key[2]="A";
            $key[3]="D";
            $key[4]="D";
            $key[5]="A";
            $key[6]="B";
            $key[7]="C";
            $key[8]="D";
            $key[9]="A";
            $key[10]="D";
        }

        return $key;
    }
}
