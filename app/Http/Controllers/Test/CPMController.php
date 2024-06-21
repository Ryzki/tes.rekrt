<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CPMController extends Controller
{
    public function getData($num)
    {
        $soal = self::soal();

        return response()->json([
            'quest' => $soal,
            'num' => $num
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $test, $selection)
    {
        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            // Get the packet and questions
            $packet = Packet::where('test_id','=',104)->where('status','=',1)->first();

            // View
            return view('test/cpm', [
                'packet' => $packet,
                'path' => $path,
                // 'questions' => $questions,
                'selection' => $selection,
                'test' => $test,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        // dd($request->all());
        $cek_hasil = self::cek_hasil($request->jawaban);
        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = 104;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($cek_hasil);
        $result->save();

        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
    }

    public static function cek_hasil($jawaban)
    {
        $kunci=strtoupper("deabfcfbacdedeafbacdfcebbfabacefdcde");
        $rs=0;
        for($i=1;$i<=36;$i++){
            if($jawaban[$i]==$kunci[$i-1]) $rs++;
        }
        $cu = cek_umur_koma();
        $ws=0;
        //cpm1 5.5
        if($rs>20 && $rs<36 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>18 && $rs<21 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>14 && $rs<19 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>11 && $rs<15 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>9 && $rs<12 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>2 && $rs<10 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<1 && $cu<'5.6' && $cu>'1.0'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm2 6
        if($rs>22 && $rs<36 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>20 && $rs<23 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>16 && $rs<21 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>13 && $rs<17 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>10 && $rs<14 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>9 && $rs<11 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<9 && $cu<'6.5' && $cu>'5.9'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm3 6.5
        if($rs>23 && $rs<36 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>21 && $rs<24 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>17 && $rs<22 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>15 && $rs<18 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>12 && $rs<16 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>10 && $rs<13 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<11 && $cu<'7' && $cu>'6.4'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm4 7
        if($rs>24 && $rs<36 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>22 && $rs<25 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>19 && $rs<23 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>16 && $rs<20 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>13 && $rs<17 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>11 && $rs<14 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<12 && $cu<'7.5' && $cu>'6.9'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm5 7.5
        if($rs>25 && $rs<36 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>23 && $rs<26 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>20 && $rs<24 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>17 && $rs<21 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>14 && $rs<18 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>12 && $rs<15 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<13 && $cu<'8' && $cu>'7.4'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm6 8
        if($rs>26 && $rs<36 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>24 && $rs<27 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>22 && $rs<25 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>19 && $rs<23 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>16 && $rs<20 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>13 && $rs<17 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<14 && $cu<'8.5' && $cu>'7.9'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm7 8.5
        if($rs>28 && $rs<36 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>26 && $rs<29 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>23 && $rs<27 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>20 && $rs<24 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>17 && $rs<21 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>14 && $rs<18 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<15 && $cu<'9' && $cu>'8.4'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm8 9
        if($rs>29 && $rs<36 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>27 && $rs<30 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>24 && $rs<28 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>21 && $rs<25 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>18 && $rs<22 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>15 && $rs<19 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<16 && $cu<'9.5' && $cu>'8.9'){$ws=0;$catcpm='Grade V IQ <69';}
        //cpm9 9 9.5
        if($rs>30 && $rs<36 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade I IQ >120';}
        if($rs>28 && $rs<31 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade II IQ 110-119';}
        if($rs>25 && $rs<29 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade III+ IQ 106-109';}
        if($rs>22 && $rs<26 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade III IQ 101-105';}
        if($rs>19 && $rs<23 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade III- IQ 90-100';}
        if($rs>16 && $rs<20 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade IV IQ 70-89';}
        if($rs>0 && $rs<17 && $cu<'60' && $cu>'9.4'){$ws=0;$catcpm='Grade V IQ <69';}

        $data = array();
        $data['ws'] = $ws;
        $data['benar'] = $rs;
        $data['iq'] = $catcpm;
        $data['jawaban'] = $jawaban;
        return $data;

    }
    public static function soal()
    {
        $soal[0]="cpm1.png";
        $soal[1]="cpm2.png";
        $soal[2]="cpm3.png";
        $soal[3]="cpm4.png";
        $soal[4]="cpm5.png";
        $soal[5]="cpm6.png";
        $soal[6]="cpm7.png";
        $soal[7]="cpm8.png";
        $soal[8]="cpm9.png";
        $soal[9]="cpm10.png";
        $soal[10]="cpm11.png";
        $soal[11]="cpm12.png";
        $soal[12]="cpm13.png";
        $soal[13]="cpm14.png";
        $soal[14]="cpm15.png";
        $soal[15]="cpm16.png";
        $soal[16]="cpm17.png";
        $soal[17]="cpm18.png";
        $soal[18]="cpm19.png";
        $soal[19]="cpm20.png";
        $soal[20]="cpm21.png";
        $soal[21]="cpm22.png";
        $soal[22]="cpm23.png";
        $soal[23]="cpm24.png";
        $soal[24]="cpm25.png";
        $soal[25]="cpm26.png";
        $soal[26]="cpm27.png";
        $soal[27]="cpm28.png";
        $soal[28]="cpm29.png";
        $soal[29]="cpm30.png";
        $soal[30]="cpm31.png";
        $soal[31]="cpm32.png";
        $soal[32]="cpm33.png";
        $soal[33]="cpm34.png";
        $soal[34]="cpm35.png";
        $soal[35]="cpm36.png";


        $jawaba[0]="cpm1a.png";
        $jawaba[1]="cpm2a.png";
        $jawaba[2]="cpm3a.png";
        $jawaba[3]="cpm4a.png";
        $jawaba[4]="cpm5a.png";
        $jawaba[5]="cpm6a.png";
        $jawaba[6]="cpm7a.png";
        $jawaba[7]="cpm8a.png";
        $jawaba[8]="cpm9a.png";
        $jawaba[9]="cpm10a.png";
        $jawaba[10]="cpm11a.png";
        $jawaba[11]="cpm12a.png";
        $jawaba[12]="cpm13a.png";
        $jawaba[13]="cpm14a.png";
        $jawaba[14]="cpm15a.png";
        $jawaba[15]="cpm16a.png";
        $jawaba[16]="cpm17a.png";
        $jawaba[17]="cpm18a.png";
        $jawaba[18]="cpm19a.png";
        $jawaba[19]="cpm20a.png";
        $jawaba[20]="cpm21a.png";
        $jawaba[21]="cpm22a.png";
        $jawaba[22]="cpm23a.png";
        $jawaba[23]="cpm24a.png";
        $jawaba[24]="cpm25a.png";
        $jawaba[25]="cpm26a.png";
        $jawaba[26]="cpm27a.png";
        $jawaba[27]="cpm28a.png";
        $jawaba[28]="cpm29a.png";
        $jawaba[29]="cpm30a.png";
        $jawaba[30]="cpm31a.png";
        $jawaba[31]="cpm32a.png";
        $jawaba[32]="cpm33a.png";
        $jawaba[33]="cpm34a.png";
        $jawaba[34]="cpm35a.png";
        $jawaba[35]="cpm36a.png";

        $jawabb[0]="cpm1b.png";
        $jawabb[1]="cpm2b.png";
        $jawabb[2]="cpm3b.png";
        $jawabb[3]="cpm4b.png";
        $jawabb[4]="cpm5b.png";
        $jawabb[5]="cpm6b.png";
        $jawabb[6]="cpm7b.png";
        $jawabb[7]="cpm8b.png";
        $jawabb[8]="cpm9b.png";
        $jawabb[9]="cpm10b.png";
        $jawabb[10]="cpm11b.png";
        $jawabb[11]="cpm12b.png";
        $jawabb[12]="cpm13b.png";
        $jawabb[13]="cpm14b.png";
        $jawabb[14]="cpm15b.png";
        $jawabb[15]="cpm16b.png";
        $jawabb[16]="cpm17b.png";
        $jawabb[17]="cpm18b.png";
        $jawabb[18]="cpm19b.png";
        $jawabb[19]="cpm20b.png";
        $jawabb[20]="cpm21b.png";
        $jawabb[21]="cpm22b.png";
        $jawabb[22]="cpm23b.png";
        $jawabb[23]="cpm24b.png";
        $jawabb[24]="cpm25b.png";
        $jawabb[25]="cpm26b.png";
        $jawabb[26]="cpm27b.png";
        $jawabb[27]="cpm28b.png";
        $jawabb[28]="cpm29b.png";
        $jawabb[29]="cpm30b.png";
        $jawabb[30]="cpm31b.png";
        $jawabb[31]="cpm32b.png";
        $jawabb[32]="cpm33b.png";
        $jawabb[33]="cpm34b.png";
        $jawabb[34]="cpm35b.png";
        $jawabb[35]="cpm36b.png";

        $jawabc[0]="cpm1c.png";
        $jawabc[1]="cpm2c.png";
        $jawabc[2]="cpm3c.png";
        $jawabc[3]="cpm4c.png";
        $jawabc[4]="cpm5c.png";
        $jawabc[5]="cpm6c.png";
        $jawabc[6]="cpm7c.png";
        $jawabc[7]="cpm8c.png";
        $jawabc[8]="cpm9c.png";
        $jawabc[9]="cpm10c.png";
        $jawabc[10]="cpm11c.png";
        $jawabc[11]="cpm12c.png";
        $jawabc[12]="cpm13c.png";
        $jawabc[13]="cpm14c.png";
        $jawabc[14]="cpm15c.png";
        $jawabc[15]="cpm16c.png";
        $jawabc[16]="cpm17c.png";
        $jawabc[17]="cpm18c.png";
        $jawabc[18]="cpm19c.png";
        $jawabc[19]="cpm20c.png";
        $jawabc[20]="cpm21c.png";
        $jawabc[21]="cpm22c.png";
        $jawabc[22]="cpm23c.png";
        $jawabc[23]="cpm24c.png";
        $jawabc[24]="cpm25c.png";
        $jawabc[25]="cpm26c.png";
        $jawabc[26]="cpm27c.png";
        $jawabc[27]="cpm28c.png";
        $jawabc[28]="cpm29c.png";
        $jawabc[29]="cpm30c.png";
        $jawabc[30]="cpm31c.png";
        $jawabc[31]="cpm32c.png";
        $jawabc[32]="cpm33c.png";
        $jawabc[33]="cpm34c.png";
        $jawabc[34]="cpm35c.png";
        $jawabc[35]="cpm36c.png";


        $jawabd[0]="cpm1d.png";
        $jawabd[1]="cpm2d.png";
        $jawabd[2]="cpm3d.png";
        $jawabd[3]="cpm4d.png";
        $jawabd[4]="cpm5d.png";
        $jawabd[5]="cpm6d.png";
        $jawabd[6]="cpm7d.png";
        $jawabd[7]="cpm8d.png";
        $jawabd[8]="cpm9d.png";
        $jawabd[9]="cpm10d.png";
        $jawabd[10]="cpm11d.png";
        $jawabd[11]="cpm12d.png";
        $jawabd[12]="cpm13d.png";
        $jawabd[13]="cpm14d.png";
        $jawabd[14]="cpm15d.png";
        $jawabd[15]="cpm16d.png";
        $jawabd[16]="cpm17d.png";
        $jawabd[17]="cpm18d.png";
        $jawabd[18]="cpm19d.png";
        $jawabd[19]="cpm20d.png";
        $jawabd[20]="cpm21d.png";
        $jawabd[21]="cpm22d.png";
        $jawabd[22]="cpm23d.png";
        $jawabd[23]="cpm24d.png";
        $jawabd[24]="cpm25d.png";
        $jawabd[25]="cpm26d.png";
        $jawabd[26]="cpm27d.png";
        $jawabd[27]="cpm28d.png";
        $jawabd[28]="cpm29d.png";
        $jawabd[29]="cpm30d.png";
        $jawabd[30]="cpm31d.png";
        $jawabd[31]="cpm32d.png";
        $jawabd[32]="cpm33d.png";
        $jawabd[33]="cpm34d.png";
        $jawabd[34]="cpm35d.png";
        $jawabd[35]="cpm36d.png";

        $jawabe[0]="cpm1e.png";
        $jawabe[1]="cpm2e.png";
        $jawabe[2]="cpm3e.png";
        $jawabe[3]="cpm4e.png";
        $jawabe[4]="cpm5e.png";
        $jawabe[5]="cpm6e.png";
        $jawabe[6]="cpm7e.png";
        $jawabe[7]="cpm8e.png";
        $jawabe[8]="cpm9e.png";
        $jawabe[9]="cpm10e.png";
        $jawabe[10]="cpm11e.png";
        $jawabe[11]="cpm12e.png";
        $jawabe[12]="cpm13e.png";
        $jawabe[13]="cpm14e.png";
        $jawabe[14]="cpm15e.png";
        $jawabe[15]="cpm16e.png";
        $jawabe[16]="cpm17e.png";
        $jawabe[17]="cpm18e.png";
        $jawabe[18]="cpm19e.png";
        $jawabe[19]="cpm20e.png";
        $jawabe[20]="cpm21e.png";
        $jawabe[21]="cpm22e.png";
        $jawabe[22]="cpm23e.png";
        $jawabe[23]="cpm24e.png";
        $jawabe[24]="cpm25e.png";
        $jawabe[25]="cpm26e.png";
        $jawabe[26]="cpm27e.png";
        $jawabe[27]="cpm28e.png";
        $jawabe[28]="cpm29e.png";
        $jawabe[29]="cpm30e.png";
        $jawabe[30]="cpm31e.png";
        $jawabe[31]="cpm32e.png";
        $jawabe[32]="cpm33e.png";
        $jawabe[33]="cpm34e.png";
        $jawabe[34]="cpm35e.png";
        $jawabe[35]="cpm36e.png";

        $jawabf[0]="cpm1f.png";
        $jawabf[1]="cpm2f.png";
        $jawabf[2]="cpm3f.png";
        $jawabf[3]="cpm4f.png";
        $jawabf[4]="cpm5f.png";
        $jawabf[5]="cpm6f.png";
        $jawabf[6]="cpm7f.png";
        $jawabf[7]="cpm8f.png";
        $jawabf[8]="cpm9f.png";
        $jawabf[9]="cpm10f.png";
        $jawabf[10]="cpm11f.png";
        $jawabf[11]="cpm12f.png";
        $jawabf[12]="cpm13f.png";
        $jawabf[13]="cpm14f.png";
        $jawabf[14]="cpm15f.png";
        $jawabf[15]="cpm16f.png";
        $jawabf[16]="cpm17f.png";
        $jawabf[17]="cpm18f.png";
        $jawabf[18]="cpm19f.png";
        $jawabf[19]="cpm20f.png";
        $jawabf[20]="cpm21f.png";
        $jawabf[21]="cpm22f.png";
        $jawabf[22]="cpm23f.png";
        $jawabf[23]="cpm24f.png";
        $jawabf[24]="cpm25f.png";
        $jawabf[25]="cpm26f.png";
        $jawabf[26]="cpm27f.png";
        $jawabf[27]="cpm28f.png";
        $jawabf[28]="cpm29f.png";
        $jawabf[29]="cpm30f.png";
        $jawabf[30]="cpm31f.png";
        $jawabf[31]="cpm32f.png";
        $jawabf[32]="cpm33f.png";
        $jawabf[33]="cpm34f.png";
        $jawabf[34]="cpm35f.png";
        $jawabf[35]="cpm36f.png";

        $data = array();
        $data[0]['soal'] = $soal;
        $data[1]['jawabA'] = $jawaba;
        $data[2]['jawabB'] = $jawabb;
        $data[3]['jawabC'] = $jawabc;
        $data[4]['jawabD'] = $jawabd;
        $data[5]['jawabE'] = $jawabe;
        $data[6]['jawabF'] = $jawabf;

        return $data;
    }
}
