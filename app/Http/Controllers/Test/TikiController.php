<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\TempTes;
use App\Models\Question;
use App\Models\TesTemporary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            if($request->part == null){
                $part = 1;
                $idx = 24;
            }else{
                $part = $request->part;
                $idx = $test->id + $part;
            }
    
    
            $packet = Packet::where('test_id','=',$idx)->where('status','=',1)->first();
            $soal = Packet::select('amount')->where('test_id','=',$idx)->where('part','=',$part)->first();
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
        
    }

    public static function indexPart(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
       
            $part = $packet->part;
            $soal = Packet::select('amount')->where('test_id','=',$test->id)->where('part','=',$part)->first();
    
            $jumlah_soal = $soal->amount;
            return view('test.tiki.tikit-1', [
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'packet' => $packet,
                'jumlah_soal' => $jumlah_soal,
                'part'=>$part
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

            $path = $request->path;
            $test_id = $request->test_id;
            $selection = $request->selection;
            $packet_id = $request->packet_id;
            $jawaban = json_encode($request->jawaban);
      
            $save_sementara = new TesTemporary;
            $save_sementara->id_user = Auth::user()->id;
            $save_sementara->test_id = $test_id;
            $save_sementara->packet_id = $packet_id;
            $save_sementara->json = $jawaban;
            $save_sementara->part = $request->part;
            $save_sementara->save();
    
            $cek = self::kunci_2(Auth::user()->id,$test_id,$packet_id,$request->part,$jawaban);
    
            $part_next = ($request->part) + 1;
            if($part_next >= 12){
                //get temp pindahkan ke table result
                $last_save = array();
                $array = TesTemporary::select('result_temp')->where('id_user',Auth::user()->id)
                                    ->where('test_id',$test_id)
                                    ->orderBy('id','desc')
                                    ->get();
                for($ls=0;$ls < count($array);$ls++){
                    $last_save['tikit-'.($ls+1)] = $array[$ls]->result_temp;
                }

    
                $result = new Result;
                $result->user_id = Auth::user()->id;
                $result->company_id = Auth::user()->attribute->company_id;
                $result->test_id = $request->test_id;
                $result->packet_id = $request->packet_id;
                $result->result = json_encode($last_save);
                $result->save();
    
                //hapus temp
                DB::delete('delete from test_temporary where id_user ='.Auth::user()->id);
                return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
            }
            else{
    
                return redirect('/tes/tiki?part='.$part_next);
            }
        }
    }

    public static function storePart(Request $request){
        
        $path = $request->path;
        $test_id = $request->test_id;
        $selection = $request->selection;
        $packet_id = $request->packet_id;
        $jawaban = json_encode($request->jawaban);
        $cek_jawaban_all = self::kunci_2(Auth::user()->id,$test_id,$packet_id,$request->part, $jawaban);
        

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($cek_jawaban_all);
        $result->save();
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
    }

    public static function kunci_2($id_user,$test_id,$packet_id,$part, $jawa){
        $save_value_1 = 0;
        $save_value_2 = 0;
        $save_value_3 = 0;
        $save_value_4 = 0;
        $save_value_5 = 0;
        $save_value_6 = 0;
        $save_value_7 = 0;
        $save_value_8 = 0;
        $save_value_9 = 0;
        $save_value_10 = 0;
        $save_value_11 = 0;
        $data = TesTemporary::where('id_user',$id_user)->where('test_id',$test_id)
                            ->where('packet_id',$packet_id)->where('part',$part)
                            ->orderBy('id', 'desc')
                            ->first();
        // $ids = $data->id;
        $ids = $data != null ? $data->id : null;
        $jawaban = $data != null ? json_decode($data->json,true) : json_decode($jawa,true);
        
        if($part == 1){
            //40
            $kunci_1=strtoupper("bccabddadabcbdbccdaaacdaabbccabbbdababad");      
            $last_value1 = self::forGet($ids,$jawaban,$kunci_1,$save_value_1,1);

            return $last_value1;
        }
        else if($part == 2){
            //26
            $kunci_22=[5,9,40,18,34,18,18,5,10,10,17,40,10,36,36,20,34,20,36,34,10,20,17,12,33,36];
            $last_value2 = self::forGet($ids,$jawaban,$kunci_22,$save_value_2,2);

            return $last_value2;
        }
        else if($part == 3){
            //40
            $kunci_3=[9,5,5,5,3,9,5,3,5,12,3,5,9,12,10,10,5,10,9,6,9,3,5,5,10,12,5,9,6,5,10,12,6,6,9,12,10,5,6,6];
            $last_value3 = self::forGet($ids,$jawaban,$kunci_3,$save_value_3,3);
            return $last_value3;
        }
        else if($part == 4){
            //30
            $kunci_4=[6,40,9,18,12,17,9,18,24,34,6,24,9,48,20,5,12,36,5,6,40,5,33,6,3,6,9,18,48,17];
            $last_value4 = self::forGet($ids,$jawaban,$kunci_4,$save_value_4,4);
            return $last_value4;
        }
        else if($part == 5){
            //20
            $kunci_5=strtoupper("cbeeadeaddbcdbdaaebe");
            $last_value5 = self::forGet($ids,$jawaban,$kunci_5,$save_value_5,5);
            return $last_value5;
        }
        else if($part == 6){
            //100
            $kunci_6=strtoupper("SSBBSBSSSBSSBSSSSBBBSBSBBBBSSBBSSBBSSSBSBBBBBSSSBBSBBBBBBBBBBBBBBBBBBBBBBBBSSBBBSSSSBSSBBSBBSBSSSBBB");
            $last_value6 = self::forGet($ids,$jawaban,$kunci_6,$save_value_6,6);
            return $last_value6;
        }
        else if($part == 7){
            //30
            $kunci_7=strtoupper("baaaadbadacadddcacbabddcaccbda");
            $last_value7 = self::forGet($ids,$jawaban,$kunci_7,$save_value_7,7);
            return $last_value7;
        }
        else if($part == 8){
            //40
            $kunci_8=[34,17,6,5,40,20,5,10,20,6,12,12,33,40,20,40,24,10,12,9,20,17,36,9,36,17,34,18,33,20,18,18,24,20,10,36,40,24,18,3];
            $last_value8 = self::forGet($ids,$jawaban,$kunci_8,$save_value_8,8);
            return $last_value8;
        }
        else if($part == 9){
            //18
            $kunci_9=strtoupper("cabcedbecbacccedcb");
            $last_value9 = self::forGet($ids,$jawaban,$kunci_9,$save_value_9,9);
            return $last_value9;
        }
        else if($part == 10){
            //20
            $kunci_10=strtoupper("cbdeaecbccadbebeaadd");
            $last_value10 = self::forGet($ids,$jawaban,$kunci_10,$save_value_10,10);
            return $last_value10;
        }
        else if($part == 11){
            //60
            $kunci_11=strtoupper("CBCDBADCABDABAABDBDBAAACDDCACDBCBCDDCBBBBABBDAABDBABADCCACCC");
            $last_value11 = self::forGet($ids,$jawaban,$kunci_11,$save_value_11,11);
            return $last_value11;
        }

    }

    public static function forGet($id,$jawaban,$array_cek,$save_value,$part){
        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($array_cek[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }
        $array_save_koreksi = array();
        $koreksi_ws = self::koreksi($part,$save_value);
        $array_save_koreksi['benar'] = $save_value;
        $array_save_koreksi['score'] = $koreksi_ws;
        $array_save_koreksi['opsi_jawaban'] = $jawaban;


        if($id != null){
            $update_data = TesTemporary::find($id);
            $update_data->result_temp = json_encode($array_save_koreksi);
            $update_data->update();
        }
            
        return $array_save_koreksi;
    }

    public static function koreksi($part,$result){
        $hasil = 0;
        if($part == 1){
            if($result <= 4){       $hasil = 0;
            }else if($result<=7){   $hasil = 1;
            }else if($result==8){   $hasil = 2;
            }else if($result==9){   $hasil = 3;
            }else if($result==10){  $hasil = 4;
            }else if($result==11){  $hasil = 5;
            }else if($result==12){  $hasil = 6;
            }else if($result==13){  $hasil = 7;
            }else if( $result<=15){ $hasil = 8;
            }else if( $result==16){ $hasil = 9;
            }else if( $result<=18){ $hasil = 10;
            }else if( $result<=20){ $hasil = 11;
            }else if( $result==21){ $hasil = 12;
            }else if( $result<=23){ $hasil = 13;
            }else if( $result==24){ $hasil = 14;
            }else if( $result<=26){ $hasil = 15;
            }else if( $result==27){ $hasil = 16;
            }else if( $result<=29){ $hasil = 17;
            }else if( $result==30){ $hasil = 18;
            }else if( $result<=32){ $hasil = 19;
            }else if( $result==33){ $hasil = 20;
            }else if( $result==34){ $hasil = 21;
            }else if( $result<=36){ $hasil = 22;
            }else if( $result==37){ $hasil = 23;
            }else if( $result==38){ $hasil = 24;
            }else if( $result==39){ $hasil = 26;
            }else                 { $hasil = 28;
            }
        }
        else if($part == 2){
            if($result<=1){         $hasil = 4;
            }else if($result<=4){   $hasil = 5;
            }else if($result==5){   $hasil = 6;
            }else if($result==6){   $hasil = 7;
            }else if($result==7){   $hasil = 8;
            }else if($result<=9){   $hasil = 9;
            }else if($result==10){  $hasil = 11;
            }else if($result==11){  $hasil = 12;
            }else if($result==12){  $hasil = 13;
            }else if($result==13){  $hasil = 14;
            }else if($result==14){  $hasil = 15;
            }else if($result==15){  $hasil = 16;
            }else if($result==16){  $hasil = 17;
            }else if($result==17){  $hasil = 18;
            }else if($result==18){  $hasil = 19;
            }else if($result==19){  $hasil = 21;
            }else if($result==20){  $hasil = 22;
            }else if($result==21){  $hasil = 24;
            }else if($result==22){  $hasil = 25;
            }else if($result==23){  $hasil = 27;
            }else if($result==24){  $hasil = 29;
            }else                {  $hasil = 30;
            }
        }
        else if($part == 3){
            if($result <= 3){           $hasil = 0;
            }else if($result <= 6){     $hasil = 1;
            }else if($result <= 8){     $hasil = 2;
            }else if($result == 9){     $hasil = 3;
            }else if($result <= 11){    $hasil = 4;
            }else if($result <= 13){    $hasil = 5;
            }else if($result == 14){    $hasil = 6;
            }else if($result <= 16){    $hasil = 7;
            }else if($result <= 18){    $hasil = 8;
            }else if($result == 19){    $hasil = 9;
            }else if($result <= 21){    $hasil = 10;
            }else if($result == 22){    $hasil = 11;
            }else if($result <= 24){    $hasil = 12;
            }else if($result==25){      $hasil = 13;
            }else if($result==26){      $hasil = 14;
            }else if($result<=28){      $hasil = 15;
            }else if($result==29){      $hasil = 16;
            }else if($result==30){      $hasil = 17;
            }else if($result==31){      $hasil = 18;
            }else if($result==32){      $hasil = 19;
            }else if($result==33){      $hasil = 20;
            }else if($result==34){      $hasil = 22;
            }else if($result==35){      $hasil = 24;
            }else if($result==36){      $hasil = 25;
            }else if($result==37){      $hasil = 26;
            }else if($result==38){      $hasil = 28;
            }else                {      $hasil = 29;
            }
        }
        else if($part == 4){
            if($result<=1){         $hasil = 0;
            }else if($result==2){   $hasil = 3;
            }else if($result==3){   $hasil = 6;
            }else if($result==4){   $hasil = 7;
            }else if($result==5){   $hasil = 9;
            }else if($result==6){   $hasil = 10;
            }else if($result==7){   $hasil = 12;
            }else if($result==8){   $hasil = 13;
            }else if($result==9){   $hasil = 14;
            }else if($result==10){  $hasil = 16;
            }else if($result==11){  $hasil = 17;
            }else if($result<=13){  $hasil = 18;
            }else if($result==14){  $hasil = 19;
            }else if($result==15){  $hasil = 20;
            }else if($result<=17){  $hasil = 21;
            }else if($result==18){  $hasil = 22;
            }else if($result==19){  $hasil = 23;
            }else if($result<=21){  $hasil = 24;
            }else if($result<=23){  $hasil = 25;
            }else if($result==24){  $hasil = 26;
            }else if($result==25){  $hasil = 28;
            }else if($result==26){  $hasil = 29;
            }else                {  $hasil = 30;
            }
        }
        else if($part == 5){
            if($result == 0){            $hasil = 3;
            }else if($result == 1){      $hasil = 5;
            }else if($result == 2){      $hasil = 8;
            }else if($result == 3){      $hasil = 10;
            }else if($result == 4){      $hasil = 13;
            }else if($result == 5){      $hasil = 14;
            }else if($result == 6){      $hasil = 16;
            }else if($result == 7){      $hasil = 18;
            }else if($result == 8){      $hasil = 20;
            }else if($result == 9){      $hasil = 22;
            }else if($result == 10){     $hasil = 23;
            }else if($result == 11){     $hasil = 25;
            }else if($result == 12){     $hasil = 27;
            }else if($result == 13){     $hasil = 29;
            }else{                       $hasil = 30;
            }
        }
        else if($part == 6){
            if($result <= 21){              $hasil = 0;
            }else if($result <= 25){        $hasil = 1;
            }else if($result <= 28){        $hasil = 2;
            }else if($result == 29){        $hasil = 3;
            }else if($result <= 32){        $hasil = 4;
            }else if($result <= 34){        $hasil = 5;
            }else if($result <= 36){        $hasil = 6;
            }else if($result <= 38){        $hasil = 7;
            }else if($result <= 40){        $hasil = 8;
            }else if($result <= 43){        $hasil = 9;
            }else if($result <= 45){        $hasil = 10;
            }else if($result <= 48){        $hasil = 11;
            }else if($result <= 51){        $hasil = 12;
            }else if($result <= 53){        $hasil = 13;
            }else if($result <= 55){        $hasil = 14;
            }else if($result <= 57){        $hasil = 15;
            }else if($result <= 59){        $hasil = 16;
            }else if($result <= 62){        $hasil = 17;
            }else if($result <= 65){        $hasil = 18;
            }else if($result <= 69){        $hasil = 19;
            }else if($result <= 73){        $hasil = 20;
            }else if($result <= 78){        $hasil = 21;
            }else if($result <= 85){        $hasil = 22;
            }else if($result <= 90){        $hasil = 23;
            }else if($result <= 95){        $hasil = 24;
            }else if($result <= 98){        $hasil = 25;
            }else if($result == 99){        $hasil = 27;
            }else{                          $hasil = 29;
            }
        }
        else if($part == 7){
            if($result <= 3){       $hasil = 0;
            }else if($result==4){     $hasil = 2;
            }else if($result==5){     $hasil = 4;
            }else if($result==6){     $hasil = 5;
            }else if($result==7){     $hasil = 6;
            }else if($result==8){     $hasil = 7;
            }else if($result==9){     $hasil = 8;
            }else if($result==10){     $hasil =9 ;
            }else if($result==11){     $hasil = 10;
            }else if($result==12){     $hasil = 11;
            }else if($result==13){     $hasil = 12;
            }else if($result==14){     $hasil = 13;
            }else if($result==15){     $hasil = 14;
            }else if($result==16){     $hasil = 15;
            }else if($result==17){     $hasil = 16;
            }else if($result==18){     $hasil = 17;
            }else if($result==19){     $hasil = 18;
            }else if($result==20){     $hasil = 19;
            }else if($result==21){     $hasil = 20;
            }else if($result==22){     $hasil = 21;
            }else if($result==23){     $hasil = 22;
            }else if($result==24){     $hasil = 23;
            }else if($result==25){     $hasil = 24;
            }else if($result==26){     $hasil = 25;
            }else if($result==27){     $hasil = 27;
            }else if($result==28){     $hasil = 28;
            }else if($result==29){     $hasil = 29;
            }else{                     $hasil = 30;
            }
 
        }
        else if($part == 8){
            if($result == 0){       $hasil = 6;
            }else if($result==1){     $hasil = 8;
            }else if($result==2){     $hasil = 10;
            }else if($result==3){     $hasil = 11;
            }else if($result<=5){     $hasil = 12;
            }else if($result<=7){     $hasil = 13;
            }else if($result<=11){     $hasil = 14;
            }else if($result<=15){     $hasil = 15;
            }else if($result<=19){     $hasil = 16;
            }else if($result<=22){     $hasil = 17;
            }else if($result<=25){     $hasil = 18;
            }else if($result<=27){     $hasil = 19;
            }else if($result==28){     $hasil = 20;
            }else if($result<=30){     $hasil = 21;
            }else if($result<=32){     $hasil = 22;
            }else if($result==33){     $hasil = 23;
            }else if($result==34){     $hasil = 24;
            }else if($result==35){     $hasil = 25;
            }else if($result==36){     $hasil = 26;
            }else if($result==37){     $hasil = 27;
            }else if($result==38){     $hasil = 28;
            }else if($result==39){     $hasil = 29;
            }else{                     $hasil = 30;
            }
        }
        else if($part == 9){
            if($result == 0){       $hasil = 0;
            }else if($result==1){     $hasil = 2;
            }else if($result==2){     $hasil = 4;
            }else if($result==3){     $hasil = 6;
            }else if($result==4){     $hasil = 8;
            }else if($result==5){     $hasil = 10;
            }else if($result==6){     $hasil = 12;
            }else if($result==7){     $hasil = 14;
            }else if($result==8){     $hasil = 17;
            }else if($result==9){     $hasil = 19;
            }else if($result==10){     $hasil = 21;
            }else if($result==11){     $hasil = 23;
            }else if($result==12){     $hasil = 25;
            }else if($result==13){     $hasil = 28;
            }else{                     $hasil = 30;
            }
        }
        else if($part == 10){
            if($result == 0){       $hasil = 0;
            }else if($result==1){     $hasil = 2;
            }else if($result==2){     $hasil = 4;
            }else if($result==3){     $hasil = 6;
            }else if($result==4){     $hasil = 8;
            }else if($result==5){     $hasil = 9;
            }else if($result==6){     $hasil = 11;
            }else if($result==7){     $hasil = 13;
            }else if($result==8){     $hasil = 14;
            }else if($result==9){     $hasil = 16;
            }else if($result==10){     $hasil = 17;
            }else if($result==11){     $hasil = 19;
            }else if($result==12){     $hasil = 20;
            }else if($result==13){     $hasil = 22;
            }else if($result==14){     $hasil = 23;
            }else if($result==15){     $hasil = 25;
            }else if($result==16){     $hasil = 26;
            }else if($result==17){     $hasil = 28;
            }else{                     $hasil = 30;
            }
        }
        else if($part == 11){
            if($result <= 13){       $hasil = 0;
            }else if($result<=15){     $hasil = 1;
            }else if($result<=17){     $hasil = 2;
            }else if($result==18){     $hasil = 3;
            }else if($result<=20){     $hasil = 4;
            }else if($result<=22){     $hasil = 5;
            }else if($result<=24){     $hasil = 6;
            }else if($result<=26){     $hasil = 7;
            }else if($result<=28){     $hasil = 8;
            }else if($result<=30){     $hasil = 9;
            }else if($result<=32){     $hasil = 10;
            }else if($result==33){     $hasil = 11;
            }else if($result<=35){     $hasil = 12;
            }else if($result<=37){     $hasil = 13;
            }else if($result<=39){     $hasil = 14;
            }else if($result<=41){     $hasil = 15;
            }else if($result<=43){     $hasil = 16;
            }else if($result<=45){     $hasil = 17;
            }else if($result<=47){     $hasil = 18;
            }else if($result==48){     $hasil = 19;
            }else if($result<=50){     $hasil = 20;
            }else if($result<=52){     $hasil = 21;
            }else if($result<=54){     $hasil = 22;
            }else if($result<=56){     $hasil = 23;
            }else if($result<=58){     $hasil = 24;
            }else{                     $hasil = 25;
            }
        }

        return $hasil;
    }


    
    
    
    
    
    
    
    
    
    
    
        
    
}
