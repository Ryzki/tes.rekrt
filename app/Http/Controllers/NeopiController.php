<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeopiController extends Controller
{
    public function getData($num){
        $soal = self::data();
        foreach($soal as $key=>$data){
            $re_arrange[$key-1] = $data;
        }

        $soal[0]['soal'] = $re_arrange;

        return response()->json([
            'quest' => $soal,
            'num'=> $num
        ]);
    }

    public static function index(Request $request, $path, $test, $selection)
    {

        $cek_test = existTest($test->id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }
        else{
            // Get the packet and questions
            $packet = Packet::where('test_id','=',102)->where('status','=',1)->first();

            // View
            return view('test/neopi', [
                'packet' => $packet,
                'path' => $path,
                // 'questions' => $questions,
                'selection' => $selection,
                'test' => $test,
            ]);
        }
    }

    public static function store(Request $request)
    {
        // dd($request->all());
        $jawaban = $request->jawaban;

        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($jawaban);
        $result->save();

        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes ']);
    }

    public static function data(){
        $soal[1]='Saya bukan seorang yang mudah cemas.';
        $soal[2]='Saya benar-benar menyukai sebagian besar orang yang saya jumpai.';
        $soal[3]='Saya memiliki kemampuan imajinasi yang sangat aktif.';
        $soal[4]='Saya cenderung sinis dan skeptis pada niat baik orang lain.';
        $soal[5]='Saya dikenal karena cara berpikir dan kebijaksanaan saya.';
        $soal[6]='Saya sering marah pada cara orang memperlakukan saya.';
        $soal[7]='Saya tidak bisa bergaul.';
        $soal[8]='Masalah estetika dan seni bukanlah hal yang sangat penting bagi saya.';
        $soal[9]='Saya tidak licik dan juga tidak curang.';
        $soal[10]='Saya lebih suka pada segala pilihan terbuka daripada perencanaan ke depan yang baku.';
        $soal[11]='Saya jarang merasa kesepian atau sedih.';
        $soal[12]='Saya dominan, kuat, tegas dan asertif (menyatakan apa adanya).';
        $soal[13]='Tanpa emosi yang kuat hidup ini akan tidak menarik bagi saya.';
        $soal[14]='Beberapa orang berpikir bahwa saya ini mementingkan diri sendiri dan egois.';
        $soal[15]='Saya berusaha melaksanakan semua tugas saya dengan penuh kesadaran.';
        $soal[16]='Dalam menghadapi orang lain, saya selalu takut menemui hambatan sosial.';
        $soal[17]='Saya bekerja dan bermain dengan gaya santai.';
        $soal[18]='Saya merasa dapat bertindak dengan cara-cara yang bervariasi.';
        $soal[19]='Saya lebih suka bekerja sama dengan orang lain daripada bersaing dengan mereka.';
        $soal[20]='Saya orang yang suka seenaknya saja dan lesu.';
        $soal[21]='Saya jarang patuh pada sesuatu secara berlebihan.';
        $soal[22]='Saya sering membutuhkan kegembiraan.';
        $soal[23]='Saya sering menikmati permainan dengan teori dan gagasan abstrak.';
        $soal[24]='Saya tidak segan-segan menunjukkan bakat dan prestasi saya.';
        $soal[25]='Saya cukup bagus dalam mengatur irama kerja, sehingga segala sesuatu selesai pada waktunya.';
        $soal[26]='Saya sering merasa tidak mampu dan berharap orang lain dapat memecahkan masalah saya.';
        $soal[27]='Saya belum pernah melompat-lompat kegirangan.';
        $soal[28]='Saya percaya bila membiarkan siswa-siswa mendengarkan pembicara yang kontroversial, dapat membingungkan dan menyesatkan mereka.';
        $soal[29]='Pemimpin politik perlu lebih menyadari sisi kemanusiaan dalam mengambil kebijakan.';
        $soal[30]='Selama bertahun-tahun saya telah melakukan beberapa hal yang cukup tolol.';
        $soal[31]='Saya mudah merasa ketakutan.';
        $soal[32]='Saya tidak mendapatkan banyak kesenangan dalam mengobrol dengan orang lain.';
        $soal[33]='Saya berusaha untuk tetap menjaga pikiran agar terarah pada hal-hal realistis dan menghindari terlalu banyak berkhayal.';
        $soal[34]='Saya percaya bahwa sebagian besar orang pada dasarnya bermaksud baik.';
        $soal[35]='Menurut pendapat saya, orang yang golput (tidak menggunakan hak suara) pada pemilu bukanlah warga negara yang baik.';
        $soal[36]='Saya adalah orang yang tenang dan tidak temperamental/emosional.';
        $soal[37]='Saya suka berada diantara banyak orang.';
        $soal[38]='Saya terkadang betul-betul hanyut dalam musik yang saya dengarkan.';
        $soal[39]='Bila perlu, saya memanfaatkan orang untuk mendapatkan sesuatu yang saya inginkan.';
        $soal[40]='Saya menjaga semua barang milik saya dengan rapi dan bersih.';
        $soal[41]='Terkadang saya merasa diri saya betul-betul tidak berguna.';
        $soal[42]='Saya terkadang tidak bisa kukuh dalam pendirian dan bertindak, seperti sebagaimana mestinya.';
        $soal[43]='Saya jarang sekali mengalami emosi yang sangat kuat.';
        $soal[44]='Saya berusaha berlaku sopan pada siapapun yang saya jumpai.';
        $soal[45]='Terkadang saya tidak dapat diandalkan atau dipercaya sebagaimana yang seharusnya bisa saya lakukan.';
        $soal[46]='Saya jarang merasa kikuk atau canggung ditengah orang banyak.';
        $soal[47]='Bila saya mengerjakan sesuatu, saya menjalankannya dengan sungguh-sungguh dan tekun.';
        $soal[48]='Menurut pendapat saya adalah hal yang menarik untuk mempelajari dan mengembangkan hobi/ketertarikan baru.';
        $soal[49]='Saya dapat bersikap kasar dan tajam.';
        $soal[50]='Saya mempunyai tujuan-tujuan yang jelas dan mewujudkannya dengan cara yang rapi.';
        $soal[51]='Saya memiliki kesulitan melawan dorongan-dorongan keinginan saya sendiri.';
        $soal[52]='Saya tidak dapat menikmati liburan di suatu kota yang sibuk dan penuh keramaian.';
        $soal[53]='Bagi saya, alasan-alasan filosofis adalah hal yang membosankan.';
        $soal[54]='Saya tidak suka membicarakan tentang diri sendiri dan prestasi saya.';
        $soal[55]='Saya banyak membuang waktu sebelum saya betul-betul mengerjakan pekerjaan.';
        $soal[56]='Saya merasa mampu menghadapi sebagian besar permasalahan saya.';
        $soal[57]='Saya terkadang mengalami kegembiraan dan kebahagiaan yang luar biasa.';
        $soal[58]='Saya yakin bahwa hukum dan kebijakan sosial harus disesuaikan sebagai jawaban atas tuntutan dunia yang terus berubah.';
        $soal[59]='Saya berkemauan keras dan berpendirian kuat.';
        $soal[60]='Saya memikirkan segala sesuatu secara mendalam sebelum mengambil keputusan.';
        $soal[61]='Saya jarang merasa takut atau cemas.';
        $soal[62]='Saya dikenal sebagai orang yang hangat dan ramah.';
        $soal[63]='Saya memiliki kehidupan fantasi yang aktif.';
        $soal[64]='Saya percaya bahwa sebagian besar orang akan memanfaatkannya apabila diberikan peluang.';
        $soal[65]='Saya selalu berusaha memperoleh perkembangan informasi untuk membuat keputusan yang tepat.';
        $soal[66]='Saya dikenal sebagai seorang yang pemarah/temperamental.';
        $soal[67]='Saya biasanya suka mengerjakan segala sesuatunya sendiri.';
        $soal[68]='Menonton seni tari atau tari modern adalah hal yang membosankan bagi saya.';
        $soal[69]='Saya tidak bisa membohongi siapapun, sekalipun sebenarnya saya berkeinginan untuk melakukannya.';
        $soal[70]='Saya bukan orang yang kaku terhadap aturan-aturan yang ada.';
        $soal[71]='Saya jarang bersedih atau murung.';
        $soal[72]='Saya sering menjadi pemimpin dalam kelompok saya.';
        $soal[73]='Bagi saya, bagaimana perasaan saya mengenai sesuatu adalah hal penting.';
        $soal[74]='Beberapa orang menganggap saya dingin dan perhitungan.';
        $soal[75]='Saya melunasi hutang atau janji dengan tepat waktu.';
        $soal[76]='Terkadang saya merasa begitu malu sehingga saya ingin mengucilkan diri.';
        $soal[77]='Pekerjaan saya mungkin lambat tetapi mantap.';
        $soal[78]='Sekali saya menemukan cara yang tepat untuk mengerjakan sesuatu, saya bertahan dengan cara tersebut.';
        $soal[79]='Saya ragu mengungkapkan kemarahan, sekalipun saya rasa sudah saatnya mengungkapkannya.';
        $soal[80]='Bila saya mulai program pengembangan diri, biasanya saya gagal setelah berlangsung beberapa hari.';
        $soal[81]='Tidak sulit bagi saya untuk melawan godaan.';
        $soal[82]='Terkadang saya mengerjakan sesuatu hanya untuk \"gebrakan\" atau \"sensasi\".';
        $soal[83]='Saya senang memecahkan masalah dan teka-teki.';
        $soal[84]='Saya lebih baik dari sebagian besar orang, dan saya menyadari hal itu.';
        $soal[85]='Saya adalah orang yang produktif, yang selalu mampu menyelesaikan pekerjaan.';
        $soal[86]='Bila saya mengalami stress atau tekanan yang berat, terkadang saya merasa hancur berkeping-keping.';
        $soal[87]='Saya bukan tipe orang yang optimis atau periang.';
        $soal[88]='Saya yakin kita harus mengacu pada pemuka agama untuk mengambil keputusan yang menyangkut masalah moral.';
        $soal[89]='Kita harus berbuat lebih banyak untuk orang miskin dan orang lanjut usia.';
        $soal[90]='Terkadang saya berbuat terlebih dulu, baru berpikir kemudian.';
        $soal[91]='Saya sering merasa tegang dan gugup.';
        $soal[92]='Banyak orang menganggap saya dingin dan menjaga jarak.';
        $soal[93]='Saya tidak suka membuang waktu dengan melamun.';
        $soal[94]='Saya menganggap sebagian besar orang yang saya hadapi adalah jujur dan dapat dipercaya.';
        $soal[95]='Saya sering menghadapi situasi tanpa betul-betul siap.';
        $soal[96]='Saya tidak dianggap sebagai orang yang mudah tersinggung dan temperamental.';
        $soal[97]='Saya merasa sangat membutuhkan kehadiran orang lain pada saat saya sendirian dalam waktu yang lama.';
        $soal[98]='Saya tergelitik/terkesan pada pola-pola yang saya lihat pada bidang seni dan alam.';
        $soal[99]='Berlaku sangat jujur merupakan hal yang tidak tepat dalam menjalankan bisnis.';
        $soal[100]='Saya suka menyimpan sesuatu di tempatnya, sehingga dengan mudah saya dapat menemukannya.';
        $soal[101]='Saya terkadang mengalami perasaan bersalah dan berdosa yang mendalam.';
        $soal[102]='Dalam pertemuan, saya biasanya lebih suka membiarkan orang lain berbicara.';
        $soal[103]='Saya jarang memperhatikan perasaan saya.';
        $soal[104]='Saya umumnya berusaha mengerti dan bertenggang rasa terhadap orang lain.';
        $soal[105]='Terkadang saya berlaku curang saat bermain kartu seorang diri.';
        $soal[106]='Bukanlah hal yang sangat memalukan bila ada orang menertawakan atau mengejek saya.';
        $soal[107]='Saya sering dipenuhi dengan energi.';
        $soal[108]='Saya sering mencoba makanan baru dan makanan asing.';
        $soal[109]='Bila saya tidak menyukai orang, saya biarkan orang tersebut mengetahuinya.';
        $soal[110]='Saya bekerja keras untuk mencapai tujuan saya.';
        $soal[111]='Bila saya menyantap makanan kesukaan saya, saya cenderung makan terlalu banyak.';
        $soal[112]='Saya cenderung menghindari cerita film yang mengejutkan atau menakutkan.';
        $soal[113]='Saya terkadang kehilangan ketertarikan pada orang, bila ia berbicara masalah yang sangat abstrak dan teoritis.';
        $soal[114]='Saya berusaha untuk rendah hati.';
        $soal[115]='Saya merasa kesulitan untuk mengerjakan apa yang seharusnya dilakukan.';
        $soal[116]='Saya berusaha tetap tenang dalam keadaan genting.';
        $soal[117]='Terkadang saya terbuai dalam kebahagiaan.';
        $soal[118]='Saya menghargai adanya perbedaan pendapat, karena perbedaan pendapat diantara masyarakat adalah hal yang wajar.';
        $soal[119]='Saya tidak simpatik pada orang yang suka meminta.';
        $soal[120]='Saya selalu mempertimbangkan segala akibat sebelum saya melakukan sesuatu.';
        $soal[121]='Saya jarang kuatir tentang masa depan saya.';
        $soal[122]='Saya benar-benar senang berbincang dengan orang lain.';
        $soal[123]='Saya senang melamun atau mengkhayal, menelusuri segala kemungkinan dan membiarkannya tumbuh dan berkembang.';
        $soal[124]='Saya curiga pada orang yang berlaku manis terhadap saya.';
        $soal[125]='Saya bangga pada penalaran dan akal sehat yang saya miliki.';
        $soal[126]='Saya sering merasa muak pada orang-orang yang saya hadapi.';
        $soal[127]='Saya menyukai pekerjaan yang dapat saya kerjakan sendiri, tanpa diganggu oleh orang lain.';
        $soal[128]='Puisi sangat sedikit atau bahkan sama sekali tidak memberikan pengaruh bagi diri saya.';
        $soal[129]='Saya tidak ingin dianggap munafik.';
        $soal[130]='Saya tampaknya tidak akan pernah bisa teratur.';
        $soal[131]='Saya cenderung menyalahkan diri saya sendiri bila ada sesuatu yang salah.';
        $soal[132]='Orang lain sering mengharapkan saya untuk membuat keputusan.';
        $soal[133]='Saya mengalami berbagai gejolak emosi dan perasaan.';
        $soal[134]='Saya tidak dikenal karena kemurahan hati saya.';
        $soal[135]='Bila saya telah berkomitmen, saya selalu dapat diandalkan untuk menindaklanjuti.';
        $soal[136]='Saya sering merasa rendah diri di hadapan orang lain.';
        $soal[137]='Saya tidak gesit atau bersemangat seperti orang lain.';
        $soal[138]='Saya lebih suka meluangkan waktu di lingkungan yang sudah saya kenal.';
        $soal[139]='Bila saya dipermalukan, saya mencoba memaafkan atau melupakannya.';
        $soal[140]='Saya tidak senang bila didorong-dorong untuk maju.';
        $soal[141]='Saya jarang mengendalikan keinginan-keinginan hati saya yang serampangan.';
        $soal[142]='Saya menyukai hal-hal yang menyenangkan.';
        $soal[143]='Saya senang mengerjakan teka-teki pengasah otak.';
        $soal[144]='Saya mempunyai penilaian yang sangat tinggi terhadap diri saya sendiri.';
        $soal[145]='Sekali saya memulai sebuah pekerjaan, saya hampir selalu menyelesaikannya.';
        $soal[146]='Saya sering sulit untuk memantapkan pikiran saya sendiri.';
        $soal[147]='Saya tidak menganggap diri saya sendiri \"periang\".';
        $soal[148]='Saya menganggap berpegang teguh pada prinsip sendiri lebih penting daripada tidak mempunyai arah pikiran.';
        $soal[149]='Kebutuhan bersifat kemanusiaan harus selalu diprioritaskan dalam pertimbangan ekonomi.';
        $soal[150]='Saya sering melakukan sesuatu tanpa dipikirkan terlebih dahulu.';
        $soal[151]='Saya selalu mengkhawatirkan terjadi sesuatu yang menyimpang dari apa yang saya harapkan.';
        $soal[152]='Saya merasa mudah tersenyum dan beramah tamah dengan orang, sekalipun belum saya kenal.';
        $soal[153]='Bila saya merasa mulai melamun, saya biasanya menyibukkan diri dengan berkonsentrasi pada beberapa pekerjaan atau aktivitas.';
        $soal[154]='Reaksi pertama saya adalah mempercayai orang.';
        $soal[155]='Saya tampaknya tidak benar-benar berhasil dalam segala sesuatu.';
        $soal[156]='Membuat saya marah adalah sesuatu yang sulit.';
        $soal[157]='Saya lebih suka liburan di pantai yang terkenal daripada di lingkungan hutan yang terasing.';
        $soal[158]='Jenis-jenis musik tertentu membawa kegembiraan yang tanpa akhir pada diri saya.';
        $soal[159]='Seringkali saya mengelabui orang lain untuk melakukan apa yang saya inginkan.';
        $soal[160]='Saya cenderung menyukai hal-hal yang rapi.';
        $soal[161]='Saya memiliki penilaian yang rendah terhadap diri sendiri.';
        $soal[162]='Saya lebih suka bertanggung jawab pada diri sendiri daripada memimpin orang lain.';
        $soal[163]='Saya jarang memperhatikan suasana hati atau perasaan yang muncul karena adanya lingkungan yang berbeda.';
        $soal[164]='Sebagian besar orang yang saya kenal menyukai saya.';
        $soal[165]='Saya sangat patuh pada prinsip-prinsip etika.';
        $soal[166]='Saya merasa nyaman dengan kehadiran atasan/boss atau pejabat lainnya.';
        $soal[167]='Saya biasanya tampak tergesa-gesa.';
        $soal[168]='Seringkali saya mengubah lingkungan rumah untuk mendapatkan sesuatu yang berbeda.';
        $soal[169]='Bila seseorang mengajak bertengkar, saya siap menghadapinya.';
        $soal[170]='Saya berusaha keras untuk mencapai prestasi dengan segala kemampuan saya.';
        $soal[171]='Saya terkadang makan berlebih.';
        $soal[172]='Saya menyukai kegembiraan saat mengendarai kereta luncur.';
        $soal[173]='Saya tidak berminat berspekulasi terhadap situasi dunia maupun keadaan manusia.';
        $soal[174]='Saya merasa tidak lebih baik daripada orang lain, apapun keadaannya.';
        $soal[175]='Bila pekerjaan yang saya hadapi menjadi terlalu sulit, saya cenderung beralih pada pekerjaan lainnya.';
        $soal[176]='Saya bisa menguasai diri sendiri dengan baik di saat krisis.';
        $soal[177]='Saya seorang periang dan penuh semangat.';
        $soal[178]='Saya menganggap diri saya sendiri sebagai seorang yang berwawasan luas dan toleran terhadap gaya hidup orang lain.';
        $soal[179]='Saya yakin semua orang pantas dihormati.';
        $soal[180]='Saya jarang membuat keputusan yang terburu-buru.';
        $soal[181]='Saya memiliki rasa ketakutan yang lebih sedikit dibandingkan orang lain.';
        $soal[182]='Saya memiliki ikatan emosional yang kuat dengan teman-teman.';
        $soal[183]='Saat kecil dulu, saya jarang menyukai permainan peran.';
        $soal[184]='Saya cenderung berasumsi baik pada orang lain.';
        $soal[185]='Saya adalah orang yang memiliki kemampuan.';
        $soal[186]='Terkadang saya merasa tidak enak dan mudah marah.';
        $soal[187]='Pertemuan-pertemuan sosial biasanya membosankan saya.';
        $soal[188]='Terkadang saat saya membaca puisi atau melihat karya seni, saya merasakan luapan getaran dan kegairahan.';
        $soal[189]='Terkadang saya menggertak atau pura-pura menyanjung seseorang supaya ia mau melakukan apa yang saya inginkan.';
        $soal[190]='Saya tidak terlalu peduli terhadap kebersihan.';
        $soal[191]='Terkadang segala sesuatu tampak suram dan tanpa harapan bagi saya.';
        $soal[192]='Dalam suatu pembicaraan, saya cenderung mendominasi pembicaraan.';
        $soal[193]='Saya mudah merasakan apa yang dirasakan orang lain.';
        $soal[194]='Saya menganggap diri saya sebagai orang yang dermawan.';
        $soal[195]='Saya berusaha melaksanakan pekerjaan dengan seksama, hingga tidak perlu mengulangi lagi.';
        $soal[196]='Bila saya telah berkata atau berbuat salah terhadap orang lain, saya tidak berani bertemu dengan mereka lagi.';
        $soal[197]='Saya selalu sibuk.';
        $soal[198]='Bila berlibur, saya lebih suka kembali ke tempat yang pernah saya kunjungi.';
        $soal[199]='Saya seorang yang keras kepala.';
        $soal[200]='Saya selalu berusaha yang terbaik dalam mengerjakan segala hal.';
        $soal[201]='Terkadang saya mengerjakan sesuatu tanpa berpikir panjang, yang kemudian saya sesali kemudian.';
        $soal[202]='Saya terpikat pada warna-warna cerah dan gaya yang mengagumkan.';
        $soal[203]='Saya memiliki rasa keingintahuan intelektual yang besar.';
        $soal[204]='Saya lebih suka memuji orang daripada dipuji orang lain.';
        $soal[205]='Terdapat banyak pekerjaan sepele yang seharusnya saya kerjakan, namun terkadang saya abaikan begitu saja.';
        $soal[206]='Bila segalanya menjadi kacau, saya masih bisa membuat keputusan yang baik.';
        $soal[207]='Saya jarang menggunakan kata-kata seperti \"fantastis\" atau \"sensasional\" untuk menceritakan pengalaman saya.';
        $soal[208]='Saya pikir bila seseorang tidak mengetahui apa yang mereka percayai, ketika mereka berusia 25 tahun pasti ada yang salah pada diri mereka.';
        $soal[209]='Saya bersimpati kepada orang-orang yang kurang beruntung dibandingkan diri saya.';
        $soal[210]='Sebelum melakukan perjalanan, saya sudah merencanakannya dengan teliti jauh hari sebelumnya.';
        $soal[211]='Pikiran yang menakutkan terkadang muncul di benak saya.';
        $soal[212]='Saya menjalin hubungan baik dengan orang-orang yang bekerjasama dengan saya.';
        $soal[213]='Saya terbiasa dengan cara berpikir yang mapan dan konvensional.';
        $soal[214]='Saya memiliki kepercayaan yang besar pada kodrat manusia.';
        $soal[215]='Saya efisien dan efektif dalam bekerja.';
        $soal[216]='Kejengkelan kecil sekalipun dapat membuat saya frustasi.';
        $soal[217]='Saya menikmati pesta atau perayaan bersama orang banyak.';
        $soal[218]='Saya senang membaca puisi yang menekankan perasaan dan imajinasi daripada sekedar alur cerita.';
        $soal[219]='Saya bangga pada kelihaian saya mengatur orang lain.';
        $soal[220]='Saya membutuhkan waktu lama untuk menemukan barang yang salah tempat.';
        $soal[221]='Bila terjadi hal-hal yang tidak beres atau semrawut, saya sering kehilangan semangat dan putus asa.';
        $soal[222]='Sulit bagi saya untuk mengatur dan mengendalikan keadaan.';
        $soal[223]='Benda-benda yang aneh atau asing seperti nama pemandangan atau tempat dapat membangkitkan keinginan dan suasana hati yang kuat bagi saya.';
        $soal[224]='Bila saya mampu, saya bersedia meluangkan waktu untuk menolong orang lain.';
        $soal[225]='Kalau tidak benar-benar merasa sakit, saya jarang tidak masuk sekolah.';
        $soal[226]='Ketika orang yang saya kenal melakukan hal yang bodoh, saya ikut merasa malu.';
        $soal[227]='Saya orang yang aktif.';
        $soal[228]='Saya mengikuti rute yang sama bila pergi ke beberapa tempat.';
        $soal[229]='Saya sering bertengkar dengan keluarga dan teman-teman saya.';
        $soal[230]='Saya tergolong orang yang \"sangat rajin bekerja\".';
        $soal[231]='Saya selalu bisa mengendalikan perasaan saya.';
        $soal[232]='Saya suka berada dalam kerumunan suatu kegiatan olah raga.';
        $soal[233]='Saya memiliki beberapa minat intelektual.';
        $soal[234]='Saya orang yang superior.';
        $soal[235]='Saya memiliki disiplin yang tinggi.';
        $soal[236]='Saya memiliki stabilitas emosi yang baik.';
        $soal[237]='Saya bisa tertawa dengan mudah.';
        $soal[238]='Saya percaya bahwa moral baru yang serba memperbolehkan bukan lagi merupakan moralitas.';
        $soal[239]='Saya lebih suka dikenal sebagai \"pemurah hati\" daripada \"orang yang adil\".';
        $soal[240]='Saya berpikir dua kali sebelum saya dapat menjawab sebuah pertanyaan.';

        return $soal;
    }
}
