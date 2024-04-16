<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NVAController extends Controller
{
    public function getData($part,$id){
        $soal = Question::where('packet_id','=',67)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);
        $gambar_numerik = self::gambar();

        return response()->json([
            'gambar' => $gambar_numerik,
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }  

    public function getVerbal($part,$id){
        $soal = Question::where('packet_id','=',68)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);
        $paragraf = self::paragraf();

        return response()->json([
            'paragraf' => $paragraf,
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }

    public function getAbstrak($part,$id){
        $soal = Question::where('packet_id','=',69)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part
        ]);
    }

    public function getDataP($part,$id){
        $soal = Question::where('packet_id','=',70)->where('number','=',$part)->first();
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
            $part = 1;
            $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();

            $soal = Packet::select('amount')->where('test_id','=',$test->id)->where('part','=',$part)->first();

            if($path == 'numerik-40'){
                    return view('test.numeric-40',[
                        'part' => $part,
                        'path' => $path,
                        'test' => $test,
                        'selection' => $selection,
                        'packet' => $packet,
                        'jumlah_soal' => $soal->amount,
                    ]);
            }
            else if($path == 'verbal60'){

                return view('test.verbal-60',[
                    'part' => $part,
                    'path' => $path,
                    'test' => $test,
                    'selection' => $selection,
                    'packet' => $packet,
                    'jumlah_soal' => $soal->amount,
                ]);
            }
            else if($path == 'abstraksi24'){

                return view('test.abstraksi24',[
                    'part' => $part,
                    'path' => $path,
                    'test' => $test,
                    'selection' => $selection,
                    'packet' => $packet,
                    'jumlah_soal' => $soal->amount,
                ]);
            }

            else if($path == '16p'){
                return view('test.16p',[
                    'part' => $part,
                    'path' => $path,
                    'test' => $test,
                    'selection' => $selection,
                    'packet' => $packet,
                    'jumlah_soal' => $soal->amount,
                ]);
            }
        }
    }

    public static function store(Request $request)
    {
        $jawaban = $request->jawaban;
        if($request->packet_id != 70){
            if($request->packet_id == 67){
                $kunci=strtoupper("bebebcebacbaacbbbacaecedcacdabaecdbbccab");
                $table = 'numerik40';
            }
            if($request->packet_id == 68){
                $kunci=strtoupper("ccabbcacabcbbcccbcabcbbcaacaccaababcaccbaccbcbcbbcccaabbacac");
                $table = 'verbal60';
            }
            if($request->packet_id == 69){
                $kunci=strtoupper("ebdacabecaadaebbbeedbcbc");
                $table = 'abstraksi24';
            }
    
            $save_value = 0;
            for($i=1;$i <= count($jawaban);$i++){
                if($kunci[$i-1] == $jawaban[$i]){
                    $save_value++;
                }
            }
    
            $select = DB::table('norma_aptitude')->select($table)->where('nilai', $save_value)->first();
            $last_save['jawaban'] = $request->jawaban;
            $last_save['benar'] = $save_value;
            $last_save['iq'] = $select->$table;
        }
        else{
            $cek = self::penilaian_16p($jawaban);
            // [$aa,$bb,$cc,$ee,$ff,$gg,$hh,$ii,$ll,$mm,$nn,$oo,$q1,$q2,$q3,$q4,$md]
            $array = array();
            $array['aaw']= self::norma_16pf("a",$cek[0]); 
            $array['bbw']= self::norma_16pf("b",$cek[1]);
            $array['ccw']= self::norma_16pf("c",$cek[2]);
            $array['eew']= self::norma_16pf("e",$cek[3]);
            $array['ffw']= self::norma_16pf("f",$cek[4]);
            $array['ggw']= self::norma_16pf("g",$cek[5]);
            $array['hhw']= self::norma_16pf("h",$cek[6]);
            $array['iiw']= self::norma_16pf("i",$cek[7]);
            $array['llw']= self::norma_16pf("l",$cek[8]);
            $array['mmw']= self::norma_16pf("m",$cek[9]);
            $array['nnw']= self::norma_16pf("n",$cek[10]);
            $array['oow']= self::norma_16pf("o",$cek[11]);
            $array['q1w']= self::norma_16pf("q1",$cek[12]);
            $array['q2w']= self::norma_16pf("q2",$cek[13]);
            $array['q3w']= self::norma_16pf("q3",$cek[14]);
            $array['q4w']= self::norma_16pf("q4",$cek[15]);


            $last_save['jawaban'] = $jawaban;
            $last_save['penilaian'] = $array;

        }
        

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($last_save);
        $result->save();

        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
    }

    public static function gambar()
    {
        $cek[1] = 'numerik40_1.png';
        $cek[2] = 'numerik40_6.png';
        $cek[3] = 'numerik40_11.png';
        $cek[4] = 'numerik40_16.png';
        $cek[5] = 'numerik40_21.png';
        $cek[6] = 'numerik40_25.png';
        $cek[7] = 'numerik40_29.png';
        $cek[8] = 'numerik40_33.png';
        $cek[9] = 'numerik40_37.png';

        return $cek;

    }
    public static function paragraf(){

        $paragraf[0]='Baru-baru ini telah terbit suatu laporan tentang suatu studi yang dilakukan terhadap kebangkrutan perusahaan-perusahaan di Surabaya pada tahun terntentu. Laporan tersebut menunjukkan bahwa semua kebangkrutan, tanpa pengecualian, terjadi pada perusahaan yang mempekerjakan kurang dari 50 karyawan. Penelitian tersebut menunjukkan bahwa perusahaan kecil yang sedang mengalami kesulitan keuangan sering mengabaikan tunjangan-tunjangan yang seharusnya menjadi hak mereka. Contohnya, menurut laporan tersebut, tidak satupun dari perusahaan yang dinyatakan bangkrut pada tahun tersebut menerima subsidi ketenagakerjaan.';

        $paragraf[1]='Tidak perlu diragukan lagi bahwa secara fisiologis manusia mempunyai kesamaan dengan makhluk hidup yang lain, terutama jenis mamalia. Dapat dipastikan bahwa selama jutaan tahun manusia menyambung hidupnya dengan makan kacang-kacangan, gandum dan daun berair banyak, seperti yang dilakukan oleh kera, <strong><em>saudara</em></strong> kita yang terdekat saat ini, mengingat kesamaan dalam sistem pencernaan makanan.';

        $paragraf[2]='Selama kurang lebih 35 tahun ini, telah dilaporkan sejumlah penampakan UFO yang tidak dapat secara resmi diterangkan sebagai penampakan balon cuaca. Di lain pihak, reliabilitas dari para saksi penampakan UFO juga tidak dapat dipertanyakan.';

        $paragraf[3]='Walaupun banyak penemuan baru dalam bidang pengobatan kanker melalui pembedahan, radioaktif dan farmasi, satu dari setiap lima kematian disebabkan oleh kanker. Saat ini banyak perhatian dicurahkan terhadap usaha menghindari penyebab kanker yang telah dikenal seperti misalnya merokok, dan terhadap usaha mendeteksi gejala kanker secara lebih dini, untuk mengurangi timbulnya kasus-kasus fatal.';

        $paragraf[4]='Rasialisme adalah salah satu pengaruh yang paling merusak dalam masyarakat kita masa kini dan rasialisme inilah yang menjadi bagian integral ideologi fasisme dan ideologi ekstrim sayap kanan lainnya. Dalam tahun-tahun terakhir ini terdapat kecenderungan menyolok yaitu meningkatnya pandangan rasial di kalangan pengangguran kaum muda kulit putih.';

        $paragraf[5]='Kerugian besar dari surat menyurat bisnis adalah tidak adanya umpan balik yang cepat. Sedangkan dalam kondisi tatap muka, terdapat umpan balik verbal dan non verbal yang dapat mengubah arah komunikasi semula. Di lain pihak, komunikasi tertulis menguntungkan karena menghasilkan catatan yang permanen, dan memberikan waktu yang cukup untuk mempersiapkan komunikasi dengan lebih hati-hati dibandingkan dengan komunikasi tatap muka.';

        $paragraf[6]='Benarkah iklan menguntungkan konsumen? Bila produk dalam bidang tertentu terdiferensiasi dengan jelas, konsumen butuh bimbingan dan pilihan konsumen dapat dibantu dengan adanya iklan informatif. Di lain pihak, bila pilihan produk yang sejenis cukup banyak, hal ini kurang menguntungkan bagi konsumen, karena biaya iklan yang cukup besar juga termasuk dalam harga barang yang ia bayar. Contoh yang baik adalah deterjen. Deterjen sering diiklankan dengan biaya yang cukup besar. Saat ini tersedia cukup banyak merk, walaupun kenyataannya hanya beberapa perusahaan yang menguasai pasar.';

        $paragraf[7]='Sehubungan penelitian dalam bidang seleksi salesman di sebuah perusahaan berusaha menghubungkan berbagai karakteristik dengan prestasi kerja salesman yang baik dan dengan kestabilan dari suatu jabatan. Semua salesman yang direkrut dalam jangka waktu tertentu diikuti prestasi kerjanya selama 3 tahun selama diterima. Mereka yang saat itu masih bekerja dibandingkan dengan mereka yang telah keluar dan yang masih bekerja dibagi menjadi 3 kelompok, yang prestasi kerjanya baik, sedang dan kurang. Mereka yang keluar sebagian besar berusaha lebih muda berlatar belakang pendidikan lebih tinggi dan kurang memiliki pengalaman kerja di bidang penjualan.';

        $paragraf[8]='Dengan tidak adanya lembaga gabungan yang tepat antara pihak industri dan pemerintah untuk membicarakan atau merundingkan masalah harga konsumen, maka anggota serikat perdagangan, sebagai konsumen, harus mempersiapkan diri terhadap kenaikan harga dari sejumlah komoditi dan jasa, guna menyesuaikan tuntutan mereka sebagai produsen dan anggota serikat untuk menyeragamkan perbaikan kondisi kerja dan kondisi sosial.';

        $paragraf[9]='Sejak sumur-sumur minyak di dasar laut dihidupkan kembali dan makin besarnya ukuran kapal tanker minyak, genangan minyak yang terjadi akibat tumpahan minyak telah menjadi permasalahan yang cukup penting. Walaupun telah berusaha, teknologi belum berhasil mengatasinya, dan sebagai akibatnya terjadilah polusi. Satu-satunya cara yang efektif untuk membersihkan genangan minyak di laut adalah menyemprotnya dengan deterjen. Akan tetapi biayanya mahal dan dapat membahayakan kehidupan laut yang dilindunginya. Satu-satunya alternatif adalah dengan menggunakan sejenis mesin. Bermacam-macam peralatan mekanis untuk membersihkan lapisan minyak tersebut tidak selalu berhasil.';

        $paragraf[10]='Banyak jalan untuk memotivasi karyawan tingkat staf. Salah satu cara yang telah terbukti berhasil di banyak perusahaan unggul di Inggris adalah diperkenalkannya Jam Kerja Fleksibel. Keberanian memperkenalkan sistem tersebut menunjukkan penampilan progresif yang tentunya telah membantu perusahaan-perusahaan ini untuk tetap profitable (menguntungkan) walaupun kondisi ekonomi akhir-akhir ini kurang baik.';

        $paragraf[11]='Banyak wanita melakukan pengobatan Penggantian Hormon berdasarkan keyakinan yang sebenarnya salah, bahwa hal tersebut akan membantu mengatasi masalah seperti insomnia dan depresi, yang pada dasarnya tidak berkaitan dengan perubahan hormonal. Tetapi bagi beberapa wanita tertentu, pengobatan ini merupakan satu-satunya penyembuhan bagi timbulnya stress tertentu sehubungan dengan dimulainya tahapan usia pertengahan.';

        $paragraf[12]='Suatu komite yang ditunjuk untuk mendeteksi kebutuhan akan pendidikan non-kejuruan bagi orang dewasa di Inggris dan Wales dan kemudian meninjau kembali pengadaan program tersebut, menggarisbawahi hak-hak seorang dewasa untuk memperoleh pendidikan sepanjang hidupnya, dan mengungkapkan pendapat bahwa investasi pada karyawan dan bangunan dapat memberikan keuntungan yang besar dalam hal meningkatnya penggunaan potensi tenaga kerja. Sebagai hasil dari laporan komite tersebut, kini banyak pengusaha setempat mempekerjakan seorang community education worker (bekerja sebagai pendidik komunitas), yang tugasnya adalah menentukan pengadaan program yang sesuai dan menyusun kursus-kursus yang relevan.';

        $paragraf[13]='Bangunan-bangunan yang terdaftar meliputi bangunan-bangunan yang didirikan sebelum tahun 1700 asalkan kondisi bangunan tersebut masih cukup asli, dan banyak bangunan-bangunan yang didirikan antara tahun 1700 dan 1840 walaupun penyeleksiannya lebih hati-hati. Beberapa bangunan dari antara tahun 1840 sampai 1914 juga terdaftar asalkan kualitas dan karakternya baik atau dirancang oleh arsitek-arsitek ternama. Sedikit sekali bangunan yang didirikan setelah tahun 1914 yang dimasukkan dalam daftar, sebagian besar adalah hasil karya arsitek ternama pada masa itu.';

        $paragraf[14]='Walaupun negara-negara Eropa Barat berbeda dalam hal stabilitas politik dan tingkat penerapan sosialisme, kesamaan ciri di antara mereka adalah dalam hal penerapan Ekonomi Campuran. Yang dimaksud dengan Ekonomi Campuran adalah beberapa sektor industri telah diambil alih oleh negara dan sekarang dikuasai oleh swasta. Industri yang dinasionalisasi biasanya adalah transportasi, telekomunikasi, besi dan baja. Ciri umum yang lain adalah perusahaan yang dinasionalisasi umumnya kurang memberikan keuntungan yang memadai.';

        $paragraf[15]='Seksi kontrol kredit di perusahaan kami mempunyai suatu daftar yang berisi perusahaan-perusahaan dan individu-individu yang potensial untuk diberi kredit, yang terdiri dari 3 kategori: daftar A berisi mereka yang langsung dapat diberikan kredit tanpa mempertanyakannya lagi; daftar B berisi mereka yang dapat diberi kredit setelah diperiksa Hutang Lancar-nya atau bila didukung oleh referensi; dan daftar C berisi mereka yang sudah pernah menunda dalam hal pembayaran. Para nasabah baru selalu dimasukkan dalam daftar B.';

        $paragraf[16]='Suatu penelitian tentang para sarjana menunjukkan bahwa setelah mereka bekerja selama 5 tahun, rerata penghasilan mereka 33% lebih besar dibandingkan dengan orang lain yang usianya sama. Penelitian tersebut juga menunjukkan bahwa anak-anak mereka mempunyai kemungkinan dua setengah kali lebih besar untuk masuk ke perguruan tinggi dibandingkan dengan anak-anak dari mereka yang bukan sarjana. Akan tetapi bagi sarjana wanita, kemungkinan bahwa mereka tidak menikah atau menimang anak lebih besar dibandingkan dengan wanita yang bukan sarjana.';

        $paragraf[17]='Pendapatan para kapitalis diperoleh dari surplus kekayaan yang diciptakan oleh kelompok pekerja. Surplus kekayaan ini sebenarnya dibutuhkan untuk memberikan tunjangan kepad keluarga para pekerja tersebut. Oleh karena itu, suatu pemerintahan sosialis mengambil bagian dari surplus tersebut dalam bentuk pajak, agar dapat memberikan tunjangan tersebut. Namun demikian pemerintah juga menarik pajak dari para pekerja, guna memberikan tunjangan bagi para penganggur dan kelompok-kelompok yang kurang beruntung. Sebagai akibatnya, kebijakan pemerintah di bidang pajak seringkali ditentang keras oleh para pekerja dan para kapitalis.';

        $paragraf[18]='Menurut Akte Hak Cipta tahun 1709. satu-satunya pemiliki hak cipta adalah para pencipta dan keturunannya selama 14 tahun, dan dapat diperbarui untuk periode yang sama jika si pencipta masih hidup. Pada tahun 1814, periode tersebut diperpanjang menjadi 28 tahun atau seumur hidup, dan dengan kate tahun 1842 diperpanjang menjadi 42 tahun atau 7 tahun setelah kematian. Akhirnya, akte 1911 memperpanjang periode itu menjadi 50 tahun setelah kematian si pencipta. Semua akte dan perubahan-perubahannya diartikan sebagai berlaku surut.';

        $paragraf[19]='Walaupun telah banyak dilakukan usaha untuk memacu program-program keluarga berencana dan riset di bidang teknologi pangan, pangan bagi penduduk dunia kian hari kian menjadi masalah. Telah diperkirakan bahwa dua per tiga manusia akan hidup pada atau dibawah tingkat kehidupan minimal pada awal abad ini jika populasi dunia dan produksi pangan tetap tumbuh dengan tingkat kecepatan yang sekarang. Dan banyak pula problem lain yang berkaitan dengan hal tersebut. Pada saat yang sama dan berdasarkan asumsi-asumsi yang sama, minyak bumi sudah akan habis dan transportasi pribadi jarak jauh tidak akan ekonomis lagi.';
        return $paragraf;
    }

    public static function norma_16pf($kolom,$nilai){
        $norma = DB::table('norma_16pf')->select($kolom)->where('nilai', $nilai)->first();
        return $norma->$kolom;
    }

    public static function penilaian_16p($T){
        $aa=0;
        $bb=0;
        $cc=0;
        $ee=0;
        $ff=0;
        $gg=0;
        $hh=0;
        $ii=0;
        $ll=0;
        $mm=0;
        $nn=0;
        $oo=0;
        $q1=0;
        $q2=0;
        $q3=0;
        $q4=0;
        $md=0;
    
        if (($T[2]=="B")) $a[1]=1;    else if (($T[2]=="C")) $a[1] = 2; else  $a[1]=0;
        if (($T[19]=="B")) $a[2]=1;   else if (($T[19]=="A")) $a[2] = 2; else  $a[2] = 0;
        if (($T[36]=="B")) $a[3] = 1; else if (($T[36]=="C")) $a[3] = 2 ;else  $a[3] = 0;
        if (($T[53]=="B")) $a[4] = 1; else if (($T[53]=="A")) $a[4] = 2 ;else  $a[4] = 0;
        if (($T[70]=="B")) $a[5] = 1; else if (($T[70]=="A")) $a[5] = 2 ;else  $a[5] = 0;
        if (($T[87]=="B")) $a[6] = 1; else if (($T[87]=="C")) $a[6] = 2 ;else  $a[6] = 0;

        if (($T[3]=="B")) $b[1]=1;  else $b[1] = 0;
        if (($T[20]=="A")) $b[2] =1; else $b[2] = 0; //Kesalahan Kunci no.20 jawaban C
        if (($T[37]=="B")) $b[3] =1; else $b[3] = 0;
        if (($T[54]=="C")) $b[4] =1; else $b[4] = 0;
        if (($T[71]=="A")) $b[5] =1; else $b[5] = 0;
        if (($T[88]=="C")) $b[6] =1; else $b[6] = 0;
        if (($T[104]=="A")) $b[7] =1; else $b[7] = 0;
        if (($T[105]=="B")) $b[8] =1; else $b[8] = 0;
    
        if (($T[4]=="A")) $c[1] = 2;  else if (($T[4]=="B")) $c[1] = 1; else $c[1] = 0;
        if (($T[21]=="A")) $c[2] = 2; else if (($T[21]=="B")) $c[2] = 1; else $c[2] = 0;
        if (($T[38]=="C")) $c[3] = 2; else if (($T[38]=="B")) $c[3] = 1; else $c[3] = 0;
        if (($T[55]=="A")) $c[4] = 2; else if (($T[55]=="B")) $c[4] = 1; else $c[4] = 0;
        if (($T[72]=="C")) $c[5] = 2; else if (($T[72]=="B")) $c[5] = 1; else $c[5] = 0;
        if (($T[89]=="C")) $c[6] = 2; else if (($T[89]=="B")) $c[6] = 1; else $c[6] = 0;

        if (($T[5]=="B")) $e[1] = 1;  else if (($T[5]=="C")) $e[1] = 2; else $e[1] = 0;
        if (($T[22]=="B")) $e[2] = 1;
        else if (($T[22]=="C")) $e[2] = 2; else $e[2] = 0;
        if (($T[39]=="B")) $e[3] = 1;
        else if (($T[39]=="A")) $e[3] = 2; else $e[3] = 0;
        if (($T[56]=="B")) $e[4] = 1;
        else if (($T[56]=="A")) $e[4] = 2; else $e[4] = 0;
        if (($T[73]=="B")) $e[5] = 1;
        else if (($T[73]=="C")) $e[5] = 2; else $e[5] = 0;
        if (($T[90]=="B")) $e[6] = 1;
        else if (($T[90]=="A")) $e[6] = 2; else $e[6] = 0;
        
        if (($T[6]=="B")) $f[1] = 1;
        else if (($T[6]=="C")) $f[1] = 2; else $f[1] = 0;
        if (($T[23]=="B")) $f[2] = 1;
        else if (($T[23]=="A")) $f[2] = 2; else $f[2] = 0;
        if (($T[40]=="B")) $f[3] = 1;
        else if (($T[40]=="C")) $f[3] = 2; else $f[3] = 0;
        if (($T[57]=="B")) $f[4] = 1;
        else if (($T[57]=="A")) $f[4] = 2; else $f[4] = 0;
        if (($T[74]=="B")) $f[5] = 1;
        else if (($T[74]=="A")) $f[5] = 2; else $f[5] = 0;
        if (($T[91]=="B")) $f[6] = 1;
        else if (($T[91]=="C")) $f[6] = 2; else $f[6] = 0;
        
        if (($T[7]=="B")) $g[1] = 1;
        else if (($T[7]=="A")) $g[1] = 2; else $g[1] = 0;
        if (($T[24]=="B")) $g[2] = 1;
        else if (($T[24]=="C")) $g[2] = 2; else $g[2] = 0;
        if (($T[41]=="B")) $g[3] = 1;
        else if (($T[41]=="A")) $g[3] = 2; else $g[3] = 0;
        if (($T[58]=="B")) $g[4] = 1;
        else if (($T[58]=="C")) $g[4] = 2; else $g[4] = 0;
        if (($T[75]=="B")) $g[5] = 1;
        else if (($T[75]=="A")) $g[5] = 2; else $g[5] = 0;
        if (($T[92]=="B")) $g[6] = 1;
        else if (($T[92]=="C")) $g[6] = 2; else $g[6] = 0;
        
        if (($T[8]=="B")) $h[1] = 1;
        else if (($T[8]=="A")) $h[1] = 2; else $h[1] = 0;
        if (($T[25]=="B")) $h[2] = 1;
        else if (($T[25]=="C")) $h[2] = 2; else $h[2] = 0;
        if (($T[42]=="B")) $h[3] = 1;
        else if (($T[42]=="C")) $h[3] = 2; else $h[3] = 0;
        if (($T[59]=="B")) $h[4] = 1;
        else if (($T[59]=="A")) $h[4] = 2; else $h[4] = 0;
        if (($T[76]=="B")) $h[5] = 2;
        else if (($T[76]=="A")) $h[5] = 2; else $h[5] = 0;
        if (($T[93]=="B")) $h[6] = 1;
        else if (($T[93]=="C")) $h[6] = 2; else $h[6] = 0;
        
        if (($T[9]=="B")) $i2[1] = 1;
        else if (($T[9]=="A")) $i2[1] = 2; else $i2[1] = 0;
        if (($T[26]=="B")) $i2[2] = 1;
        else if (($T[26]=="A")) $i2[2] = 2; else $i2[2] = 0;
        if (($T[43]=="B")) $i2[3] = 1;
        else if (($T[43]=="C")) $i2[3] = 2; else $i2[3] = 0;
        if (($T[60]=="B")) $i2[4] = 1;
        else if (($T[60]=="A")) $i2[4] = 2; else $i2[4] = 0;
        if (($T[77]=="B")) $i2[5] = 1;
        else if (($T[77]=="C")) $i2[5] = 2; else $i2[5] = 0;
        if (($T[94]=="B")) $i2[6] = 1;
        else if (($T[94]=="C")) $i2[6] = 2; else $i2[6] = 0;
        
        if (($T[10]=="B")) $l[1] = 1;
        else if (($T[10]=="A")) $l[1] = 2; else $l[1] = 0;
        if (($T[27]=="B")) $l[2] = 1;
        else if (($T[27]=="C")) $l[2] = 2; else $l[2] = 0;
        if (($T[44]=="B")) $l[3] = 1;
        else if (($T[44]=="C")) $l[3] = 2; else $l[3] = 0;
        if (($T[61]=="B")) $l[4] = 1;
        else if (($T[61]=="C")) $l[4] = 2; else $l[4] = 0;
        if (($T[78]=="B")) $l[5] = 1;
        else if (($T[78]=="A")) $l[5] = 2; else $l[5] = 0;
        if (($T[95]=="B")) $l[6] = 1;
        else if (($T[95]=="A")) $l[6] = 2; else $l[6] = 0;
        
        if (($T[11]=="B")) $m[1] = 1;
        else if (($T[11]=="C")) $m[1] = 2; else $m[1] = 0;
        if (($T[28]=="B")) $m[2] = 1;
        else if (($T[28]=="C")) $m[2] = 2; else $m[2] = 0;
        if (($T[45]=="B")) $m[3] = 1;
        else if (($T[45]=="A")) $m[3] = 2; else $m[3] = 0;
        if (($T[62]=="B")) $m[4] = 1;
        else if (($T[62]=="A")) $m[4] = 2; else $m[4] = 0;
        if (($T[79]=="B")) $m[5] = 1;
        else if (($T[79]=="A")) $m[5] = 2; else $m[5] = 0;
        if (($T[96]=="B")) $m[6] = 1;
        else if (($T[96]=="C")) $m[6] = 2; else $m[6] = 0;
        
        if (($T[12]=="B")) $n[1] = 1;
        else if (($T[12]=="C")) $n[1] = 2; else $n[1] = 0;
        if (($T[29]=="A")) $n[2] = 2;
        else if (($T[29]=="B")) $n[2] = 1; else $n[2] = 0;
        if (($T[46]=="A")) $n[3] = 2;
        else if (($T[46]=="B")) $n[3] = 1; else $n[3] = 0;
        if (($T[63]=="A")) $n[4] = 2;
        else if (($T[63]=="B")) $n[4] = 1; else $n[4] = 0;
        if (($T[80]=="B")) $n[5] = 1;
        else if (($T[80]=="C")) $n[5] = 2; else $n[5] = 0;
        if (($T[97]=="B")) $n[6] = 1;
        else if (($T[97]=="C")) $n[6] = 2; else $n[6] = 0;
        
        if (($T[13]=="B")) $o[1] = 1;
        else if (($T[13]=="C")) $o[1] = 2; else $o[1] = 0;
        if (($T[30]=="A")) $o[2] = 2;
        else if (($T[30]=="B")) $o[2] = 1; else $o[2] = 0;
        if (($T[47]=="B")) $o[3] = 1;
        else if (($T[47]=="C")) $o[3] = 2; else $o[3] = 0;
        if (($T[64]=="A")) $o[4] = 2;
        else if (($T[64]=="B")) $o[4] = 1; else $o[4] = 0;
        if (($T[81]=="B")) $o[5] = 1;
        else if (($T[81]=="C")) $o[5] = 2; else $o[5] = 0;
        if (($T[98]=="A")) $o[6] = 2;
        else if (($T[98]=="B")) $o[6] = 1; else $o[6] = 0;
        
        if (($T[14]=="A")) $qq1[1] = 2;
        else if (($T[14]=="B")) $qq1[1] = 1; else $qq1[1] = 0;
        if (($T[31]=="A")) $qq1[2] = 2;
        else if (($T[31]=="B")) $qq1[2] = 1; else $qq1[2] = 0;
        if (($T[48]=="B")) $qq1[3] = 1;
        else if (($T[48]=="C")) $qq1[3] = 2; else $qq1[3] = 0;
        if (($T[65]=="B")) $qq1[4] = 1;
        else if (($T[65]=="C")) $qq1[4] = 2; else $qq1[4] = 0;
        if (($T[82]=="B")) $qq1[5] = 1;
        else if (($T[82]=="C")) $qq1[5] = 2; else $qq1[5] = 0;
        if (($T[99]=="A")) $qq1[6] = 2;
        else if (($T[99]=="B")) $qq1[6] = 1; else $qq1[6] = 0;
        
        if (($T[15]=="A")) $qq2[1] = 2;
        else if (($T[15]=="B")) $qq2[1] = 1; else $qq2[1] = 0;
        if (($T[32]=="B")) $qq2[2] = 1;
        else if (($T[32]=="C")) $qq2[2] = 2; else $qq2[2] = 0;
        if (($T[49]=="A")) $qq2[3] = 2;
        else if (($T[49]=="B")) $qq2[3] = 1; else $qq2[3] = 0;
        if (($T[66]=="A")) $qq2[4] = 2;
        else if (($T[66]=="B")) $qq2[4] = 1; else $qq2[4] = 0;
        if (($T[83]=="B")) $qq2[5] = 1;
        else if (($T[83]=="C")) $qq2[5] = 2; else $qq2[5] = 0;
        if (($T[100]=="B")) $qq2[6] = 1;
        else if (($T[100]=="C")) $qq2[6] = 2; else $qq2[6] = 0;
        
        if (($T[16]=="A")) $qq3[1] = 2;
        else if (($T[16]=="B")) $qq3[1] = 1; else $qq3[1] = 0;
        if (($T[33]=="A")) $qq3[2] = 2;
        else if (($T[33]=="B")) $qq3[2] = 1; else $qq3[2] = 0;
        if (($T[50]=="A")) $qq3[3] = 2;
        else if (($T[50]=="B")) $qq3[3] = 1; else $qq3[3] = 0;
        if (($T[67]=="B")) $qq3[4] = 1;
        else if (($T[67]=="C")) $qq3[4] = 2; else $qq3[4] = 0;
        if (($T[84]=="B")) $qq3[5] = 1;
        else if (($T[84]=="C")) $qq3[5] = 2; else $qq3[5] = 0;
        if (($T[101]=="B")) $qq3[6] = 1;
        else if (($T[101]=="C")) $qq3[6] = 2; else $qq3[6] = 0;
        
        if (($T[17]=="A")) $qq4[1] = 2;
        else if (($T[17]=="B")) $qq4[1] = 1; else $qq4[1] = 0;
        if (($T[34]=="B")) $qq4[2] = 1;
        else if (($T[34]=="C")) $qq4[2] = 2; else $qq4[2] = 0;
        if (($T[51]=="B")) $qq4[3] = 1;
        else if (($T[51]=="C")) $qq4[3] = 2; else $qq4[3] = 0;
        if (($T[68]=="A")) $qq4[4] = 2;
        else if (($T[68]=="B")) $qq4[4] = 1; else $qq4[4] = 0;
        if (($T[85]=="B")) $qq4[5] = 1;
        else if (($T[85]=="C")) $qq4[5] = 2; else $qq4[5] = 0;
        if (($T[102]=="A")) $qq4[6] = 2;
        else if (($T[102]=="B")) $qq4[6] = 1; else $qq4[6] = 0;
        
        if (($T[1]=="B")) $mdarr[1] = 1;
        else if (($T[1]=="A")) $mdarr[1] = 2; else $mdarr[1] = 0;
        if (($T[18]=="B")) $mdarr[2] = 1;
        else if (($T[18]=="C")) $mdarr[2] = 2; else $mdarr[2] = 0;
        if (($T[35]=="B")) $mdarr[3] = 1;
        else if (($T[35]=="C")) $mdarr[3] = 2; else $mdarr[3] = 0;
        if (($T[52]=="B")) $mdarr[4] = 1;
        else if (($T[52]=="A")) $mdarr[4] = 2; else $mdarr[4] = 0;
        if (($T[69]=="B")) $mdarr[5] = 1;
        else if (($T[69]=="C")) $mdarr[5] = 2; else $mdarr[5] = 0;
        if (($T[86]=="B")) $mdarr[6] = 1;
        else if (($T[86]=="C")) $mdarr[6] = 2; else $mdarr[6] = 0;
        if (($T[103]=="B")) $mdarr[7] = 1;
        else if (($T[103]=="C")) $mdarr[7] = 2; else $mdarr[7] = 0;

        $bb=0;
        for ($z=1;$z<=8;$z++)
        {
            $bb=$bb+$b[$z];
        }
        $md=0;
        for ($z=1;$z<=7;$z++)
        {
            $md=$md+$mdarr[$z];
        }
        for ($z=1;$z<=6;$z++)
        {
            $aa=$aa+$a[$z];
            $cc=$cc+$c[$z];
            $ee=$ee+$e[$z];
            $ff=$ff+$f[$z];
            $gg=$gg+$g[$z];
            $hh=$hh+$h[$z];
            $ii=$ii+$i2[$z];
            $ll=$ll+$l[$z];
            $mm=$mm+$m[$z];
            $nn=$nn+$n[$z];
            $oo=$oo+$o[$z];
            $q1=$q1+$qq1[$z];
            $q2=$q2+$qq2[$z];
            $q3=$q3+$qq3[$z];
            $q4=$q4+$qq4[$z];
        }

        $hasil = [$aa,$bb,$cc,$ee,$ff,$gg,$hh,$ii,$ll,$mm,$nn,$oo,$q1,$q2,$q3,$q4,$md];

        return $hasil;
    }

    
}
