<?php

namespace App\Http\Controllers\Test;

use App\Models\Tes;
use App\Models\Soal;
use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MSDTController extends Controller
{    
    /**
     * Display
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $test, $selection)
    {
        // Get the packet and questions
        $questions = Question::with('packet')
                            ->whereHas('packet', function($query) use ($test){
                                return $query->where('test_id','=',6)->where('status','=',1);
                            })->first();
        
        $quests_number = json_decode($questions->description, true);
        
        $packet_id = $questions->packet_id;
        // View
        return view('test/'.$path, [
            'quests_number' => $quests_number,
            'packet_id' => $packet_id,
            'questions' => $questions,
            'path' => $path,
            'selection' => $selection,
            'test' => $test,
        ]);
    }

    public function getData($num){

        $quest = Question::with('packet')
                                ->whereHas('packet', function($query){
                                    return $query->where('test_id','=',6)->where('status','=',1);
                                })->first();
        $quest->description = json_decode($quest->description, true);

        return response()->json([
            'quest' => $quest,
        ]);
    }

    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        //save the result into array
        if($request->path == 'msdt'){
            $hasil = $request->get('p');
        }
        if($request->path == 'msdt-1'){
            $hasil = $request->get('inputQ');
        }
        
        $arrayResultRow = array();
        $arrayResultColumn = array();
        $countResultA = array();
        $countResultB = array();
        //save jumlah A dan B
        $jumlah = [];
        //change into 8x8 matrix
        
        $twoDArray = array_chunk($hasil, 8);
        for($bk=0;$bk<=7;$bk++){
            //cek jumlah jawaban A dan B by kolom
            $arrayResultColumn[$bk] = array_column($twoDArray,$bk);
            $arrayResultColumn[$bk] = array_count_values($arrayResultColumn[$bk]);

            //cek jumlah jawaban A dan B by baris
            $arrayResultRow[$bk] = array_count_values($twoDArray[$bk]);
        }

        for($r=0;$r<=7;$r++){
            //array of jumlah jawaban A tiap kolom ke kanan
            $countResultA[$r] = $arrayResultRow[$r]['A'];

            //array of jumlah jawaban B tiap baris ke bawah
            $countResultB[$r] = $arrayResultColumn[$r]['B'];
        }

        
        // Get the packet and questions
        $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
        //koreksi nilai sesuai aturan
        $koreksi = [
            1 , 2 , 1 , 0 , 3 , -1 , 0 , -4 
        ];

        for ($i=0 ; $i<8 ; $i++)
        {
            $x = $countResultA[$i] + $countResultB[$i] + $koreksi[$i] ;
            array_push($jumlah,$x) ; 
        }

        $TO = $jumlah[2] + $jumlah[3] + $jumlah[6] + $jumlah[7] ; 
        $RO = $jumlah[1] + $jumlah[3] + $jumlah[5] + $jumlah[7] ; 
        $E = $jumlah[4] + $jumlah[5] + $jumlah[6] + $jumlah[7] ;
        $O = $jumlah[0] ; 

        /// MEMBERIKAN NILAI TO , RO , E , O 
        if ($TO >=0 && $TO <=29 ){
            $TO = 0 ; 
        }
        else if ($TO >=30 && $TO <=31){
            $TO = 0.6 ; 
        }
        else if ($TO == 32){
            $TO = 1.2 ; 
        }
        else if ($TO == 33) {
            $TO = 1.8 ; 
        }
        else if ($TO == 34){
            $TO = 2.4 ; 
        }
        else if ($TO == 35)
        {
            $TO = 3.0 ; 
        } 
        else if ($TO >=36 && $TO <= 37)
        {
            $TO = 3.6 ;
        }
        else
        {
            $TO = 4.0 ; 
        }

        
        if ($RO >=0 && $RO <=29 ){
            $RO = 0 ; 
        }
        else if ($RO >=30 && $RO <=31){
            $RO = 0.6 ; 
        }
        else if ($RO == 32){
            $RO = 1.2 ; 
        }
        else if ($RO == 33) {
            $RO = 1.8 ; 
        }
        else if ($RO == 34){
            $RO = 2.4 ; 
        }
        else if ($RO == 35)
        {
            $RO = 3.0 ; 
        } 
        else if ($RO >=36 && $RO <= 37)
        {
            $RO = 3.6 ;
        }
        else
        {
            $RO = 4.0 ; 
        }

        
        if ($E >=0 && $E <=29 ){
            $E = 0 ; 
        }
        else if ($E >=30 && $E <=31){
            $E = 0.6 ; 
        }
        else if ($E == 32){
            $E = 1.2 ; 
        }
        else if ($E == 33) {
            $E = 1.8 ; 
        }
        else if ($E == 34){
            $E = 2.4 ; 
        }
        else if ($E == 35)
        {
            $E = 3.0 ; 
        } 
        else if ($E >=36 && $E <= 37)
        {
            $E = 3.6 ;
        }
        else
        {
            $E = 4.0 ; 
        }

        if ($O >=0 && $O <=29 ){
            $O = 0 ; 
        }
        else if ($O >=30 && $O <=31){
            $O = 0.6 ; 
        }
        else if ($O == 32){
            $O = 1.2 ; 
        }
        else if ($O == 33) {
            $O = 1.8 ; 
        }
        else if ($O == 34){
            $O = 2.4 ; 
        }
        else if ($O == 35)
        {
            $O = 3.0 ; 
        } 
        else if ($O >=36 && $O <= 37)
        {
            $O = 3.6 ;
        }
        else
        {
            $O = 4.0 ; 
        }
        
        $final = ""; 
        
        if($TO > 2){
            if($RO > 2){
                if($E > 2){
                    $final = "Executive" ; 
                }
                else {
                    $final = "Compromiser" ;
                }
            }
            else
            {
                if($E > 2){
                    $final = "Benevolent Autocrat" ;
                }
                else{
                    $final = "Autocrat" ;
                }   
            }
        }
        else{
            if($RO > 2){
                if($E > 2){
                    $final = "Developer" ;
                }
                else
                {
                    $final = "Missionary" ;
                }
            }
            else
            {
                if($E > 2){
                    $final = "Bereaucrat" ;
                }
                else
                {
                    $final = "Deserter" ;
                }   
            }
        }
        
        // Result
        $array = array(
            'TO' => round($TO, 2),
            'RO' => round($RO, 2),
            'E' => round($E, 2),
            'O' => round($O, 2),
            'tipe' => $final,
            'answers' => $hasil
        );

        // Save the result
        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($array);
        $result->save();

        // Return
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);
    }
}
