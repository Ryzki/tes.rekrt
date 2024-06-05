<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WPTController extends Controller
{
    public function getData($num){
        
        $data = json_decode(file_get_contents(public_path() . "/assets/js/wpt.json"), true);

        return response()->json([
            'quest' => $data,
            'num' => $num
        ]);
    }
    public function getDataLike($num){
        

         $data = self::cek();
        

        return response()->json([
            'quest' => $data,
            'num' => $num
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {

        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            $id = $path == 'wpt' ? 21 : $test->id;
            $packet = Packet::where('test_id','=',$id)->where('status','=',1)->first();
                
            return view('test.wpt.'.$path, [
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'packet' => $packet
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
            $jawaban = $request->jawaban;
            $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
            $wpt_kunci = $request->path == 'wpt' ? self::data() : self::dataWpt();
            $raw_score = 0;
            $umur = cekUmur();
            $usia = $request->path == 'wptlike' ? $umur : $request->jawaban[51];
            
            
            // dd($wpt_kunci);
            for($id=1;$id<=50;$id++){
                if(in_array($jawaban[$id],$wpt_kunci[$id]) == true){
                    $raw_score++;
                }
            }

            // dd($raw_score);
            $array = array();
            $rs = 0;
    
            if($usia>60){$rs+=5;}
            else if ($usia>54){$rs+=4;}
            else if ($usia>49){$rs+=4;}
            else if ($usia>39){$rs+=2;}
            else if ($usia>29){$rs+=1;}
    
            if($rs>29){$ws=5;}
            else if ($rs>24){$ws=4;}
            else if ($rs>14){$ws=3;}
            else if ($rs>7){$ws=2;}
            else $ws=1;
          
            $array['RS'] = $raw_score;
            $array['jawaban'] = $request->jawaban;
            $array['ws'] = $ws;
            $array['usia'] = $usia;

            $result = new Result;
            $result->user_id = Auth::user()->id;
            $result->company_id = Auth::user()->attribute->company_id;
            $result->test_id = $request->test_id;
            $result->packet_id = $request->packet_id;
            $result->result = json_encode($array);
    
            $result->save();
    
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);

        }
    }

    //kunci jawabab test wpt
    public static function data()
    {
        $wpt = [];
        $wpt[1]=["5"];
        $wpt[2]=["1"];
        $wpt[3]=["4"];
        $wpt[4]=["2"];
        $wpt[5]=["3"];
        $wpt[6]=["1"];
        $wpt[7]=["3"];
        $wpt[8]=["1/27--"]; //1
        $wpt[9]=["1"];
        $wpt[10]=["4"];
        $wpt[11]=["5"];
        $wpt[12]=["4000"];
        $wpt[13]=["1"];
        $wpt[14]=["3"];
        $wpt[15]=["3000"];
        $wpt[16]=["3"];
        $wpt[17]=["a", "A"];
        $wpt[18]=["14--"]; //24
        $wpt[19]=["1"];
        $wpt[20]=["2"];
        $wpt[21]=["20"];
        $wpt[22]=["s" , "S"];
        $wpt[23]=["3&4" , "4&3", "4-3", "4/3", "43", "34", "3-4", "3/4" ];
        $wpt[24]=["3--"]; //30
        $wpt[25]=["3"];
        $wpt[26]=["1"];
        $wpt[27]=["5"];
        $wpt[28]=["3"];
        $wpt[29]=["8"];
        $wpt[30]=["10"];
        $wpt[31]=["1/12"]; //12
        $wpt[32]=["1"];
        $wpt[33]=["3"];
        $wpt[34]=["24"];
        $wpt[35]=["1/4" , "0,25", "0.25", ",25", ".25"];
        $wpt[36]=["27"];
        $wpt[37]=["0.1024", ",1024", ".1024", "0,1024"]; //8.1
        $wpt[38]=["7&10", "10&7", "10/7", "10-7", "107", "710"];
        $wpt[39]=["3"];
        $wpt[40]=["2"];
        $wpt[41]=["1&4", "4&1", "4/1", "4-1", "41", "14", "1/4", "1-4"];
        $wpt[42]=["4&23", "23&4", "23/4", "23-4", "234", "423", "4/23", "4-23"]; //16
        $wpt[43]=["0.44", ",44", ".44", "0,44"]; //1
        $wpt[44]=["2"];
        $wpt[45]=["16000"]; //32000
        $wpt[46]=["4"];
        $wpt[47]=["3"];
        $wpt[48]=["500"];
        $wpt[49]=["1,2,3,5", "1235", "1-2-3-5"]; //4
        $wpt[50]=["67"]; //25

    return $wpt;
    }

    public static function dataWpt()
    {
        $wpt[1]=["5"];
        $wpt[2]=["1"];
        $wpt[3]=["4"];
        $wpt[4]=["2"];
        $wpt[5]=["3"];
        $wpt[6]=["1"];
        $wpt[7]=["3"];
        $wpt[8]=["1"];
        $wpt[9]=["1"];
        $wpt[10]=["4"];
        $wpt[11]=["5"];
        $wpt[12]=["4000"];
        $wpt[13]=["1"];
        $wpt[14]=["3"];
        $wpt[15]=["3000"];
        $wpt[16]=["3"];
        $wpt[17]=["a"];
        $wpt[18]=["24"];
        $wpt[19]=["1"];
        $wpt[20]=["2"];
        $wpt[21]=["20"];
        $wpt[22]=["s"];
        $wpt[23]=["3&4"];
        $wpt[24]=["30"];
        $wpt[25]=["3"];
        $wpt[26]=["1"];
        $wpt[27]=["5"];
        $wpt[28]=["3"];
        $wpt[29]=["8"];
        $wpt[30]=["10"];
        $wpt[31]=["12"];
        $wpt[32]=["1"];
        $wpt[33]=["3"];
        $wpt[34]=["24"];
        $wpt[35]=["1/4"];
        $wpt[36]=["27"];
        $wpt[37]=["8,1"];
        $wpt[38]=["7&10"];
        $wpt[39]=["3"];
        $wpt[40]=["2"];
        $wpt[41]=["1&4"];
        $wpt[42]=["16"];
        $wpt[43]=["1"];
        $wpt[44]=["2"];
        $wpt[45]=["32000"];
        $wpt[46]=["4"];
        $wpt[47]=["3"];
        $wpt[48]=["500"];
        $wpt[49]=["4"];
        $wpt[50]=["25"];

        return $wpt;
    }

    //soal wpt-like
    public static function cek(){
                    
        $soal[0]='Dua bulan lalu pada akhir tahun ini adalah: 1. Maret 2. Mei 3. November  4. Desember 5. Oktober<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[1]='MELEPAS adalah lawan kata dari: 1. menangkap 2. membebaskan 3. beresiko 4. mempersilakan 5. mengingatkan<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[2]='Sebagian besar hal di bawah ini serupa satu sama lain. Manakah salah satu diantaranya yang yang kurang serupa dengan yang lain? 1. Oktober 2. Maret 3. Juli 4. Sabtu 5. Agustus<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[3]='Jawablah dengan menulis YA atau TIDAK. Apakah ASAP berarti jawablah yang tidak perlu disegerakan? <br><em>Isikan dengan huruf kapital</em>';
        $soal[4]='Dalam kelompok kata berikut, manakah kata yang berbeda dari kata yang lain? 1. pasukan 2. liga 3. anggota 4. pak 5. tim<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[5]='RUTIN adalah lawan kata dari: 1. jarang 2. terbiasa 3. tetap 4. berhenti 5. selalu<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[6]='Gambar manakah yang terbuat dari dua gambar di dalam tanda kurung?<br><img src=../gambar/7wpt.png><br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[7]='Perhatikan urutan angka berikut. Diganti angka berapa huruf x agar sesuai?<br>27   9   3   x   1/3   1/9   1/27';
        $soal[8]='Dewan  dan Majelis.  Apakah kata-kata ini: 1. memiliki arti yang sama    2. memiliki arti berlawanan   3. tidak memiliki arti sama atau berlainan<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[9]='Gambar mana yang sesuai dengan gambar X ?<br><img src="../gambar/wptlike10.png"><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[10]='MUSIM GUGUR adalah lawan dari :    1. liburan   2. musim panas   3. musim semi   4. musim dingin   5. musim gugur<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[11]='Sepeda bergerak 200 meter dalam 1/4 menit. Pada kecepatan yang sama berapa meter sepeda bergerak dalam 5 menit ?';
        $soal[12]='Anggaplah dua pernyataan pertama adalah benar. Apakah yang terakhir : 1. benar   2. salah   3. tidak tahu ?<br>Rebecca Susi adalah anak migran. Semua anak migran sekolah di Kampung Pulo. Rebecca Susi sekolah di Kampung Pulo. <br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[13]='PELAN cenderung memiliki kesamaan kata dengan: 1. santai    2. dekat    3. lambat    4. terburu-buru      5. tergesa <br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[14]='Dua kelereng diberi harga Rp.1.000,-, harga 1/2 lusin memiliki harga berapa? ';
        $soal[15]='Berapa banyak duplikasi yang sama dari lima pasangan angka di bawah ini ?<br><table><tr><td>93687</td><td>93687</td></tr><tr><td>3960281</td><td>3690281</td></tr><tr><td>63608302</td><td>63608302</td></tr><tr><td>96101116</td><td>96110116</td></tr><tr><td>99996666</td><td>99996666</td></tr></table><br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[16]='Misalkan Anda menyusun kata-kata berikut sehingga menjadi pernyataan yang benar. Lalu tuliskan huruf terakhir dari kata terakhir sebagai jawaban.  <b>memiliki tiap pelana  tunggangan  kuda  selalu</b><br><em>tuliskan dengan keyboard menggunakan huruf kecil saja</em>';
        $soal[17]='Seorang anak berumur 6 tahun dan pada saat itu usia ibunya lima kali lebih tua. Ketika anak itu dilahirkan berapa usia ibunya?';
        $soal[18]='DEWANGGA  - DEWATA   Apakah kata ini<br>     1.  tidak memiliki arti yang sama atau berlawanan   2. memiliki arti yang sama 3. memiliki arti yang berlawanan  <br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[19]='Anggaplah dua pernyataan pertama adalah benar. Apakah pernyataan terakhir : 1. benar   2. salah   3. tidak tahu.<br> Joko seusia dengan Joni. Joni lebih muda dari Jono. Joko lebih tua dari Jono <br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[20]='Seorang pemborong membeli beberapa liter oli seharga Rp. 400.000,-. Ia menjual dengan harga  Rp.500.000,-, mendapat untung Rp.5.000,- setiap liter oli. Berapa banyak liter yang dijual ?';
        $soal[21]='Misalkan Anda menyusun kata-kata berikut sehingga menjadi kalimat lengkap. Jika kalimat itu benar, tulislah (B). Jika salah, tulislah (S).  susu sapi menghasilkan semua';
        $soal[22]='Dua dari peribahasa berikut ini memiliki arti sama. Manakah itu ?<br>1. Kamu adalah apa yang kamu makan.<br>2. Buruk muka cermin dibelah.<br>3. Anak ayam belajar dari induknya.<br>4. Buah jatuh tidak jauh dari pohonnya.<br>5. Semakin banyak memiliki  kambing, akan memiliki satu anak kambing yang buruk.<br><em>Isikan dengan angka saja untuk jawabannya</em><br><em>Isikan menggunakan keyboard, misal x & y dimana x dan y adalah nomor yang anda pilih. Isian jawaban x&y jangan ada spasi</em>';
        $soal[23]='Sebuah jam terlambat 6 menit dalam 1 tahun. Berapa detik ia terlambat dalam sebulan?';
        $soal[24]='TANGKUP  -   TANGKUR    Apakah kata-kata ini:<br>      1. memiliki arti yang sama    2. memiliki arti yang berlawanan   3. tidak memiliki arti yang sama atau berlawanan<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[25]='Anggaplah dua pernyataan pertama adalah benar. Pernyataan terakhir : 1. benar   2. salah   3. tidak tahu<br>Semua karyawan PT XYZ diliburkan. Beberapa orang di kantin adalah karyawan PT XYZ. Beberapa orang di kantin diliburkan oleh perusahaannya.<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[26]='Dalam 20 hari seseorang membayar angsuran 100 kotak upeti. Berapa rata-rata angsuran setiap hari ?';
        $soal[27]='Kata ini dapat memiliki arti yang berbeda: 1. tempayan  2. sate  3. tanjung<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[28]='Dua orang membawa 40 buah kotak. Y membawa 4 kali lebih banyak dari X. Berapa kotak yang dibawa X?';
        $soal[29]='Sebuah kotak segi empat, yang terisi penuh, memuat 800 meter kubik garam. Jika satu kotak lebarnya 8 meter dan panjangnya 10 meter, berapa kedalaman kotak itu ?';
        $soal[30]='Satu angka dari rangkaian berikut tidak cocok dengan pola angka yang lainnya. Angka berapakah itu ?<br>   3   5   7   9   12   13';
        $soal[31]='Jawablah pertanyaan ini dengan menulis 1 untuk YA atau 2 untuk TIDAK. Apakah A.M berarti  <b>ante merediem</b>?<br><em>Isikan dengan huruf besar/kapital untuk jawabannya</em>';
        $soal[32]='BISA DIPERCAYA   -  MUDAH DIPERCAYA     Apakah kata-kata ini<br>1. memiliki arti sama   2. memiliki arti berlawanan   3. tidak memiliki arti sama atau berlawanan<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[33]='Satu donat membutuhkan satu seperempat (1,25) gram tepung. Berapa banyak yang dihasilkan dari 30 gram tepung?<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[34]='Sebuah jam menunjuk tepat pada pukul 12 siang hari pada hari Senin. Pada pukul 2 siang, hari Rabu, jam itu terlambat 25 detik. Pada rata-rata yang sama, berapa banyak jam itu terlambat dalam 1/2 jam ?';
        $soal[35]='Kami dapat mengumpulkan 12 ton beras tahun ini. Ini merupakan 4/9 bagian dari semua yang dikumpulkan mereka. Berapa ton yang dapat mereka kumpulkan tahun ini?';
        $soal[36]='Apakah angka selanjutnya dari seri ini: 0,1   0,3   0,9   2,7';
        $soal[37]='Bentuk geometris ini dapat dibagi oleh suatu garis lurus menjadi dua bagian yang dapat disatukan dengan suatu cara hingga membentuk bujur sangkar yang sempurna. Gambarlah garis yang menghubungkan dua dari angka-angka yang ada. Lalu tuliskan angka tersebut sebagai  jawaban.<br><img src=../gambar/38wpt.png><br><em>Isikan menggunakan keyboard, misal x & y dimana x dan y adalah nomor yang anda pilih. Isian jawaban x&y jangan ada spasi</em>';
        $soal[38]='Apakah arti dari kalimat berikut :  1. sama    2. berlawanan    3. tidak sama atau berlawanan ?<br>Kain pel baru mengepel lantai dengan bersih.<br>Sandal yang sudah lama sifatnya semakin lunak<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[39]='Berapa duplikasi dari pasangan kata berikut ini?<br><table><tr><td>Bexford, M.D.</td><td>Bockford, M.D</td></tr><tr><td>Kieter, K.S.</td><td>Kieter, K.S.</td></tr><tr><td>Dingleton, M.O.</td><td>Dimbleton, M.O.</td></tr><tr><td>Richards, W.E.</td><td>Richard, W.E.</td></tr><tr><td>Friegel, A.C.</td><td>Freigel, A.C.</td></tr><tr><td>Brood, M.B.A.</td><td>Brood, M.B.A.</td></tr></table><br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[40]='Dua dari peribahasa ini memiliki makna yang serupa. Manakah itu ?<br>1. Air beriak tanda tak dalam.<br>2. Orang yang mencuri telur akan mencuri sapi.<br>3. Air tenang menghanyutkan.<br>4.Tong kosong berbunyi nyaring.<br>5. Pungguk merindukan bulan.<br><em>Isikan menggunakan keyboard, misal x & y dimana x dan y adalah nomor yang anda pilih. Isian jawaban x&y jangan ada spasi</em>';
        $soal[41]='Berapa jumlah kotak pada gambar berikut?<img src="../gambar/wptlike42.png">';
        $soal[42]='Jika pada gambar adalah sebuah perahu dengan rudder ke kiri, bergerak kemana perahu tersebut?<br><img src="../gambar/wptlike43.png">';
        $soal[43]='Dua kata berikut : 1. sama   2. berlawanan   3. tidak sama atau berlawanan ?PROLOG - EPILOG<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[44]='Dengan harga Rp. 240.000,-, seorang grosir membeli satu kardus buah apel yang berisi 12 lusin. Ia tahu dua lusin akan busuk sebelum dia menjualnya. Dengan harga berapa per lusin dia harus menjual jeruk itu untuk mendapat untung 1/3 dari harga seluruhnya ?';
        $soal[45]='Dalam rangkaian kata berikut ini, manakah kata yang berbeda dari yang lainnya ? 1. baja  2. ferum  3. timah    4. air   5. air raksa<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[46]='Anggaplah dua pernyataan pertama ini benar. Apakah pertanyaan terakhir : 1. benar 2. salah 3. tidak tahu<br>Karyawan pergi berlibur. Saya pergi berlibur. Saya adalah karyawan.<br><em>Isikan dengan angka saja untuk jawabannya</em>';
        $soal[47]='Tiga orang berbagi kemitraan dalam suatu usaha dimana X berinvestasi 3.000 dolar. Y sebesar 3.500 dolar dan Z sebesar 2.500 dolar. Jika keuntungan mencapai 1.500 dolar, lebih kurang berapa dolar yang didapat X jika keuntungan dibagi berdasarkan besarnya investasi ?';
        $soal[48]='Berapa balok yang menempel sisi balok X dari gambar berikut?><br><img src="../gambar/wptlike49.png">';
        $soal[49]='Untuk mencetak sebuah artikel berisi 30.000 kata, sebuah percetakan memutuskan untuk memakai dua ukuran jenis. Dengan menggunakan tipe yang lebih besar, sebuah halaman tercetak akan memuat 1.200 kata. Dengan tipe yang lebih kecil, sebuah halaman memuat 1.500 kata. Artikel ini masuk dalam 22 halaman di majalah. Berapa banyak halaman yang dibutuhkan untuk tipe yang lebih kecil ?';


        return $soal;
    }
}
