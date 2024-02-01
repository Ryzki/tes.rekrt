<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MBTIController extends Controller
{
    public function getData($num){
        $data_soal = self::soal();
        $jawaba = self::jawaba();
        $jawabb = self::jawabb();


        return response()->json([
            'quest' => $data_soal,
            'jawaba' => $jawaba,
            'jawabb' => $jawabb,
            'num' => $num
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {
        $packet = Packet::where('test_id','=',22)->where('status','=',1)->first();
       
        return view('test.mbti', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet
        ]);
    }

    public static function store(Request $request)
    {
        $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();


        $jawaban = $request->jawaban;
        $koreksi_jawaban = self::cek($jawaban);
        $request['koreksi'] = $koreksi_jawaban;
        //save all jawaban
        $save_result_array = array();
        $save_result_array[0] = $request['jawaban'];
        $save_result_array[1] = $request['koreksi'];
        
        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($save_result_array);
        $result->save();
        
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);

    }
    
    public static function soal()
    {
        $soal = array();
        $soal[0]='Pada sebuah perayaan atau pesta, apakah Anda:';
        $soal[1]='Apakah Anda lebih:';
        $soal[2]='Adalah hal yang buruk:';
        $soal[3]='Biasanya Anda lebih terkesan dengan';
        $soal[4]='Apakah Anda cenderung lebih terjelaskan melalui:';
        $soal[5]='Apakah Anda lebih menyukai bekerja:';
        $soal[6]='Apakah Anda cenderung untuk memilih:';
        $soal[7]='Di pesta-pesta, Anda:';
        $soal[8]='Apakah Anda merupakan seseorang yang lebih:';
        $soal[9]='Apakah Anda lebih tertarik pada:';
        $soal[10]='Dalam memutuskan/memberi penilaian pada orang lain anda lebih dipengaruhi oleh:';
        $soal[11]='Ketika pertama kali mendekati orang lain, apakah Anda lebih:';
        $soal[12]='Apakah Anda biasanya lebih:';
        $soal[13]='Apakah mengganggu Anda, mempunyai banyak hal yang:';
        $soal[14]='Dalam kelompok sosial Anda, Anda:';
        $soal[15]='Dalam mengerjakan sesuatu yang biasa, Anda lebih suka:';
        $soal[16]='Apakah Anda memilih penulis yang:';
        $soal[17]='Bagi Anda, apa yang lebih penting:';
        $soal[18]='Anda merasa nyaman, membuat:';
        $soal[19]='Apakah biasanya Anda:';
        $soal[20]='Menurut Anda, Anda orang yang:';
        $soal[21]='Dalam menelepon, apakah Anda:';
        $soal[22]='Untuk Anda, fakta adalah:';
        $soal[23]='Apakah Anda memilih bekerja dengan:';
        $soal[24]='Apakah Anda cenderung lebih:';
        $soal[25]='Apakah Anda lebih suka menjadi seseorang yang:';
        $soal[26]='Bagi Anda, mengalami sesuatu:';
        $soal[27]='Apakah Anda lebih nyaman dengan:';
        $soal[28]='Dalam suatu pertemuan apakah Anda:';
        $soal[29]='Akal sehat gaya tradisional:';
        $soal[30]='Anak-anak tidak:';
        $soal[31]='Apakah biasanya Anda lebih:';
        $soal[32]='Apakah Anda biasanya lebih bersikap:';
        $soal[33]='Apakah Anda cenderung lebih menjaga hal-hal agar:';
        $soal[34]='Apakah Anda lebih menghargai:';
        $soal[35]='Apakah interaksi baru dengan orang lain:';
        $soal[36]='Anda menggambarkan diri Anda lebih sering sebagai:';
        $soal[37]='Anda lebih melihat orang lain:';
        $soal[38]='Mana yang lebih memuaskan Anda:';
        $soal[39]='Yang mana lebih banyak Anda gunakan:';
        $soal[40]='Anda lebih nyaman dengan pekerjaan yang:';
        $soal[41]='Anda lebih menyukai bila segala hal:';
        $soal[42]='Apakah Anda lebih memilih:';
        $soal[43]='Apakah Anda lebih beranjak pada:';
        $soal[44]='Apakah Anda lebih tertarik pada:';
        $soal[45]='Mana yang lebih patut dipuji:';
        $soal[46]='Apakah Anda menghargai diri Anda karena:';
        $soal[47]='Apakah Anda lebih suka dengan:';
        $soal[48]='Apakah Anda lebih nyaman dengan:';
        $soal[49]='Apakah Anda:';
        $soal[50]='Apakah Anda sepertinya lebih mempercayai:';
        $soal[51]='Apakah Anda merasa:';
        $soal[52]='Apakah Anda memiliki ciri khas sebagai seseorang dengan:';
        $soal[53]='Apakah Anda cenderung lebih:';
        $soal[54]='Anda sangat menyukai tindakan yang:';
        $soal[55]='Apakah cara-cara Anda lebih pada:';
        $soal[56]='Bila telepon berbunyi, Anda akan:';
        $soal[57]='Apakah Anda lebih menghargai diri Anda dalam hal:';
        $soal[58]='Apakah Anda lebih tertarik pada:';
        $soal[59]='Dalam menilai, biasanya anda lebih bersikap:';
        $soal[60]='Apakah Anda lebih menganggap diri Anda sebagai orang yang:';
        $soal[61]='Apakah Anda lebih cenderung mengalami:';
        $soal[62]='Apakah Anda lebih termasuk tipe orang yang menyukai:';
        $soal[63]='Apakah Anda cenderung';
        $soal[64]='Bila Anda menulis, Anda lebih menyukai:';
        $soal[65]='Apakah Anda merasa lebih senang dengan:';
        $soal[66]='Apakah biasanya Anda lebih:';
        $soal[67]='Apakah Anda lebih:';
        $soal[68]='Apakah Anda lebih memilih:';
        $soal[69]='Apakah Anda cenderung lebih:';

        return $soal;
    }
    public static function jawaba()
    {
        $jawaba = array();
        $jawaba[0]='bergaul dengan banyak orang termasuk orang-orang yang baru Anda kenal';
        $jawaba[1]='realistik';
        $jawaba[2]='tenggelam dalam mengandai-andai';
        $jawaba[3]='prinsip ';
        $jawaba[4]='argumen logis';
        $jawaba[5]='menggunakan batas waktu';
        $jawaba[6]='secara berhati-hati';
        $jawaba[7]='Tinggal sampai pesta berakhir dengan dan semakin segar';
        $jawaba[8]='banyak menggunakan akal sehat';
        $jawaba[9]='apa yang aktual';
        $jawaba[10]='hukum dari pada situasi/keadaannya';
        $jawaba[11]='tidak melibatkan perasaan dan menjaga jarak';
        $jawaba[12]='tepat waktu';
        $jawaba[13]='tidak lengkap/tidak terselesaikan';
        $jawaba[14]='mengetahui kejadian-kejadian yang dialami oleh orang lain';
        $jawaba[15]='mengerjakannya dengan cara lazimnya orang';
        $jawaba[16]='menuliskan kata-kata dengan arti yang sebenarnya';
        $jawaba[17]='konsisten dan teguh dengan pemikiran/ide Anda';
        $jawaba[18]='keputusan logis';
        $jawaba[19]='menyukai segala hal terselesaikan';
        $jawaba[20]='tegas, keras pendirian/persisten';
        $jawaba[21]='langsung berbicara';
        $jawaba[22]='menggambarkan apa yang terjadi';
        $jawaba[23]='informasi praktis';
        $jawaba[24]='berpikiran jernih';
        $jawaba[25]='adil dari pada pemaaf';
        $jawaba[26]='harus direncanakan dan berdasarkan pilihan';
        $jawaba[27]='Langsung beli/dipesankan';
        $jawaba[28]='memulai pembicaraan-pembicaraan';
        $jawaba[29]='biasanya bisa dipercaya';
        $jawaba[30]='cukup sering melakukan kegiatan bermanfaat';
        $jawaba[31]='mempertahankan prinsip';
        $jawaba[32]='tegas dari pada lemah-lembut';
        $jawaba[33]='terorganisasi dengan baik';
        $jawaba[34]='sesuatu yang pasti';
        $jawaba[35]='menggugah semangat Anda';
        $jawaba[36]='Orang yang praktis';
        $jawaba[37]='dari bagaimana kemanfaatannya';
        $jawaba[38]='mendiskusikan suatu topik sampai tuntas';
        $jawaba[39]='pemikiran';
        $jawaba[40]='berdasarkan kontrak';
        $jawaba[41]='rapi dan teratur';
        $jawaba[42]='banyak teman dengan hubungan yang singkat';
        $jawaba[43]='fakta-fakta';
        $jawaba[44]='produksi dan distribusi/hasil akhir';
        $jawaba[45]='orang yang sangat logis';
        $jawaba[46]='berpendirian teguh';
        $jawaba[47]='pernyataan-pernyataan final dan tidak bisa diganggu-gugat';
        $jawaba[48]='sesudah pengambilan keputusan';
        $jawaba[49]='berbicara dengan mudah dan panjang lebar dengan orang yang baru dikenal';
        $jawaba[50]='pengalaman-pengalaman';
        $jawaba[51]='lebih praktis daripada kreatif';
        $jawaba[52]='rasio jelas';
        $jawaba[53]='adil apa adanya/tanpa prasangka';
        $jawaba[54]='memastikan segala sesuatu ditata dahulu';
        $jawaba[55]='mengatur segala sesuatu';
        $jawaba[56]='segera menerima telepon tersebut';
        $jawaba[57]='kemampuan memahami realita';
        $jawaba[58]='azas';
        $jawaba[59]='netral';
        $jawaba[60]='keras hati/teguh';
        $jawaba[61]='kejadian-kejadian yang telah terjadwal';
        $jawaba[62]='hal-hal yang bersifat rutin';
        $jawaba[63]='mudah didekati';
        $jawaba[64]='memakai kata-kata dengan arti yang sebenarnya';
        $jawaba[65]='mengalami sendiri dan nyata';
        $jawaba[66]='berpikiran jernih';
        $jawaba[67]='adil dari pada toleran';
        $jawaba[68]='kegiatan yang terencana';
        $jawaba[69]='hati-hati daripada spontan';

        return $jawaba;
    }

    public static function jawabb()
    {

        $jawabb= array();
        $jawabb[0]='bergaul dengan beberapa orang yang telah anda kenal saja';
        $jawabb[1]='spekulatif';
        $jawabb[2]='terlanjur basah kerepotan ditengah jalan';
        $jawabb[3]='perasaan';
        $jawabb[4]='argumen emotif (perasaan)';
        $jawabb[5]='kapan-kapan saja';
        $jawabb[6]='secara spontan';
        $jawabb[7]='meninggalkan pesta lebih cepat dengan kondisi capai/lelah';
        $jawabb[8]='imajinatif';
        $jawabb[9]='apa yang mungkin';
        $jawabb[10]='situasi/keadaannya dari pada hukum';
        $jawabb[11]='melibatkan diri secara pribadi dan mencoba membuat orang tersebut tertarik';
        $jawabb[12]='santai';
        $jawabb[13]='sudah lengkap/terselesaikan';
        $jawabb[14]='biasanya ketinggalan berita';
        $jawabb[15]='mengerjakannya dengan cara Anda sendiri';
        $jawabb[16]='menggunakan kiasan-kiasan';
        $jawabb[17]='menjaga keharmonisan hubungan sosial';
        $jawabb[18]='keputusan berdasar nilai-nilai';
        $jawabb[19]='membiarkan berbagai pilihan dengan berbagai kemungkinan';
        $jawabb[20]='nyantai, bisa menyesuaikan pendirian/kemauan (easy-going)';
        $jawabb[21]='menyusun dulu kata-kata yang diucapkan';
        $jawabb[22]='biasanya perlu interpretasi';
        $jawabb[23]='ide-ide abstrak';
        $jawabb[24]='berperasaan hangat';
        $jawabb[25]='pemaaf dari pada adil';
        $jawabb[26]='biarlah berjalan dengan sendirinya';
        $jawabb[27]='mempunyai pilihan-pilihan';
        $jawabb[28]='menunggu untuk ditanya';
        $jawabb[29]='sering menimbulkan salah tafsir';
        $jawabb[30]='cukup berfantasi';
        $jawabb[31]='bersikap simpatik';
        $jawabb[32]='lemah-lembut dari pada tegas';
        $jawabb[33]='terbuka untuk kemungkinan-kemungkinan';
        $jawabb[34]='sesuatu yang berubah-ubah';
        $jawabb[35]='menguras tenaga Anda';
        $jawabb[36]='Orang yang teoritis';
        $jawabb[37]='dari bagaimana kelihatannya';
        $jawabb[38]='mencapai kesepakatan tentang suatu permasalahan';
        $jawabb[39]='perasaan';
        $jawabb[40]='tidak terikat';
        $jawabb[41]='tergantung situasi';
        $jawabb[42]='beberapa teman dengan hubungan yang lama/langgeng';
        $jawabb[43]='kaidah-kaidah';
        $jawabb[44]='perencana, penelitian';
        $jawabb[45]='orang yang sangat sentimentil (mengedepankan perasaan)';
        $jawabb[46]='penuh pengabdian';
        $jawabb[47]='pernyataan-pernyataan tidak bersifat pasti (tentatif)/masih awal';
        $jawabb[48]='sebelum pengambilan keputusan';
        $jawabb[49]='sulit berbicara dengan orang yang baru dikenal';
        $jawabb[50]='firasat/dugaan/prasangka';
        $jawabb[51]='lebih kreatif daripada praktis';
        $jawabb[52]='perasaan yang kuat';
        $jawabb[53]='menaruh perhatian (simpatis)';
        $jawabb[54]='membiarkan hal-hal yang terjadi begitu saja';
        $jawabb[55]='menunda-nunda penyelesaian';
        $jawabb[56]='berharap orang lain yang menerimanya';
        $jawabb[57]='kemampuan imajinasi yang baik';
        $jawabb[58]='penyesuaian';
        $jawabb[59]='pemurah';
        $jawabb[60]='lunak hati/halus';
        $jawabb[61]='kejadian-kejadian yang datang begitu saja';
        $jawabb[62]='hal-hal yang tidak biasa';
        $jawabb[63]='agak menjaga jarak';
        $jawabb[64]='menggunakan kata-kata kiasan';
        $jawabb[65]='membayangkan';
        $jawabb[66]='menunjukkan perasaan yang kuat';
        $jawabb[67]='toleran dari pada adil';
        $jawabb[68]='kegiatan yang tidak terencana';
        $jawabb[69]='spontan daripada hati-hati';

        return $jawabb;
    }

    public static function cek($T){
        $EE=0;$II=0;$SS=0;$NN=0;$TT=0;$FF=0;$JJ=0;$PP=0;

        if (($T[1]=="A")) $EE++; else $II++;
        if (($T[8]=="A")) $EE++; else $II++;
        if (($T[15]=="A")) $EE++; else $II++;
        if (($T[22]=="A")) $EE++; else $II++;
        if (($T[29]=="A")) $EE++; else $II++;
        if (($T[36]=="A")) $EE++; else $II++;
        if (($T[43]=="A")) $EE++; else $II++;
        if (($T[50]=="A")) $EE++; else $II++;
        if (($T[57]=="A")) $EE++; else $II++;
        if (($T[64]=="A")) $EE++; else $II++;

        if (($T[2]=="A")) $SS++; else $NN++;
        if (($T[3]=="A")) $SS++; else $NN++;
        if (($T[9]=="A")) $SS++; else $NN++;
        if (($T[10]=="A")) $SS++; else $NN++;
        if (($T[16]=="A")) $SS++; else $NN++;
        if (($T[17]=="A")) $SS++; else $NN++;
        if (($T[23]=="A")) $SS++; else $NN++;
        if (($T[24]=="A")) $SS++; else $NN++;
        if (($T[30]=="A")) $SS++; else $NN++;
        if (($T[31]=="A")) $SS++; else $NN++;
        if (($T[37]=="A")) $SS++; else $NN++;
        if (($T[38]=="A")) $SS++; else $NN++;
        if (($T[44]=="A")) $SS++; else $NN++;
        if (($T[45]=="A")) $SS++; else $NN++;
        if (($T[51]=="A")) $SS++; else $NN++;
        if (($T[52]=="A")) $SS++; else $NN++;
        if (($T[58]=="A")) $SS++; else $NN++;
        if (($T[59]=="A")) $SS++; else $NN++;
        if (($T[65]=="A")) $SS++; else $NN++;
        if (($T[66]=="A")) $SS++; else $NN++;
        

        if (($T[4]=="A")) $TT++; else $FF++;
        if (($T[5]=="A")) $TT++; else $FF++;
        if (($T[11]=="A")) $TT++; else $FF++;
        if (($T[12]=="A")) $TT++; else $FF++;
        if (($T[18]=="A")) $TT++; else $FF++;
        if (($T[19]=="A")) $TT++; else $FF++;
        if (($T[25]=="A")) $TT++; else $FF++;
        if (($T[26]=="A")) $TT++; else $FF++;
        if (($T[32]=="A")) $TT++; else $FF++;
        if (($T[33]=="A")) $TT++; else $FF++;
        if (($T[39]=="A")) $TT++; else $FF++;
        if (($T[40]=="A")) $TT++; else $FF++;
        if (($T[46]=="A")) $TT++; else $FF++;
        if (($T[47]=="A")) $TT++; else $FF++;
        if (($T[53]=="A")) $TT++; else $FF++;
        if (($T[54]=="A")) $TT++; else $FF++;
        if (($T[60]=="A")) $TT++; else $FF++;
        if (($T[61]=="A")) $TT++; else $FF++;
        if (($T[67]=="A")) $TT++; else $FF++;
        if (($T[68]=="A")) $TT++; else $FF++;
        

        if (($T[6]=="A")) $JJ++; else $PP++;
        if (($T[7]=="A")) $JJ++; else $PP++;
        if (($T[13]=="A")) $JJ++; else $PP++;
        if (($T[14]=="A")) $JJ++; else $PP++;
        if (($T[20]=="A")) $JJ++; else $PP++;
        if (($T[21]=="A")) $JJ++; else $PP++;
        if (($T[27]=="A")) $JJ++; else $PP++;
        if (($T[28]=="A")) $JJ++; else $PP++;
        if (($T[34]=="A")) $JJ++; else $PP++;
        if (($T[35]=="A")) $JJ++; else $PP++;
        if (($T[41]=="A")) $JJ++; else $PP++;
        if (($T[42]=="A")) $JJ++; else $PP++;
        if (($T[48]=="A")) $JJ++; else $PP++;
        if (($T[49]=="A")) $JJ++; else $PP++;
        if (($T[55]=="A")) $JJ++; else $PP++;
        if (($T[56]=="A")) $JJ++; else $PP++;
        if (($T[62]=="A")) $JJ++; else $PP++;
        if (($T[63]=="A")) $JJ++; else $PP++;
        if (($T[69]=="A")) $JJ++; else $PP++;
        if (($T[70]=="A")) $JJ++; else $PP++;


        $EW=self::nilai("E",$EE);
        $IW=self::nilai("I",$II);
        $SW=self::nilai("S",$SS);
        $NW=self::nilai("N",$NN);
        $TW=self::nilai("T",$TT);
        $FW=self::nilai("F",$FF);
        $JW=self::nilai("J",$JJ);
        $PW=self::nilai("P",$PP);

        if ($SW>$NW)  $duaSN= "SN";
        elseif($NW>$SW)$duaSN="NS"; 
        elseif ($SS>$NN) $duaSN="SN";
        else $duaSN="NS";

        if ($duaSN=="SN") 
        {
                if ($JW>$PW) $dua= "SJ";
                elseif ($PW>$JW) $dua= "SP";
                elseif($JJ>$PP)$dua= "SJ";
                else $dua= "SP";
        }
        if ($duaSN=="NS") 
            {
                if ($TW>$FW) $dua= "NT";
                elseif ($FW>$TW) $dua= "NF";
                elseif($TT>$FF)$dua= "NT";
                else $dua= "NF";
            }

        if (($EW>=$IW)) $char1="E" ; else if(($EW<$IW)) $char1="I";
        if (($SW>=$NW)) $char2="S" ; else if(($SW<$NW)) $char2="N";
        if (($TW>=$FW)) $char3="T" ; else if(($TW<$FW)) $char3="F";
        if (($JW>=$PW)) $char4="J" ; else if(($JW<$PW)) $char4="P";

        $empat = $char1.$char2.$char3.$char4;

        $array_cek2 = array();
        $array_cek2[1] = $EE;
        $array_cek2[2] = $II;
        $array_cek2[3] = $SS;
        $array_cek2[4] = $NN;
        $array_cek2[5] = $TT;
        $array_cek2[6] = $FF;
        $array_cek2[7] = $JJ;
        $array_cek2[8] = $PP;

        $array_cek = array();
        $array_cek[1] = $EW;
        $array_cek[2] = $IW;
        $array_cek[3] = $SW;
        $array_cek[4] = $NW;
        $array_cek[5] = $TW;
        $array_cek[6] = $FW;
        $array_cek[7] = $JW;
        $array_cek[8] = $PW;

        $array_all = array();
        $array_all[0] = $array_cek;
        $array_all[1] = $array_cek2;
        $array_all[2] = $empat;
        $array_all[3] = $duaSN;
        $array_all[4] = $dua;
        // $array_all[5] = self::resultMBTI($empat);

        
        return $array_all;
    }

    public static function nilai($word,$value){
        $result = 0;
        if($value == 0){
            if($word == 'E') $result = 1;
            else if($word == 'I') $result = 5;
            else if($word == 'S') $result = 0;
            else if($word == 'N') $result = 4;
            else if($word == 'T') $result = 0;
            else if($word == 'F') $result = 4;
            else if($word == 'J') $result = 0;
            else if($word == 'P') $result = 8;
        }
        else if($value == 1){
            if($word == 'E') $result = 2;
            else if($word == 'I') $result = 11;
            else if($word == 'S') $result = 0;
            else if($word == 'N') $result = 6;
            else if($word == 'T') $result = 1;
            else if($word == 'F') $result = 7;
            else if($word == 'J') $result = 0;
            else if($word == 'P') $result = 12;
        }
        else if($value == 2){
            if($word == 'E') $result = 5;
            else if($word == 'I') $result = 21;
            else if($word == 'S') $result = 1;
            else if($word == 'N') $result = 10;
            else if($word == 'T') $result = 1;
            else if($word == 'F') $result = 10;
            else if($word == 'J') $result = 0;
            else if($word == 'P') $result = 17;
        }
        else if($value ==3){
            if($word == 'E') $result = 11;
            else if($word == 'I') $result = 34;
            else if($word == 'S') $result = 1;
            else if($word == 'N') $result = 14;
            else if($word == 'T') $result = 2;
            else if($word == 'F') $result = 15;
            else if($word == 'J') $result = 1;
            else if($word == 'P') $result = 23;
        }
        else if($value ==4){
            if($word == 'E') $result = 21;
            else if($word == 'I') $result = 50;
            else if($word == 'S') $result = 3;
            else if($word == 'N') $result = 20;
            else if($word == 'T') $result = 4;
            else if($word == 'F') $result = 20;
            else if($word == 'J') $result = 1;
            else if($word == 'P') $result = 31;
        }
        else if($value ==5){
            if($word == 'E') $result = 34;
            else if($word == 'I') $result = 66;
            else if($word == 'S') $result = 4;
            else if($word == 'N') $result = 27;
            else if($word == 'T') $result = 6;
            else if($word == 'F') $result = 27;
            else if($word == 'J') $result = 2;
            else if($word == 'P') $result = 39;
        }
        else if($value ==6){
            if($word == 'E') $result = 50;
            else if($word == 'I') $result = 79;
            else if($word == 'S') $result = 7;
            else if($word == 'N') $result = 36;
            else if($word == 'T') $result = 9;
            else if($word == 'F') $result = 35;
            else if($word == 'J') $result = 4;
            else if($word == 'P') $result = 48;
        }
        else if($value ==7){
            if($word == 'E') $result = 66;
            else if($word == 'I') $result = 89;
            else if($word == 'S') $result = 11;
            else if($word == 'N') $result = 45;
            else if($word == 'T') $result = 13;
            else if($word == 'F') $result = 43;
            else if($word == 'J') $result = 6;
            else if($word == 'P') $result = 57;
        }
        else if($value ==8){
            if($word == 'E') $result = 79;
            else if($word == 'I') $result = 95;
            else if($word == 'S') $result = 15;
            else if($word == 'N') $result = 54;
            else if($word == 'T') $result = 18;
            else if($word == 'F') $result = 52;
            else if($word == 'J') $result = 10;
            else if($word == 'P') $result = 66;
        }
        else if($value ==9){
            if($word == 'E') $result = 89;
            else if($word == 'I') $result = 98;
            else if($word == 'S') $result = 22;
            else if($word == 'N') $result = 63;
            else if($word == 'T') $result = 24;
            else if($word == 'F') $result = 60;
            else if($word == 'J') $result = 14;
            else if($word == 'P') $result = 74;
        }
        else if($value ==10){
            if($word == 'E') $result = 95;
            else if($word == 'I') $result = 99;
            else if($word == 'S') $result = 29;
            else if($word == 'N') $result = 71;
            else if($word == 'T') $result = 32;
            else if($word == 'F') $result = 68;
            else if($word == 'J') $result = 20;
            else if($word == 'P') $result = 80;
        }
        else if($value ==11){
            if($word == 'E') $result = 98;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 37;
            else if($word == 'N') $result = 78;
            else if($word == 'T') $result = 40;
            else if($word == 'F') $result = 76;
            else if($word == 'J') $result = 26;
            else if($word == 'P') $result = 86;
        }
        else if($value ==12){
            if($word == 'E') $result = 99;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 46;
            else if($word == 'N') $result = 85;
            else if($word == 'T') $result = 48;
            else if($word == 'F') $result = 82;
            else if($word == 'J') $result = 34;
            else if($word == 'P') $result = 90;
        }
        else if($value ==13){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 55;
            else if($word == 'N') $result = 89;
            else if($word == 'T') $result = 57;
            else if($word == 'F') $result = 87;
            else if($word == 'J') $result = 43;
            else if($word == 'P') $result = 94;
        }
        else if($value ==14){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 64;
            else if($word == 'N') $result = 93;
            else if($word == 'T') $result = 65;
            else if($word == 'F') $result = 91;
            else if($word == 'J') $result = 52;
            else if($word == 'P') $result = 96;
        }
        else if($value ==15){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 73;
            else if($word == 'N') $result = 96;
            else if($word == 'T') $result = 73;
            else if($word == 'F') $result = 94;
            else if($word == 'J') $result = 61;
            else if($word == 'P') $result = 98;
        }
        else if($value ==16){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 80;
            else if($word == 'N') $result = 97;
            else if($word == 'T') $result = 80;
            else if($word == 'F') $result = 96;
            else if($word == 'J') $result = 69;
            else if($word == 'P') $result = 99;
        }
        else if($value ==17){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 86;
            else if($word == 'N') $result = 99;
            else if($word == 'T') $result = 85;
            else if($word == 'F') $result = 98;
            else if($word == 'J') $result = 77;
            else if($word == 'P') $result = 99;
        }
        else if($value ==18){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 90;
            else if($word == 'N') $result = 99;
            else if($word == 'T') $result = 90;
            else if($word == 'F') $result = 99;
            else if($word == 'J') $result = 83;
            else if($word == 'P') $result = 100;
        }
        else if($value ==19){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 94;
            else if($word == 'N') $result = 100;
            else if($word == 'T') $result = 93;
            else if($word == 'F') $result = 99;
            else if($word == 'J') $result = 88;
            else if($word == 'P') $result = 100;
        }
        else if($value ==20){
            if($word == 'E') $result = 100;
            else if($word == 'I') $result = 100;
            else if($word == 'S') $result = 96;
            else if($word == 'N') $result = 100;
            else if($word == 'T') $result = 96;
            else if($word == 'F') $result = 100;
            else if($word == 'J') $result = 92;
            else if($word == 'P') $result = 100;
        }

        return $result;
    }


    
}
