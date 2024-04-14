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
        }
    }

    public static function store(Request $request)
    {
        $jawaban = $request->jawaban;
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

    
}
