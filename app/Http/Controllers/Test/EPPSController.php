<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EPPSController extends Controller
{
    public function getData($num){
        $jawaba = self::jawaba();
        $jawabb = self::jawabb();

        $soal = array();
        $soal['jawaba'] = $jawaba;
        $soal['jawabb'] = $jawabb;

        return response()->json([
            'quest' => $soal,
            'num' => $num
        ]);
    }
    public static function index(Request $request, $path, $test, $selection){

        $packet = Packet::where('test_id','=',$test->id)->first();
        $jawaba = self::jawaba();
        $jawabb = self::jawabb();

        return view('test.epps', [
            'path' => $path,
            'test' => $test,
            'selection' => $selection,
            'packet' => $packet,
            'jumlah_soal' => $packet->amount
        ]);
    }

    public static function store(Request $request)
    {
     $test_id = $request->test_id;
        $cek_test = existTest($test_id);
        if($cek_test == false && Auth::user()->role->is_global != 1){
            abort(404);
        }

        else{
            $gender = Auth::user()->attribute->gender;
            $koreksi_jawaban = self::koreksi($request->jawaban, $gender);

            $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();

            $result = new Result;
            $result->user_id = Auth::user()->id;
            $result->company_id = Auth::user()->attribute->company_id;
            $result->test_id = $request->test_id;
            $result->packet_id = $request->packet_id;
            $result->result = json_encode($koreksi_jawaban);
            $result->save();

            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);
        }
    }
    public static function jawaba(){
        $jawaba[0]='Saya suka menolong teman-teman, apabila mereka sedang dalam kesulitan.';

        $jawaba[1]='Saya ingin mengetahui pandangan orang-orang besar, mengenai masalah atau topik yang menarik perhatian saya.';
    
        $jawaba[2]='Saya menginginkan setiap pekerjaan tertulis, saya teliti, rapi dan tersusun dengan baik.';
    
        $jawaba[3]='Saya suka menceritakan cerita atau dongeng yang lucu sewaktu berkumpul dengan teman-teman.';
    
        $jawaba[4]='Saya ingin berbuat sekehendak hati saya.';
    
        $jawaba[5]='Saya ingin dapat memecahkan teka-teki atau persoalan yang sukar bagi orang lain.';
    
        $jawaba[6]='Saya ingin mengalami pembaharuan atau perubahan dalam kehidupan saya sehari-hari.';
    
        $jawaba[7]='Saya suka merencanakan dan mengatur detail-detail dari pekerjaan yang harus dilakukan.';
    
        $jawaba[8]='Saya ingin orang lain memperhatikan dan memberikan komentar mengenai penampilan.';
    
        $jawaba[9]='Saya suka menghindari situasi yang mengharapkan saya untuk melakukan hal-hal yang biasa dilakukan orang.';
    
        $jawaba[10]='Saya ingin menjadi orang ahli yang diakui dalam suatu pekerjaan, jabatan atau bidang yang khusus.';
    
        $jawaba[11]='Saya ingin mengetahui pandangan para ahli mengenai berbagai masalah yang menarik perhatian saya.';
    
        $jawaba[12]='Saya suka menyelesaikan pekerjaan atau tugas yang telah saya mulai.';
    
        $jawaba[13]='Saya suka bercerita kepada orang lain mengenai petualangan dan hal-hal yang pernah saya alami.';
    
        $jawaba[14]='Saya ingin tidak tergantung dari orang lain dalam menentukan sesuatu yang akan saya lakukan.';
    
        $jawaba[15]='Saya ingin bisa mengerjakan sesuatu lebih baik dari orang lain.';
    
        $jawaba[16]='Saya suka melakukan kebiasaan dan menghindari sesuatu yang mungkin dianggap tidak wajar oleh orang-orang yang saya hormati.';
    
        $jawaba[17]='Saya ingin agar hidup saya teratur, sehingga berjalan lancar tanpa adanya banyak perubahan dalam rencana saya.';
    
        $jawaba[18]='Saya suka membaca buku atau melihat film, terutama yang bertema sensual.';
    
        $jawaba[19]='Saya suka mengecam orang yang kedudukannya memegang kekuasaan.';
    
        $jawaba[20]='Saya suka mengerjakan tugas yang dianggap orang lain sebagai tugas yang membutuhkan ketrampilan dan usaha.';
    
        $jawaba[21]='Saya suka memuji orang yang saya kagumi.';
    
        $jawaba[22]='Saya suka menyimpan surat, bon atau kertas-kertas lain dengan tersusun rapi dan sesuai cara tertentu.';
    
        $jawaba[23]='Saya suka mengajukan pertanyaan yang menurut saya orang lain tidak akan dapat menjawab.';
    
        $jawaba[24]='Saya menjadi sedemikian marah, sehingga rasanya ingin melempar dan merusak barang-barang.';
    
        $jawaba[25]='Saya ingin berhasil dalam segala hal yang saya lakukan.';
    
        $jawaba[26]='Saya suka mengikuti petunjuk dan melakukan apa yang diharapkan orang lain kepada saya.';
    
        $jawaba[27]='Saya menginginkan setiap pekerjaan tertulis, saya teliti, rapi dan tersusun dengan baik.';
    
        $jawaba[28]='Saya suka menceritakan cerita atau dongeng yang lucu sewaktu berkumpul dengan teman-teman.';
    
        $jawaba[29]='Saya ingin berbuat sekehendak hati saya.';
    
        $jawaba[30]='Saya ingin dapat memecahkan teka-teki atau persoalan yang sulit bagi orang lain.';
    
        $jawaba[31]='Saya suka menerima pimpinan dari orang yang saya kagumi.';
    
        $jawaba[32]='Saya suka makan yang teratur dan ada waktu tertentu untuk makan.';
    
        $jawaba[33]='Saya suka mengatakan sesuatu yang dianggap lucu dan cerdas oleh orang lain.';
    
        $jawaba[34]='Saya ingin merasa bebas untuk melakukan sesuatu yang saya kehendaki.';
    
        $jawaba[35]='Saya suka mengerjakan tugas yang dianggap orang lain sebagai tugas yang membutuhkan ketrampilan dan usaha.';
    
        $jawaba[36]='Dalam merencanakan sesuatu, saya ingin mendapat saran-saran dari orang-orang yang pendapatnya saya hormati.';
    
        $jawaba[37]='Saya ingin agar hidup saya teratur, sehingga berjalan lancar tanpa adanya banyak perubahan dalam rencana saya.';
    
        $jawaba[38]='Saya suka menjadi pusat perhatian apabila saya berada dalam suatu kelompok.';
    
        $jawaba[39]='Saya suka menghindari situasi yang mengharapkan saya untuk melakukan hal-hal yang biasa dilakukan orang.';
    
        $jawaba[40]='Saya ingin menulis novel atau cerita yang hebat.';
    
        $jawaba[41]='Apabila saya dalam suatu kelompok, saya suka menerima pimpinan orang lain dalam memutuskan apa yang akan dilakukan oleh kelompok itu.';
    
        $jawaba[42]='Saya suka menyimpan surat, bon atau kertas-kertas lain dengan tersusun rapi dan sesuai cara tertentu.';
    
        $jawaba[43]='Saya suka mengajukan pertanyaan yang menurut saya orang lain tidak akan dapat menjawab.';
    
        $jawaba[44]='Saya suka mengelak dari tanggung-jawab dan kewajiban.';
    
        $jawaba[45]='Saya ingin diakui dan menjadi ahli dalam suatu pekerjaan, jabatan atau bidang khusus tertentu.';
    
        $jawaba[46]='Saya suka membaca biografi orang besar.';
    
        $jawaba[47]='Saya suka merencanakan dan mengatur secara detail dari tiap pekerjaan yang harus saya lakukan.';
    
        $jawaba[48]='Saya suka menggunakan kata-kata yang maknanya susah diketahui oleh orang lain.';
    
        $jawaba[49]='Saya suka mengecam orang yang kedudukannya memegang kekuasaan.';
    
        $jawaba[50]='Saya suka melakukan pekerjaan apa saja dengan sebaik mungkin.';
    
        $jawaba[51]='Saya ingin mengetahui pandangan orang-orang besar mengenai suatu masalah yang menarik perhatian saya.';
    
        $jawaba[52]='Saya suka membuat perencanaan sebelum memulai pekerjaan yang sulit.';
    
        $jawaba[53]='Saya suka bercerita kepada orang lain mengenai petualangan dan hal-hal yang pernah saya alami.';
    
        $jawaba[54]='Saya suka menyatakan pendapatnya saya tentang berbagai hal.';
    
        $jawaba[55]='Saya ingin mengerjakan segala sesuatu lebih baik daripada orang lain.';
    
        $jawaba[56]='Saya suka melakukan kebiasaan dan menghindari sesuatu yang mungkin dianggap tidak wajar oleh orang-orang yang saya hormati.';
    
        $jawaba[57]='Sebelum melakukan suatu pekerjaan, saya ingin pekerjaan itu teratur dan terencana.';
    
        $jawaba[58]='Saya ingin orang lain memperhatikan dan memberi komentar mengenai penampilan saya di depan umum.';
    
        $jawaba[59]='Saya ingin tidak tergantung dari orang lain dalam menentukan segala sesuatu yang akan saya kerjakan.';
    
        $jawaba[60]='Saya ingin dapat mengatakan bahwa saya telah melakukan suatu pekerjaan yang sulit dengan baik.';
    
        $jawaba[61]='Saya akan mengatakan kepada atasan bahwa mereka telah melakukan suatu pekerjaan dengan baik, apabila memang keadaannya demikian.';
    
        $jawaba[62]='Jika saya akan bepergian, saya senang apabila segala sesuatu telah dipersiapkan sebelumnya.';
    
        $jawaba[63]='Saya terkadang suka melakukan hal-hal hanya untuk melihat bagaimana pengaruhnya terhadap orang lain.';
    
        $jawaba[64]='Saya suka melakukan hal-hal yang dianggap orang lain tidak sesuai dengan kebiasaan.';
    
        $jawaba[65]='Saya ingin menyelesaikan sesuatu yang sangat penting artinya.';
    
        $jawaba[66]='Saya suka memuji orang yang saya kagumi.';
    
        $jawaba[67]='Saya ingin barang-barang saya tersusun rapi di atas meja atau dalam ruang kerja saya.';
    
        $jawaba[68]='Saya suka berbicara tentang hal-hal yang telah saya capai.';
    
        $jawaba[69]='Saya suka melakukan hal-hal dengan cara saya sendiri tanpa menghiraukan apa yang mungkin dipikirkan orang lain.';
    
        $jawaba[70]='Saya ingin menulis novel atau cerita yang hebat.';
    
        $jawaba[71]='Apabila saya dalam suatu kelompok, saya suka menerima pimpinan orang lain dalam memutuskan apa yang akan dilakukan oleh kelompok itu.';
    
        $jawaba[72]='Saya ingin agar hidup saya teratur, sehingga berjalan lancar tanpa adanya banyak perubahan dalam rencana saya.';
    
        $jawaba[73]='Saya suka mengajukan pertanyaan yang menurut saya orang lain tidak akan dapat menjawab.';
    
        $jawaba[74]='Saya suka menghidar dari tanggung-jawab atau kewajiban.';
    
        $jawaba[75]='Saya ingin loyal terhadap teman-teman saya.';
    
        $jawaba[76]='Saya suka mengamati bagaimana perasaan orang lain dalam suatu keadaan tertentu.';
    
        $jawaba[77]='Saya ingin teman-teman saya memberi dorongan apabila saya menghadapi kegagalan.';
    
        $jawaba[78]='Saya ingin menjadi salah seorang pimpinan dalam organisasi atau kelompok dimana saya menjadi anggota kelompok atau organisasi itu.';
    
        $jawaba[79]='Bila keadaan kurang menguntungkan, maka saya merasa harus lebih disalahkan daripada orang lain.';
    
        $jawaba[80]='Saya suka mengerjakan sesuatu untuk teman-teman saya.';
    
        $jawaba[81]='Saya suka menempatkan diri saya ditempat orang lain dan membayangkan perasaan saya apabila saya dalam keadaan atau situasi yang sama seperti orang tersebut.';
    
        $jawaba[82]='Saya ingin agar teman-teman saya menunjukkan simpati dan pengertian apabila saya mengalami kesulitan.';
    
        $jawaba[83]='Apabila bekerja dalam suatu panitia, saya ingin ditunjuk sebagai ketua.';
    
        $jawaba[84]='Bila saya melakukan sesuatu hal yang salah, saya merasa bahwa untuk itu saya harus dihukum.';
    
        $jawaba[85]='Saya suka melakukan sesuatu atau menjalani segala sesuatu bersama teman-teman.';
    
        $jawaba[86]='Saya ingin memahami bagaimana perasaan teman-teman dalam menghadapi berbagai masalah.';
    
        $jawaba[87]='Saya ingin diperlakukan dengan ramah oleh teman-teman.';
    
        $jawaba[88]='Saya ingin dianggap sebagai pemimpin oleh orang lain.';
    
        $jawaba[89]='Saya merasa bahwa pilu hati, kesedihan dan kesusahan yang saya alami, lebih banyak membawa kebaikan daripada kerugian.';
    
        $jawaba[90]='Saya ingin memiliki ikatan perasaan yang kuat dengan teman-teman saya.';
    
        $jawaba[91]='Saya suka merenungkan kepribadian teman-teman saya dan mencoba mengerti sebab dari kepribadian seperti itu.';
    
        $jawaba[92]='Saya ingin agar teman-teman saya meributkan saya apabila saya tertimpa cedera atau sakit.';
    
        $jawaba[93]='Saya suka mengatakan kepada orang lain bagaimana seharusnya mereka melakukan pekerjaannya.';
    
        $jawaba[94]='Saya merasa canggung ditengah-tengah orang lain yang saya anggap sebagai atasan saya.';
    
        $jawaba[95]='Saya lebih suka mengerjakan sesuatu bersama-sama dengan teman saya daripada sendiri.';
    
        $jawaba[96]='Saya suka mempelajari dan menganalisis tingkah laku orang lain.';
    
        $jawaba[97]='Saya ingin teman-teman saya merasa kasihan apabila saya sedang sakit.';
    
        $jawaba[98]='Sedapat mungkin saya ingin membimbing dan mengarahkan tindakan atau tingkah laku orang lain.';
    
        $jawaba[99]='Saya merasa bahwa dalam banyak hal, saya sering kalah dibandingkan dengan orang lain.';
    
        $jawaba[100]='Saya ingin berhasil dalam segala hal yang saya lakukan.';
    
        $jawaba[101]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
    
        $jawaba[102]='Saya ingin agar teman-teman ikut membantu bila saya mengalami kesulitan.';
    
        $jawaba[103]='Saya suka memperdebatkan pendirian saya bila diserang oleh orang lain.';
    
        $jawaba[104]='Saya merasa bersalah, apabila saya telah melakukan sesuatu yang ternyata salah/keliru.';
    
        $jawaba[105]='Saya suka melakukan sesuatu atau menjalani segala sesuatu bersama teman-teman.';
        $jawaba[106]='Saya suka menerima pimpinan dari orang yang saya kagumi.';
        $jawaba[107]='Saya ingin teman-teman dengan gembira memberikan pertolongan kecil kepada saya.';
        $jawaba[108]='Bila saya dalam suatu kelompok, saya ingin menentukan apa yang akan kami kerjakan.';
        $jawaba[109]='Saya lebih suka mengalah dan menghindari perkelahian daripada memaksakan kemauan saya.';
        $jawaba[110]='Saya suka membuat teman baru.';
        $jawaba[111]='Saya suka menilai orang lain berdasarkan sebab mengapa mereka melakukan hal tersebut, bukan berdasar dari apa yang sesungguhnya mereka lakukan.';
        $jawaba[112]='Saya ingin agar hidup saya teratur, sehingga berjalan lancar tanpa adanya banyak perubahan dalam rencana saya.';
        $jawaba[113]='Saya suka diminta menyelesaikan pertentangan atau perselisihan.';
        $jawaba[114]='Saya merasa bahwa saya harus mengakui sesuatu hal yang telah saya lakukan dan ternyata salah/keliru.';
        $jawaba[115]='Saya lebih suka mengerjakan sesuatu bersama-sama dengan teman saya daripada sendiri.';
        $jawaba[116]='Saya suka merenungkan kepribadian teman-teman saya dan mencoba mengerti sebab dari kepribadian seperti itu.';
        $jawaba[117]='Saya ingin teman-teman saya bersimpati dan menghibur saya apabila saya bersusah hati.';
        $jawaba[118]='Saya suka mengajukan pertanyaan yang menurut saya orang lain tidak akan dapat menjawab.';
        $jawaba[119]='Saya merasa canggung ditengah-tengah orang lain yang saya anggap sebagai atasan saya.';
        $jawaba[120]='Saya suka bergaul dalam kelompok yang para anggotanya akrab dan bersahabat antara satu dengan lainnya.';
        $jawaba[121]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
        $jawaba[122]='Saya ingin teman-teman saya ikut merasa kasihan apabila saya sedang sakit.';
        $jawaba[123]='Saya ingin dapat mempengaruhi orang lain untuk melakukan apa yang saya kehendaki.';
        $jawaba[124]='Saya suka mengecam orang yang kedudukannya memegang kekuasaan.';
        $jawaba[125]='Saya suka bergaul dalam kelompok yang para anggotanya akrab dan bersahabat antara satu dengan lainnya.';
        $jawaba[126]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
        $jawaba[127]='Saya ingin agar teman-teman ikut membantu bila saya mengalami kesulitan.';
        $jawaba[128]='Saya ingin menjadi salah seorang pemimpin dalam organisasi kelompok yang saya masuki.';
        $jawaba[129]='Saya merasa bahwa pilu hati, kesedihan dan kesusahan yang saya alami, lebih banyak membawa kebaikan daripada kerugian.';
        $jawaba[130]='Saya lebih suka mengerjakan sesuatu bersama-sama dengan teman saya daripada sendiri.';
        $jawaba[131]='Saya suka merenungkan kepribadian teman-teman saya dan mencoba mengerti sebab dari kepribadian seperti itu.';
        $jawaba[132]='Saya ingin agar teman-teman saya menunjukkan simpati dan pengertian apabila saya mengalami kesulitan.';
        $jawaba[133]='Saya suka memperdebatkan pendirian saya bila diserang oleh orang lain.';
        $jawaba[134]='Saya lebih suka mengalah dan menghindari perkelahian daripada memaksakan kemauan saya.';
        $jawaba[135]='Saya suka melakukan sesuatu untuk teman-teman.';
        $jawaba[136]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
        $jawaba[137]='Saya ingin teman-teman dengan gembira memberikan pertolongan kecil kepada saya.';
        $jawaba[138]='Saya ingin dianggap pemimpin oleh orang lain.';
        $jawaba[139]='Bila saya melakukan sesuatu hal yang salah, saya merasa bahwa untuk itu saya harus dihukum.';
        $jawaba[140]='Saya ingin loyal terhadap teman-teman saya.';
        $jawaba[141]='Saya suka meramalkan bagaimana teman-teman akan bertindak dalam berbagai situasi.';
        $jawaba[142]='Saya ingin teman-teman menunjukkan rasa sayangnya terhadap saya.';
        $jawaba[143]='Bila saya berada dalam kelompok, saya ingin menentukan apa yang akan dikerjakan.';
        $jawaba[144]='Saya merasa sedih akan ketidakmampuan saya dalam menghadapi berbagai keadaan.';
        $jawaba[145]='Saya suka berkirim surat atau e-mail pada teman-teman.';
        $jawaba[146]='Saya suka meramalkan bagaimana teman-teman akan bertindak dalam berbagai situasi.';
        $jawaba[147]='Saya ingin agar teman-teman saya meributkan saya apabila saya tertimpa cedera atau sakit.';
        $jawaba[148]='Saya suka menyatakan kepada orang lain bagaimana mereka harus melakukan pekerjaan mereka.';
        $jawaba[149]='Saya merasa bahwa dalam banyak hal, saya sering kalah dibandingkan dengan orang lain.';
        $jawaba[150]='Saya suka menolong teman-teman apabila merekda dalam kesulitan.';
        $jawaba[151]='Saya suka bepergian melihat daerah-daerah pedalaman.';
        $jawaba[152]='Saya suka bekerja keras pada tiap pekerjaan yang saya hadapi.';
        $jawaba[153]='Saya suka bepergian dengan seseorang (lawan jenis) yang menurut saya menarik.';
        $jawaba[154]='Saya suka membaca berita-berita surat kabar atau melihat di TV mengenai pembunuhan dan lainnya yang berkaitan dengan kekerasan.';
        $jawaba[155]='Saya suka memberikan pertolongan atau bantuan kecil kepada teman-teman saya.';
        $jawaba[156]='Saya ingin mengalami pembaharuan atau perubahan dalam kehidupan saya sehari-hari.';
        $jawaba[157]='Saya suka bekerja sampai jauh malam untuk menyelesaikan pekerjaan.';
        $jawaba[158]='Saya suka nafsu saya terangsang.';
        $jawaba[159]='Saya rasanya ingin membalas dam bila ada orang menghina saya.';
        $jawaba[160]='Saya suka bermurah hati pada teman-teman saya.';
        $jawaba[161]='Saya suka bertemu dengan orang-orang baru.';
        $jawaba[162]='Saya suka menyelesaikan pekerjaan atau tugas yang telah saya mulai.';
        $jawaba[163]='Saya ingin dianggap mempunyai daya tarik fisik oleh orang-orang dari lawan jenis saya.';
        $jawaba[164]='Saya suka mengatakan kepada orang lain bagaimana pendapat saya mengenai mereka.';
        $jawaba[165]='Saya suka berlaku sangat ramah terhadap teman-teman saya.';
        $jawaba[166]='Saya lebih suka mencoba pekerjaan yang baru daripada terus menerus melakukan pekerjaan yang sama.';
        $jawaba[167]='Saya suka bertahan menghadapi masalah, sekalipun nampaknya saya tidak akan berhasil.';
        $jawaba[168]='Saya suka membaca buku atau melihat film, terutama yang bertema sensual.';
        $jawaba[169]='Saya rasanya ingin menyalahkan orang lain bila keadaannya kurang menguntungkan saya.';
        $jawaba[170]='Saya suka menunjukkan simpati saya kepada teman-teman bila mereka mendapat cedera atau sakit.';
        $jawaba[171]='Saya suka makan di restoran atau warung makan yang baru atau asing bagi saya.';
        $jawaba[172]='Saya ingin menyelesaikan pekerjaan satu persatu sebelum memulai pekerjaan lainnya.';
        $jawaba[173]='Saya suka untuk berdiskusi tentang seks dan aktifitas seksualitas.';
        $jawaba[174]='Saya menjadi sedemikian marah, sehingga rasanya ingin melempar dan merusak barang-barang.';
        $jawaba[175]='Saya suka menolong teman-teman, apabila mereka sedang dalam kesulitan.';
        $jawaba[176]='Saya suka mengerjakan sesuatu yang baru dan berbeda-beda.';
        $jawaba[177]='Bila saya harus melakukan suatu tugas, saya ingin mengerjakannya sampai selesai.';
        $jawaba[178]='Saya suka bepergian dengan lawan jenis yang menarik.';
        $jawaba[179]='Saya suka menyerang pendirian yang bertentangan dengan pendirian saya.';
        $jawaba[180]='Saya suka bermurah hati dengan teman-teman saya.';
        $jawaba[181]='Saya suka makan di restoran atau warung makan yang baru atau asing bagi saya.';
        $jawaba[182]='Saya suka bekerja sampai jauh malam untuk menyelesaikan pekerjaan.';
        $jawaba[183]='Saya suka nafsu saya terangsang.';
        $jawaba[184]='Saya rasanya ingin mengolok-olok orang yang melakukan hal-hal yang saya anggap bodoh.';
        $jawaba[185]='Saya suka memaafkan teman-teman saya yang menyakiti saya.';
        $jawaba[186]='Saya suka bereksperimen atau mencoba hal-hal baru.';
        $jawaba[187]='Saya suka mengerjakan teka-teki atau memecahkan persoalan sampai selesai.';
        $jawaba[188]='Saya ingin dianggap mempunyai daya tarik fisik oleh orang-orang dari lawan jenis saya.';
        $jawaba[189]='Saya merasa ingin mengecam seseorang dimuka umum bila orang tersebut memang layak menerimanya.';
        $jawaba[190]='Saya suka berlaku sangat ramah terhadap teman-teman saya.';
        $jawaba[191]='Saya suka mencoba pekerjaan baru dan berbeda, daripada melakukan suatu pekerjaan yang sama.';
        $jawaba[192]='Saya suka menyelesaikan pekerjaan atau tugas yang telah saya mulai.';
        $jawaba[193]='Saya suka untuk berdiskusi tentang seks dan aktifitas seksualitas.';
        $jawaba[194]='Saya menjadi sedemikian marah, sehingga rasanya ingin melempar dan merusak barang-barang.';
        $jawaba[195]='Saya suka berlaku sangat ramah terhadap teman-teman saya.';
        $jawaba[196]='Saya suka berkeliling di pedalaman dan tinggal di berbagai tempat.';
        $jawaba[197]='Saya suka bertahan menghadapi masalah, sekalipun nampaknya saya tidak akan berhasil.';
        $jawaba[198]='Saya suka membaca buku atau melihat film, terutama yang bertema sensual.';
        $jawaba[199]='Saya rasanya ingin menyalahkan orang lain bila keadaannya kurang menguntungkan saya.';
        $jawaba[200]='Saya suka melakukan pekerjaan sebaik mungkin.';
        $jawaba[201]='Saya suka mengerjakan sesuatu yang baru dan berbeda-beda.';
        $jawaba[202]='Bila saya harus melakukan suatu tugas, saya ingin mengerjakannya sampai selesai.';
        $jawaba[203]='Saya suka ikut aktivitas sosial bersama-sama dengan orang yang berlawanan jenis dengan saya.';
        $jawaba[204]='Saya akan menyerang pendirian yang bertentangan dengan pendirian saya.';
        $jawaba[205]='Saya suka memperlakukan orang lain dengan ramah dan simpatik.';
        $jawaba[206]='Saya suka melakukan kebiasaan dan menghindari sesuatu yang mungkin dianggap tidak wajar oleh orang-orang yang saya hormati.';
        $jawaba[207]='Saya suka bekerja keras pada tiap pekerjaan yang saya hadapi.';
        $jawaba[208]='Saya mempunyai keinginan untuk mencium orang yang menurut saya menarik, dari lawan jenis saya.';
        $jawaba[209]='Saya rasanya ingin menghardik seseorang bila pendapatnya berbeda dengannya.';
        $jawaba[210]='Saya suka menolong orang lain yang saya anggap tidak begitu beruntung bila dibandingkan dengan saya.';
        $jawaba[211]='Saya suka berkeliling di pedalaman dan tinggal di berbagai tempat.';
        $jawaba[212]='Jika saya akan bepergian, saya senang apabila segala sesuatu telah dipersiapkan sebelumnya.';
        $jawaba[213]='Saya mudah jatuh cinta dengan lawan jenis saya.';
        $jawaba[214]='Saya suka mengatakan kepada orang lain bagaimana pendapat saya mengenai mereka.';
        $jawaba[215]='Saya suka memberikan pertolongan atau bantuan kecil kepada teman-teman saya.';
        $jawaba[216]='Saya suka bertemu dengan orang-orang baru.';
        $jawaba[217]='Saya suka mengerjakan teka-teki atau memecahkan persoalan sampai selesai.';
        $jawaba[218]='Saya suka berbicara tentang hal-hal yang telah saya capai.';
        $jawaba[219]='Saya rasanya ingin mengolok-olok orang yang melakukan hal-hal yang saya anggap bodoh.';
        $jawaba[220]='Saya ingin teman-teman saya mempercayai dan menceritakan kepada saya kesulitan-kesulitan mereka.';
        $jawaba[221]='Saya suka mengikuti mode atau trend baru.';
        $jawaba[222]='Saya ingin menghindari gangguan bila sedang bekerja.';
        $jawaba[223]='Saya suka mengarkan atau bercerita tentang lelucon yang berkisar tentang seks.';
        $jawaba[224]='Saya suka mengelak dari tanggung-jawab dan kewajiban.';

        return $jawaba;
    }

    public static function jawabb(){
        $jawabb[0]='Saya ingin melakukan pekerjaan apa saja dengan sebaik mungkin.';
        $jawabb[1]='Saya ingin menyelesaikan sesuatu yang sangat penting dan berarti bagi saya.';
        $jawabb[2]='Saya ingin menjadi ahli yang diakui dalam suatu pekerjaan, jabatan atau bidang yang khusus.';
        $jawabb[3]='Saya ingin menulis novel atau cerita yang hebat.';
        $jawabb[4]='Saya ingin mengatakan bahwa saya telah melakukan dengan baik suatu pekerjaan yang sulit.';
        $jawabb[5]='Saya suka mengikuti petunjuk dan melakukan apa yang diharapkan orang dari diri saya. ';
        $jawabb[6]='Saya akan mengatakan kepada atasan bahwa mereka telah melakukan suatu pekerjaan dengan baik, apabila memang keadaannya demikian.';
        $jawabb[7]='Saya suka mengikuti petunjuk dan melakukan apa yang diharapkan orang lain kepada saya.';
        $jawabb[8]='Saya suka membaca riwayat hidup orang-orang terkenal.';
        $jawabb[9]='Saya suka membaca riwayat hidup orang-orang terkenal.';
        $jawabb[10]='Sebelum memulai suatu pekerjaan, saya ingin pekerjaan itu teratur dan terencana.';
        $jawabb[11]='Jika saya akan bepergian, saya senang apabila sebelumnya telah direncanakan.';
        $jawabb[12]='Saya ingin barang-barang saya tersusun rapi dan teratur di atas meja atau di ruang kerja saya.';
        $jawabb[13]='Saya suka makan yang teratur dan ada waktu tertentu untuk makan.';
        $jawabb[14]='Saya ingin barang-barang saya tersusun rapi dan teratur di atas meja atau di ruang kerja saya.';
        $jawabb[15]='Saya suka menceritakan cerita atau dongeng yang lucu sewaktu berkumpul dengan teman-teman.';
        $jawabb[16]='Saya suka mengatakan tentang hal-hal yang telah saya capai.';
        $jawabb[17]='Saya suka bercerita kepada orang lain mengenai petualangan dan hal-hal yang pernah saya alami.';
        $jawabb[18]='Saya suka menjadi pusat perhatian ketika berada dalam suatu kelompok.';
        $jawabb[19]='Saya suka menggunakan kata-kata yang arti atau maknanya sulit diketahui oleh orang lain.';
        $jawabb[20]='Saya ingin berbuat sekehendak hati saya.';
        $jawabb[21]='Saya ingin merasa bebas untuk melakukan hal-hal yang saya kehendaki.';
        $jawabb[22]='Saya ingin tidak tergantung dari orang lain dalam menentukan sesuatu yang akan saya lakukan.';
        $jawabb[23]='Suka suka mengecam orang yang kedudukannya memegang kekuasaan.';
        $jawabb[24]='Saya suka mengelak dari tanggung-jawab dan kewajiban.';
        $jawabb[25]='Saya suka mendapatkan teman-teman baru.';
        $jawabb[26]='Saya ingin memiliki ikatan perasaan yang kuat dengan teman-teman saya.';
        $jawabb[27]='Saya ingin mendapatkan teman sebanyak mungkin.';
        $jawabb[28]='Saya suka berkirim surat atau e-mail pada teman-teman.';
        $jawabb[29]='Saya suka melakukan atau mengerjakan sesuatu bersama dengan teman-teman.';
        $jawabb[30]='Saya suka menilai orang lain berdasarkan sebab mengapa mereka melakukan hal tersebut, bukan berdasar dari apa yang sesungguhnya mereka lakukan.';
        $jawabb[31]='Saya ingin memahami bagaimana perasaan teman-teman dalam menghadapi berbagai masalah.';
        $jawabb[32]='Saya suka mempelajari dan menganalisis tingkah laku orang lain.';
        $jawabb[33]='Saya suka menempatkan diri saya ditempat orang lain dan membayangkan perasaan saya apabila saya dalam keadaan atau situasi yang sama seperti orang tersebut.';
        $jawabb[34]='Saya suka mengamati bagaimana perasaan orang lain dalam satu keadaan tertentu.';
        $jawabb[35]='Saya ingin teman-teman saya memberi dorongan apabila saya menghadapi kegagalan.';
        $jawabb[36]='Saya ingin diperlakukan dengan ramah oleh teman-teman saya.';
        $jawabb[37]='Saya ingin teman-teman saya ikut merasa kasihan apabila saya sedang sakit.';
        $jawabb[38]='Saya ingin agar teman-teman saya meributkan saya apabila saya tertimpa cedera atau sakit.';
        $jawabb[39]='Saya ingin teman-teman saya bersimpati dan menghibur saya apabila saya bersusah hati.';
        $jawabb[40]='Apabila bekerja dalam suatu panitia, saya ingin ditunjuk sebagai ketua.';
        $jawabb[41]='Sedapat mungkin saya ingin membimbing dan mengarahkan tindakan atau tingkah laku orang lain.';
        $jawabb[42]='Saya ingin menjadi salah seorang pimpinan dalam organisasi atau kelompok dimana saya menjadi anggota kelompok atau organisasi itu.';
        $jawabb[43]='Saya suka mengatakan kepada orang lain bagaimana seharusnya mereka melakukan pekerjaannya.';
        $jawabb[44]='Saya suka diminta menyelesaikan pertentangan atau perselisihan.';
        $jawabb[45]='Saya merasa bersalah, apabila saya telah melakukan sesuatu yang ternyata salah/keliru.';
        $jawabb[46]='Saya merasa bahwa saya harus mengakui sesuatu hal yang telah saya lakukan dan ternyata salah/keliru.';
        $jawabb[47]='Bila keadaan kurang menguntungkan, maka saya merasa harus lebih disalahkan daripada orang lain.';
        $jawabb[48]='Saya merasa bahwa dalam banyak hal, saya sering kalah dibandingkan dengan orang lain.';
        $jawabb[49]='Saya merasa canggung ditengah-tengah orang lain yang saya anggap sebagai atasan saya.';
        $jawabb[50]='Saya suka menolong orang lain yang saya anggap tidak begitu beruntung bila dibandingkan dengan saya.';
        $jawabb[51]='Saya suka bermurah hati dengan teman-teman saya.';
        $jawabb[52]='Saya suka memberikan pertolongan atau bantuan kecil kepada teman-teman saya.';
        $jawabb[53]='Saya ingin teman-teman saya mempercayai dan menceritakan kepada saya kesulitan-kesulitan mereka.';
        $jawabb[54]='Saya suka memaafkan teman-teman saya yang menyakiti saya.';
        $jawabb[55]='Saya suka makan di restoran atau warung makan yang baru atau asing bagi saya.';
        $jawabb[56]='Saya suka mengikuti mode atau trendend baru.';
        $jawabb[57]='Saya suka bepergian melihat daerah-daerah pedalaman.';
        $jawabb[58]='Saya suka berkeliling di pedalaman dan tinggal di berbagai tempat.';
        $jawabb[59]='Saya suka mengerjakan sesuatu yang baru dan berbeda-beda.';
        $jawabb[60]='Saya suka bekerja keras pada tiap pekerjaan yang saya hadapi.';
        $jawabb[61]='Saya ingin menyelesaikan pekerjaan satu persatu sebelum memulai pekerjaan lainnya.';
        $jawabb[62]='Saya suka mengerjakan teka-teki atau memecahkan persoalan sampai selesai.';
        $jawabb[63]='Saya suka bertahan menghadapi masalah, sekalipun nampaknya saya tidak akan berhasil.';
        $jawabb[64]='Saya ingin bekerja berjam-jam dengan tidak diganggu.';
        $jawabb[65]='Saya mempunyai keinginan untuk mencium orang yang menurut saya menarik, dari lawan jenis saya.';
        $jawabb[66]='Saya ingin dianggap mempunyai daya tarik fisik oleh orang-orang dari lawan jenis saya.';
        $jawabb[67]='Saya mudah jatuh cinta dengan lawan jenis saya.';
        $jawabb[68]='Saya suka mendengarkan atau bercerita tentang lelucon yang berkisar tentang seks.';
        $jawabb[69]='Saya suka membaca buku atau melihat film yang bertemakan sensualitas.';
        $jawabb[70]='saya suka membantah pikiran yang bertentangan dengan pikiran saya.';
        $jawabb[71]='Saya merasa ingin mengecam seseorang dimuka umum bila orang tersebut memang layak menerimanya.';
        $jawabb[72]='Saya menjadi sedemikian marah, sehingga rasanya ingin melempar dan merusak barang-barang.';
        $jawabb[73]='Saya suka mengatakan kepada orang lain bagaimana pendapat saya mengenai mereka.';
        $jawabb[74]='Saya rasanya ingin mengolok-olok orang yang melakukan hal-hal yang saya anggap bodoh.';
        $jawabb[75]='Saya ingin melakukan pekerjaan apa saja sebaik mungkin.';
        $jawabb[76]='Saya ingin dapat mengatakan bahwa saya telah melakukan suatu pekerjaan yang sulit dengan baik.';
        $jawabb[77]='Saya ingin berhasil terhadap apa yang saya lakukan.';
        $jawabb[78]='Saya ingin mengerjakan segala sesuatu dengan lebih baik daripada orang lain.';
        $jawabb[79]='Saya ingin dapat memecahkan teka-teki atau persoalan yang sukar bagi orang lain.';
        $jawabb[80]='Dalam merencanakan sesuatu, saya ingin mendapat saran dari orang yang pendapatnya saya hormati.';
        $jawabb[81]='Saya akan mengatakan kepada atasan bahwa mereka telah melakukan suatu pekerjaan dengan baik, apabila memang keadaannya demikian.';
        $jawabb[82]='Saya suka menerima pimpinan dari orang yang saya kagumi.';
        $jawabb[83]='Apabila saya dalam suatu kelompok, saya suka menerima pimpinan orang lain dalam memutuskan apa yang akan dilakukan oleh kelompok itu.';
        $jawabb[84]='Saya suka melakukan kebiasaan dan menghindari sesuatu yang mungkin dianggap tidak wajar oleh orang-orang yang saya hormati.';
        $jawabb[85]='Saya suka membuat rencana sebelum memulai pekerjaan yang sulit.';
        $jawabb[86]='Jika saya akan bepergian, saya senang apabila sebelumnya telah direncanakan.';
        $jawabb[87]='Sebelum memulai suatu pekerjaan, saya ingin pekerjaan itu teratur dan terencana.';
        $jawabb[88]='Saya suka menyimpan surat, bon atau kertas-kertas lain dengan tersusun rapi dan sesuai cara tertentu.';
        $jawabb[89]='Saya ingin agar hidup saya teratur, sehingga berjalan lancar tanpa adanya banyak perubahan dalam rencana saya.';
        $jawabb[90]='Saya suka mengatakan sesuatu yang dianggap lucu dan cerdas oleh orang lain.';
        $jawabb[91]='Saya terkadang suka melakukan hal-hal hanya untuk melihat bagaimana pengaruhnya terhadap orang lain.';
        $jawabb[92]='Saya suka mengatakan tentang hal-hal yang telah saya capai.';
        $jawabb[93]='Saya suka menjadi pusat perhatian ketika berada dalam suatu kelompok.';
        $jawabb[94]='Saya suka menggunakan kata-kata yang arti atau maknanya sulit diketahui oleh orang lain.';
        $jawabb[95]='Saya suka menyatakan pendapat saya tentang berbagai hal.';
        $jawabb[96]='Saya suka melakukan hal-hal yang dianggap orang lain tidak sesuai dengan kebiasaan.';
        $jawabb[97]='Saya suka menghindari situasi yang mengharapkan saya untuk melakukan hal-hal yang biasa dilakukan orang.';
        $jawabb[98]='Saya suka melakukan hal-hal dengan cara saya sendiri tanpa menghiraukan apa yang mungkin dipikirkan orang lain.';
        $jawabb[99]='Saya suka mengelak dari tanggung-jawab dan kewajiban.';
        $jawabb[100]='Saya suka menjalin pertemanan baru.';
        $jawabb[101]='Saya ingin melakukan sesuatu untuk teman saya.';
        $jawabb[102]='Saya suka melakukan sesuatu untuk teman-teman saya.';
        $jawabb[103]='Saya suka berkirim surat atau e-mail pada teman-teman.';
        $jawabb[104]='Saya ingin memiliki ikatan perasaan yang kuat dengan teman-teman saya.';
        $jawabb[105]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
        $jawabb[106]='Saya ingin memahami bagaimana perasaan teman-teman dalam menghadapi berbagai masalah.';
        $jawabb[107]='Saya suka menilai orang lain berdasarkan sebab mengapa mereka melakukan hal tersebut, bukan berdasar dari apa yang sesungguhnya mereka lakukan.';
        $jawabb[108]='Saya suka meramalkan bagaimana teman-teman akan bertindak dalam berbagai situasi.';
        $jawabb[109]='Saya suka menganalisis perasaan saya dan sebab mengapa saya melakukan sesuatu.';
        $jawabb[110]='Saya ingin agar teman-teman ikut membantu bila saya mengalami kesulitan.';
        $jawabb[111]='Saya ingin teman-teman menunjukkan rasa sayangnya terhadap saya.';
        $jawabb[112]='Saya ingin teman-teman saya ikut merasa kasihan apabila saya sedang sakit.';
        $jawabb[113]='Saya ingin teman-teman dengan gembira memberikan pertolongan kecil kepada saya.';
        $jawabb[114]='Saya ingin teman-teman saya bersimpati dan menghibur saya apabila saya bersusah hati.';
        $jawabb[115]='Saya suka memperdebatkan pendirian saya bila diserang oleh orang lain.';
        $jawabb[116]='Saya ingin dapat mempengaruhi orang lain untuk melakukan apa yang saya kehendaki.';
        $jawabb[117]='Bila saya berada dalam suatu kelompok, saya ingin menentukan apa yang akan dilakukan kelompok tersebut.';
        $jawabb[118]='Saya suka mengatakan kepada orang lain bagaimana seharusnya mereka melakukan pekerjaannya.';
        $jawabb[119]='Sedapat mungkin saya ingin membimbing dan mengarahkan tindakan atau tingkah laku orang lain.';
        $jawabb[120]='Saya merasa bersalah, apabila saya telah melakukan sesuatu yang ternyata salah/keliru.';
        $jawabb[121]='Saya merasa sedih akan ketidakmampuan saya dalam menghadapi berbagai keadaan.';
        $jawabb[122]='Saya lebih suka mengalah dan menghindari perkelahian daripada memaksakan kemauan saya.';
        $jawabb[123]='Saya merasa sedih akan ketidakmampuan saya dalam menghadapi berbagai keadaan.';
        $jawabb[124]='Saya merasa canggung ditengah-tengah orang lain yang saya anggap sebagai atasan saya.';
        $jawabb[125]='Saya suka menolong teman-teman, apabila mereka sedang dalam kesulitan.';
        $jawabb[126]='Saya suka menunjukkan simpati saya kepada teman-teman bila mereka mendapat cedera atau sakit.';
        $jawabb[127]='Saya suka memperlakukan orang lain dengan ramah dan simpatik.';
        $jawabb[128]='Saya suka menunjukkan simpati saya kepada teman-teman bila mereka mendapat cedera atau sakit.';
        $jawabb[129]='Saya suka bersikap ramah terhadap teman-teman.';
        $jawabb[130]='Saya suka bereksperimen atau mencoba hal-hal baru.';
        $jawabb[131]='Saya suka mencoba pekerjaan baru dan berbeda, daripada melakukan suatu pekerjaan yang sama.';
        $jawabb[132]='Saya suka bertemu dengan orang-orang baru.';
        $jawabb[133]='Saya ingin mengalami pembaharuan atau perubahan dalam kehidupan saya sehari-hari.';
        $jawabb[134]='Saya suka berkeliling di pedalaman dan tinggal di berbagai tempat.';
        $jawabb[135]='Bila saya harus melakukan suatu tugas, saya ingin mengerjakannya sampai selesai.';
        $jawabb[136]='Saya ingin menghindari gangguan bila sedang bekerja.';
        $jawabb[137]='Saya suka bekerja sampai jauh malam untuk menyelesaikan pekerjaan.';
        $jawabb[138]='Saya ingin bekerja berjam-jam tanpa diganggu.';
        $jawabb[139]='Saya suka bertahan menghadapi masalah, sekalipun nampaknya saya tidak akan berhasil.';
        $jawabb[140]='Saya suka bepergian dengan seseorang (lawan jenis) yang menurut saya menarik.';
        $jawabb[141]='Saya suka untuk berdiskusi tentang seks dan aktivitas seksualitas.';
        $jawabb[142]='Saya suka nafsu saya terangsang.';
        $jawabb[143]='Saya suka ikut aktivitas sosial bersama-sama dengan orang yang berlawanan jenis dengan saya.';
        $jawabb[144]='Saya suka membaca buku atau film, terutama yang bertema sensual.';
        $jawabb[145]='Saya suka membaca berita-berita surat kabar atau melihat di TV mengenai pembunuhan dan lainnya yang berkaitan dengan kekerasan.';
        $jawabb[146]='Saya suka menyerang pendirian yang bertentangan dengan pendirian saya.';
        $jawabb[147]='Saya rasanya ingin menyalahkan orang lain bila keadaannya kurang menguntungkan saya.';
        $jawabb[148]='Saya rasanya ingin membalas dam bila seseorang menghina saya.';
        $jawabb[149]='Saya rasanya ingin menghardik seseorang bila pendapatnya berbeda dengannya.';
        $jawabb[150]='Saya melakukan pekerjaan apa saja sebaik mungkin.';
        $jawabb[151]='Saya suka mengerjakan tugas yang dianggap orang lain sebagai tugas yang membutuhkan ketrampilan dan usaha.';
        $jawabb[152]='Saya ingin menyelesaikan sesuatu yang sangat penting artinya.';
        $jawabb[153]='Saya ingin berhasil dalam apa yang akan saya lakukan.';
        $jawabb[154]='Saya ingin menulis novel atau cerita yang hebat.';
        $jawabb[155]='Dalam merencanakan sesuatu, saya ingin mendapatkan saran dari orang yang pendapatnya saya hormati.';
        $jawabb[156]='Saya akan mengatakan kepada atasan bahwa mereka telah melakukan suatu pekerjaan dengan baik, apabila memang keadaannya demikian.';
        $jawabb[157]='Saya suka memuji orang saya kagumi.';
        $jawabb[158]='Saya suka menerima pimpinan dari orang yang saya kagumi.';
        $jawabb[159]='Apabila saya dalam suatu kelompok, saya suka menerima pimpinan orang lain dalam memutuskan apa yang akan dilakukan oleh kelompok itu.';
        $jawabb[160]='Saya suka membuat rencana sebelum memulai pekerjaan yang sulit.';
        $jawabb[161]='Saya menginginkan setiap pekerjaan tertulis, saya teliti, rapi dan tersusun dengan baik.';
        $jawabb[162]='Saya ingin barang-barang saya tersusun rapi dan teratur di atas meja atau di ruang kerja saya.';
        $jawabb[163]='Saya suka merencanakan dan mengatur detail-detail dari pekerjaan yang harus dilakukan.';
        $jawabb[164]='Saya suka makan yang teratur dan ada waktu tertentu untuk makan.';
        $jawabb[165]='Saya suka mengatakan sesuatu yang dianggap lucu dan cerdas oleh orang lain.';
        $jawabb[166]='Saya terkadang suka melakukan hal-hal hanya untuk melihat bagaimana pengaruhnya terhadap orang lain.';
        $jawabb[167]='Saya ingin orang lain memperhatikan dan memberi komentar mengenai penampilan saya di depan umum.';
        $jawabb[168]='Saya suka menjadi pusat perhatian bila saya ada dalam suatu kelompok.';
        $jawabb[169]='Saya suka mengajukan pertanyaan yang menurut saya orang lain tidak akan dapat menjawab.';
        $jawabb[170]='Saya suka mengemukakan pendapat saya tentang berbagai hal.';
        $jawabb[171]='Saya suka melakukan hal-hal yang dianggap orang lain tidak sesuai dengan kebiasaan.';
        $jawabb[172]='Saya ingin merasa bebas untuk melakukan hal-hal yang saya kehendaki.';
        $jawabb[173]='Saya suka melakukan hal-hal dengan cara saya sendiri tanpa menghiraukan apa yang mungkin dipikirkan orang lain.';
        $jawabb[174]='Saya suka mengelak dari tanggung-jawab dan kewajiban.';
        $jawabb[175]='Saya ingin loyal terhadap teman-teman saya.';
        $jawabb[176]='Saya suka membuat teman baru.';
        $jawabb[177]='Saya suka bergaul dalam kelompok yang para anggotanya akrab dan bersahabat antara satu dengan lainnya.';
        $jawabb[178]='Saya ingin membuat teman sebanyak mungkin.';
        $jawabb[179]='Saya suka berkirim surat atau e-mail pada teman-teman.';
        $jawabb[180]='Saya suka mengamati bagaimana perasaan orang lain dalam satu keadaan tertentu.';
        $jawabb[181]='Saya suka menempatkan diri saya di tempat orang lain dan membayangkan perasaan saya apabila saya dalam keadaan atau situasi yang sama seperti orang tersebut.';
        $jawabb[182]='Saya ingin memahami bagaimana perasaan teman-teman dalam menghadapi berbagai masalah.';
        $jawabb[183]='Saya suka mempelajari dan menganalisis tingkah laku orang lain.';
        $jawabb[184]='Saya suka meramalkan bagaimana teman-teman akan bertindak dalam berbagai situasi.';
        $jawabb[185]='Saya ingin teman-teman saya memberi dorongan apabila saya menghadapi kegagalan.';
        $jawabb[186]='Saya ingin agar teman-teman saya menunjukkan simpati dan pengertian apabila saya mengalami kesulitan.';
        $jawabb[187]='Saya ingin diperlakukan dengan ramah oleh teman-teman saya.';
        $jawabb[188]='Saya ingin teman-teman menunjukkan rasa sayangnya terhadap saya.';
        $jawabb[189]='Saya ingin agar teman-teman saya meributkan saya apabila saya tertimpa cedera atau sakit.';
        $jawabb[190]='Saya ingin dianggap sebagai pemimpin oleh orang lain.';
        $jawabb[191]='Apabila bekerja dalam suatu panitia, saya ingin ditunjuk sebagai ketua.';
        $jawabb[192]='Saya ingin dapat mempengaruhi orang lain untuk melakukan apa yang saya kehendaki.';
        $jawabb[193]='Saya suka diminta menyelesaikan pertentangan atau perselisihan.';
        $jawabb[194]='Saya suka mengatakan kepada orang lain bagaimana seharusnya mereka melakukan pekerjaannya.';
        $jawabb[195]='Bila keadaan kurang menguntungkan, maka saya merasa harus lebih disalahkan daripada orang lain.';
        $jawabb[196]='Bila saya melakukan sesuatu hal yang salah, saya merasa bahwa untuk itu saya harus dihukum.';
        $jawabb[197]='Saya merasa bahwa pilu hati, kesedihan dan kesusahan yang saya alami, lebih banyak membawa kebaikan daripada kerugian.';
        $jawabb[198]='Saya merasa bahwa saya harus mengakui sesuatu hal yang telah saya lakukan dan ternyata salah/keliru.';
        $jawabb[199]='Saya merasa bahwa dalam banyak hal, saya sering kalah dibandingkan dengan orang lain.';
        $jawabb[200]='Saya suka menolong orang lain yang saya anggap tidak begitu beruntung bila dibandingkan dengan saya.';
        $jawabb[201]='Saya suka memperlakukan orang lain dengan ramah dan simpatik.';
        $jawabb[202]='Saya suka menolong orang lain yang saya anggap tidak begitu beruntung bila dibandingkan dengan saya.';
        $jawabb[203]='Saya suka memaafkan teman-teman saya yang menyakiti saya.';
        $jawabb[204]='Saya ingin teman-teman saya mempercayai dan menceritakan kepada saya kesulitan-kesulitan mereka.';
        $jawabb[205]='Saya suka bepergian melihat daerah-daerah pedalaman.';
        $jawabb[206]='Saya suka mengikuti mode atau trend baru.';
        $jawabb[207]='Saya ingin mengalami pembaharuan atau perubahan dalam kehidupan saya sehari-hari.';
        $jawabb[208]='Saya suka bereksperimen atau mencoba hal-hal baru.';
        $jawabb[209]='Saya suka mengikuti mode atau trend baru.';
        $jawabb[210]='Saya suka menyelesaikan pekerjaan atau tugas yang telah saya mulai.';
        $jawabb[211]='Saya ingin bekerja berjam-jam dengan tidak diganggu.';
        $jawabb[212]='Saya suka mengerjakan teka-teki atau memecahkan persoalan sampai selesai.';
        $jawabb[213]='Saya ingin menyelesaikan pekerjaan satu persatu sebelum memulai pekerjaan lainnya.';
        $jawabb[214]='Saya ingin menghindar dari gangguan bila saya sedang bekerja.';
        $jawabb[215]='Saya suka ikut aktivitas sosial bersama-sama dengan orang yang berlawanan jenis dengan saya.';
        $jawabb[216]='Saya mempunyai keinginan untuk mencium orang yang menurut saya menarik, dari lawan jenis saya.';
        $jawabb[217]='Saya mudah jatuh cinta dengan lawan jenis saya.';
        $jawabb[218]='Saya suka mendengarkan atau bercerita tentang lelucon yang berkisar tentang seks.';
        $jawabb[219]='Saya suka mendengarkan atau bercerita tentang lelucon yang berkisar tentang seks.';
        $jawabb[220]='Saya suka membaca berita-berita surat kabar atau melihat di TV mengenai pembunuhan dan lainnya yang berkaitan dengan kekerasan.';
        $jawabb[221]='Saya merasa ingin mengecam seseorang dimuka umum bila orang tersebut memang layak menerimanya.';
        $jawabb[222]='Saya rasanya ingin menghardik seseorang bila pendapatnya berbeda dengannya.';
        $jawabb[223]='Saya rasanya ingin membalas dam bila seseorang menghina saya.';
        $jawabb[224]='Saya rasanya ingin mengolok-olok orang yang melakukan hal-hal yang saya anggap bodoh.';
    
        return $jawabb;
    }

    public static function koreksi($jawaban, $gender){
        for ($i=1;$i<=225;$i++){
            $T[$i]=$jawaban[$i];
        }
            $ach=isA($T[1])+isA($T[6])+isA($T[11])+isA($T[16])+isA($T[21])+isA($T[26])+isA($T[31])+isA($T[36])+isA($T[41])+isA($T[46])+isA($T[51])+isA($T[56])+isA($T[61])+isA($T[66])+isA($T[71]);
            $def=isA($T[2])+isA($T[7])+isA($T[12])+isA($T[17])+isA($T[22])+isA($T[27])+isA($T[32])+isA($T[37])+isA($T[42])+isA($T[47])+isA($T[52])+isA($T[57])+isA($T[62])+isA($T[67])+isA($T[72]);
            $ord=isA($T[3])+isA($T[8])+isA($T[13])+isA($T[18])+isA($T[23])+isA($T[28])+isA($T[33])+isA($T[38])+isA($T[43])+isA($T[48])+isA($T[53])+isA($T[58])+isA($T[63])+isA($T[68])+isA($T[73]);
            $exh=isA($T[4])+isA($T[9])+isA($T[14])+isA($T[19])+isA($T[24])+isA($T[29])+isA($T[34])+isA($T[39])+isA($T[44])+isA($T[49])+isA($T[54])+isA($T[59])+isA($T[64])+isA($T[69])+isA($T[74]);
            $aut=isA($T[5])+isA($T[10])+isA($T[15])+isA($T[20])+isA($T[25])+isA($T[30])+isA($T[35])+isA($T[40])+isA($T[45])+isA($T[50])+isA($T[55])+isA($T[60])+isA($T[65])+isA($T[70])+isA($T[75]);

            //---------------
            $aff=isA($T[76])+isA($T[81])+isA($T[86])+isA($T[91])+isA($T[96])+isA($T[101])+isA($T[106])+isA($T[111])+isA($T[116])+isA($T[121])+isA($T[126])+isA($T[131])+isA($T[136])+isA($T[141])+isA($T[146]);
            $int=isA($T[77])+isA($T[82])+isA($T[87])+isA($T[92])+isA($T[97])+isA($T[102])+isA($T[107])+isA($T[112])+isA($T[117])+isA($T[122])+isA($T[127])+isA($T[132])+isA($T[137])+isA($T[142])+isA($T[147]);
            $suc=isA($T[78])+isA($T[83])+isA($T[88])+isA($T[93])+isA($T[98])+isA($T[103])+isA($T[108])+isA($T[113])+isA($T[118])+isA($T[123])+isA($T[128])+isA($T[133])+isA($T[138])+isA($T[143])+isA($T[148]);
            $dom=isA($T[79])+isA($T[84])+isA($T[89])+isA($T[94])+isA($T[99])+isA($T[104])+isA($T[109])+isA($T[114])+isA($T[119])+isA($T[124])+isA($T[129])+isA($T[134])+isA($T[139])+isA($T[144])+isA($T[149]);
            $aba=isA($T[80])+isA($T[85])+isA($T[90])+isA($T[95])+isA($T[100])+isA($T[105])+isA($T[110])+isA($T[115])+isA($T[120])+isA($T[125])+isA($T[130])+isA($T[135])+isA($T[140])+isA($T[145])+isA($T[150]);

            //--------------
            $nur=isA($T[151])+isA($T[156])+isA($T[161])+isA($T[166])+isA($T[171])+isA($T[176])+isA($T[181])+isA($T[186])+isA($T[191])+isA($T[196])+isA($T[201])+isA($T[206])+isA($T[211])+isA($T[216])+isA($T[221]);
            $chg=isA($T[152])+isA($T[157])+isA($T[162])+isA($T[167])+isA($T[172])+isA($T[177])+isA($T[182])+isA($T[187])+isA($T[192])+isA($T[197])+isA($T[202])+isA($T[207])+isA($T[212])+isA($T[217])+isA($T[222]);
            $end=isA($T[153])+isA($T[158])+isA($T[163])+isA($T[168])+isA($T[173])+isA($T[178])+isA($T[183])+isA($T[188])+isA($T[193])+isA($T[198])+isA($T[203])+isA($T[208])+isA($T[213])+isA($T[218])+isA($T[223]);
            $het=isA($T[154])+isA($T[159])+isA($T[164])+isA($T[169])+isA($T[174])+isA($T[179])+isA($T[184])+isA($T[189])+isA($T[194])+isA($T[199])+isA($T[204])+isA($T[209])+isA($T[214])+isA($T[219])+isA($T[224]);
            $agg=isA($T[155])+isA($T[160])+isA($T[165])+isA($T[170])+isA($T[175])+isA($T[180])+isA($T[185])+isA($T[190])+isA($T[195])+isA($T[200])+isA($T[205])+isA($T[210])+isA($T[215])+isA($T[220])+isA($T[225]);



            $ach=$ach+isB($T[1])+isB($T[2])+isB($T[3])+isB($T[4])+isB($T[5])+isB($T[76])+isB($T[77])+isB($T[78])+isB($T[79])+isB($T[80])+isB($T[151])+isB($T[152])+isB($T[153])+isB($T[154])+isB($T[155]);
            $def=$def+isB($T[6])+isB($T[7])+isB($T[8])+isB($T[9])+isB($T[10])+isB($T[81])+isB($T[82])+isB($T[83])+isB($T[84])+isB($T[85])+isB($T[156])+isB($T[157])+isB($T[158])+isB($T[159])+isB($T[160]);
            $ord=$ord+isB($T[11])+isB($T[12])+isB($T[13])+isB($T[14])+isB($T[15])+isB($T[86])+isB($T[87])+isB($T[88])+isB($T[89])+isB($T[90])+isB($T[161])+isB($T[162])+isB($T[163])+isB($T[164])+isB($T[165]);
            $exh=$exh+isB($T[16])+isB($T[17])+isB($T[18])+isB($T[19])+isB($T[20])+isB($T[91])+isB($T[92])+isB($T[93])+isB($T[94])+isB($T[95])+isB($T[166])+isB($T[167])+isB($T[168])+isB($T[169])+isB($T[170]);
            $aut=$aut+isB($T[21])+isB($T[22])+isB($T[23])+isB($T[24])+isB($T[25])+isB($T[96])+isB($T[97])+isB($T[98])+isB($T[99])+isB($T[100])+isB($T[171])+isB($T[172])+isB($T[173])+isB($T[174])+isB($T[175]);
            $aff=$aff+isB($T[26])+isB($T[27])+isB($T[28])+isB($T[29])+isB($T[30])+isB($T[101])+isB($T[102])+isB($T[103])+isB($T[104])+isB($T[105])+isB($T[176])+isB($T[177])+isB($T[178])+isB($T[179])+isB($T[180]);
            $int=$int+isB($T[31])+isB($T[32])+isB($T[33])+isB($T[34])+isB($T[35])+isB($T[106])+isB($T[107])+isB($T[108])+isB($T[109])+isB($T[110])+isB($T[181])+isB($T[182])+isB($T[183])+isB($T[184])+isB($T[185]);
            $suc=$suc+isB($T[36])+isB($T[37])+isB($T[38])+isB($T[39])+isB($T[40])+isB($T[111])+isB($T[112])+isB($T[113])+isB($T[114])+isB($T[115])+isB($T[186])+isB($T[187])+isB($T[188])+isB($T[189])+isB($T[190]);
            $dom=$dom+isB($T[41])+isB($T[42])+isB($T[43])+isB($T[44])+isB($T[45])+isB($T[116])+isB($T[117])+isB($T[118])+isB($T[119])+isB($T[120])+isB($T[191])+isB($T[192])+isB($T[193])+isB($T[194])+isB($T[195]);
            $aba=$aba+isB($T[46])+isB($T[47])+isB($T[48])+isB($T[49])+isB($T[50])+isB($T[121])+isB($T[122])+isB($T[123])+isB($T[124])+isB($T[125])+isB($T[196])+isB($T[197])+isB($T[198])+isB($T[199])+isB($T[200]);
            $nur=$nur+isB($T[51])+isB($T[52])+isB($T[53])+isB($T[54])+isB($T[55])+isB($T[126])+isB($T[127])+isB($T[128])+isB($T[129])+isB($T[130])+isB($T[201])+isB($T[202])+isB($T[203])+isB($T[204])+isB($T[205]);
            $chg=$chg+isB($T[56])+isB($T[57])+isB($T[58])+isB($T[59])+isB($T[60])+isB($T[131])+isB($T[132])+isB($T[133])+isB($T[134])+isB($T[135])+isB($T[206])+isB($T[207])+isB($T[208])+isB($T[209])+isB($T[210]);
            $end=$end+isB($T[61])+isB($T[62])+isB($T[63])+isB($T[64])+isB($T[65])+isB($T[136])+isB($T[137])+isB($T[138])+isB($T[139])+isB($T[140])+isB($T[211])+isB($T[212])+isB($T[213])+isB($T[214])+isB($T[215]);
            $het=$het+isB($T[66])+isB($T[67])+isB($T[68])+isB($T[69])+isB($T[70])+isB($T[141])+isB($T[142])+isB($T[143])+isB($T[144])+isB($T[145])+isB($T[216])+isB($T[217])+isB($T[218])+isB($T[219])+isB($T[220]);
            $agg=$agg+isB($T[71])+isB($T[72])+isB($T[73])+isB($T[74])+isB($T[75])+isB($T[146])+isB($T[147])+isB($T[148])+isB($T[149])+isB($T[150])+isB($T[221])+isB($T[222])+isB($T[223])+isB($T[224])+isB($T[225]);
            
            $con=0;
            ($T[1]==$T[151])?$con=$con+1:$con;
            ($T[7]==$T[157])?$con=$con+1:$con;
            ($T[13]==$T[163])?$con=$con+1:$con;
            ($T[19]==$T[169])?$con=$con+1:$con;
            ($T[25]==$T[175])?$con=$con+1:$con;
            ($T[26]==$T[101])?$con=$con+1:$con;
            ($T[32]==$T[107])?$con=$con+1:$con;
            ($T[38]==$T[113])?$con=$con+1:$con;
            ($T[44]==$T[119])?$con=$con+1:$con;
            ($T[50]==$T[125])?$con=$con+1:$con;
            ($T[51]==$T[201])?$con=$con+1:$con;
            ($T[57]==$T[207])?$con=$con+1:$con;
            ($T[63]==$T[213])?$con=$con+1:$con;
            ($T[69]==$T[219])?$con=$con+1:$con;
            ($T[75]==$T[225])?$con=$con+1:$con;
            ($T[1]==$T[151])?$con=$con+1:$con;

            // $ceks = [$ach,$def,$ord,$exh,$aut,$aff,$int,$suc,$dom,$aba,$nur,$chg,$end,$het,$agg];

            $ceks['ach'] = $ach ;
            $ceks['def'] = $def ;
            $ceks['ord'] = $ord ;
            $ceks['exh'] = $exh ;
            $ceks['aut'] = $aut ;
            $ceks['aff'] = $aff ;
            $ceks['int'] = $int ;
            $ceks['suc'] = $suc ;
            $ceks['dom'] = $dom ;
            $ceks['aba'] = $aba ;
            $ceks['nur'] = $nur ;
            $ceks['chg'] = $chg ;
            $ceks['end'] = $end ;
            $ceks['het'] = $het ;
            $ceks['agg'] = $agg ;

            if($gender == "L"){
                if (($ach>= 25)) $acht = 7;
                else if (($ach >= 22)) $acht = 6;
                else if (($ach >= 19)) $acht = 5;
                else if (($ach >= 16)) $acht = 4;
                else if (($ach >= 13)) $acht = 3;
                else if (($ach >= 10)) $acht = 2;
                else $acht = 1;

                if (($def>= 23)) $deft = 7;
                else if (($def >= 20)) $deft = 6;
                else if (($def >= 16)) $deft = 5;
                else if (($def >= 13)) $deft = 4;
                else if (($def >= 10)) $deft = 3;
                else if (($def >= 6)) $deft = 2;
                else $deft = 1;

                if (($ord>= 29)) $ordt = 7;
                else if (($ord >= 25)) $ordt = 6;
                else if (($ord >= 21)) $ordt = 5;
                else if (($ord >= 17)) $ordt = 4;
                else if (($ord >= 13)) $ordt = 3;
                else if (($ord >= 9)) $ordt = 2;
                else $ordt = 1;


                if (($exh>= 19)) $exht = 7;
                else if (($exh >= 16)) $exht = 6;
                else if (($exh >= 12)) $exht = 5;
                else if (($exh >= 9)) $exht = 4;
                else if (($exh >= 6)) $exht = 3;
                else if (($exh >= 2)) $exht = 2;
                else $exht = 1;


                if (($aut>= 17)) $autt = 7;
                else if (($aut >= 13)) $autt = 6;
                else if (($aut >= 10)) $autt = 5;
                else if (($aut >= 7)) $autt = 4;
                else if (($aut >= 3)) $autt = 3;
                else if (($aut >= 0)) $autt = 2;
                else $autt = 1;

                

                if (($aff>= 22)) $afft = 7;
                else if (($aff >= 18)) $afft = 6;
                else if (($aff >= 15)) $afft = 5;
                else if (($aff >= 11)) $afft = 4;
                else if (($aff >= 7)) $afft = 3;
                else if (($aff >= 4)) $afft = 2;
                else $afft = 1;


                if (($int>= 24)) $intt = 7;
                else if (($int >= 20)) $intt = 6;
                else if (($int >= 16)) $intt = 5;
                else if (($int >= 12)) $intt = 4;
                else if (($int >= 8)) $intt = 3;
                else if (($int >= 4)) $intt = 2;
                else $intt = 1;


                if (($suc>= 26)) $suct = 7;
                else if (($suc >= 21)) $suct = 6;
                else if (($suc >= 16)) $suct = 5;
                else if (($suc >= 11)) $suct = 4;
                else if (($suc >= 6)) $suct = 3;
                else if (($suc >= 1)) $suct = 2;
                else $suct = 1;


                if (($dom>= 24)) $domt = 7;
                else if (($dom >= 20)) $domt = 6;
                else if (($dom >= 16)) $domt = 5;
                else if (($dom >= 12)) $domt = 4;
                else if (($dom >= 8)) $domt = 3;
                else if (($dom >= 3)) $domt = 2;
                else $domt = 1;


                if (($aba>= 23)) $abat = 7;
                else if (($aba >= 20)) $abat = 6;
                else if (($aba >= 16)) $abat = 5;
                else if (($aba >= 13)) $abat = 4;
                else if (($aba >= 10)) $abat = 3;
                else if (($aba >= 6)) $abat = 2;
                else $abat = 1;


                if (($nur>= 28)) $nurt = 7;
                else if (($nur >= 24)) $nurt = 6;
                else if (($nur >= 20)) $nurt = 5;
                else if (($nur >= 16)) $nurt = 4;
                else if (($nur >= 12)) $nurt = 3;
                else if (($nur >= 9)) $nurt = 2;
                else $nurt = 1;


                if (($chg>= 21)) $chgt = 7;
                else if (($chg >= 18)) $chgt = 6;
                else if (($chg >= 14)) $chgt = 5;
                else if (($chg >= 10)) $chgt = 4;
                else if (($chg >= 6)) $chgt = 3;
                else if (($chg >= 2)) $chgt = 2;
                else $chgt = 1;


                if (($end>= 30)) $endt = 7;
                else if (($end >= 26)) $endt = 6;
                else if (($end >= 22)) $endt = 5;
                else if (($end >= 18)) $endt = 4;
                else if (($end >= 14)) $endt = 3;
                else if (($end >= 10)) $endt = 2;
                else $endt = 1;


                if (($het>= 18)) $hett = 7;
                else if (($het >= 14)) $hett = 6;
                else if (($het >= 9)) $hett = 5;
                else if (($het >= 4)) $hett = 4;
                else if (($het >= 0)) $hett = 3;
                else if (($het >= 0)) $hett = 2;
                else $hett = 1;


                if (($agg>= 21)) $aggt = 7;
                else if (($agg >= 17)) $aggt = 6;
                else if (($agg >= 13)) $aggt = 5;
                else if (($agg >= 9)) $aggt = 4;
                else if (($agg >= 5)) $aggt = 3;
                else if (($agg >= 1)) $aggt = 2;
                else $aggt = 1;
            }
            else{
                        
                if (($ach>= 25)) $acht = 7;
                else if (($ach >= 21)) $acht = 6;
                else if (($ach >= 18)) $acht = 5;
                else if (($ach >= 14)) $acht = 4;
                else if (($ach >= 11)) $acht = 3;
                else if (($ach >= 7)) $acht = 2;
                else $acht = 1;

                if (($def>= 26)) $deft = 7;
                else if (($def >= 22)) $deft = 6;
                else if (($def >= 18)) $deft = 5;
                else if (($def >= 14)) $deft = 4;
                else if (($def >= 10)) $deft = 3;
                else if (($def >= 6)) $deft = 2;
                else $deft = 1;

                
                if (($ord>= 28)) $ordt = 7;
                else if (($ord >= 25)) $ordt = 6;
                else if (($ord >= 20)) $ordt = 5;
                else if (($ord >= 16)) $ordt = 4;
                else if (($ord >= 12)) $ordt = 3;
                else if (($ord >= 8)) $ordt = 2;
                else $ordt = 1;

                if (($exh>= 17)) $exht = 7;
                else if (($exh >= 14)) $exht = 6;
                else if (($exh >= 11)) $exht = 5;
                else if (($exh >= 7)) $exht = 4;
                else if (($exh >= 4)) $exht = 3;
                else if (($exh >= 0)) $exht = 2;
                else $exht = 1;

                if (($aut>= 15)) $autt = 7;
                else if (($aut >= 13)) $autt = 6;
                else if (($aut >= 10)) $autt = 5;
                else if (($aut >= 7)) $autt = 4;
                else if (($aut >= 4)) $autt = 3;
                else if (($aut >= 1)) $autt = 2;
                else $autt = 1;

                
                if (($aff>= 23)) $afft = 7;
                else if (($aff >= 21)) $afft = 6;
                else if (($aff >= 18)) $afft = 5;
                else if (($aff >= 14)) $afft = 4;
                else if (($aff >= 11)) $afft = 3;
                else if (($aff >= 7)) $afft = 2;
                else $afft = 1;

                if (($int>= 25)) $intt = 7;
                else if (($int >= 21)) $intt = 6;
                else if (($int >= 17)) $intt = 5;
                else if (($int >= 12)) $intt = 4;
                else if (($int >= 8)) $intt = 3;
                else if (($int >= 4)) $intt = 2;
                else $intt = 1;

                if (($suc>= 19)) $suct = 7;
                else if (($suc >= 15)) $suct = 6;
                else if (($suc >= 12)) $suct = 5;
                else if (($suc >= 8)) $suct = 4;
                else if (($suc >= 4)) $suct = 3;
                else if (($suc >= 0)) $suct = 2;
                else $suct = 1;

                if (($dom>= 25)) $domt = 7;
                else if (($dom >= 20)) $domt = 6;
                else if (($dom >= 16)) $domt = 5;
                else if (($dom >= 12)) $domt = 4;
                else if (($dom >= 8)) $domt = 3;
                else if (($dom >= 3)) $domt = 2;
                else $domt = 1;

                if (($aba>= 23)) $abat = 7;
                else if (($aba >= 20)) $abat = 6;
                else if (($aba >= 18)) $abat = 5;
                else if (($aba >= 16)) $abat = 4;
                else if (($aba >= 12)) $abat = 3;
                else if (($aba >= 8)) $abat = 2;
                else $abat = 1;

                if (($nur>= 27)) $nurt = 7;
                else if (($nur >= 23)) $nurt = 6;
                else if (($nur >= 19)) $nurt = 5;
                else if (($nur >= 15)) $nurt = 4;
                else if (($nur >= 11)) $nurt = 3;
                else if (($nur >= 7)) $nurt = 2;
                else $nurt = 1;

                if (($chg>= 24)) $chgt = 7;
                else if (($chg >= 20)) $chgt = 6;
                else if (($chg >= 16)) $chgt = 5;
                else if (($chg >= 12)) $chgt = 4;
                else if (($chg >= 8)) $chgt = 3;
                else if (($chg >= 4)) $chgt = 2;
                else $chgt = 1;

                if (($end>= 30)) $endt = 7;
                else if (($end >= 26)) $endt = 6;
                else if (($end >= 22)) $endt = 5;
                else if (($end >= 18)) $endt = 4;
                else if (($end >= 14)) $endt = 3;
                else if (($end >= 10)) $endt = 2;
                else $endt = 1;

                if (($het>= 16)) $hett = 7;
                else if (($het >= 12)) $hett = 6;
                else if (($het >= 8)) $hett = 5;
                else if (($het >= 4)) $hett = 4;
                else if (($het >= 0)) $hett = 3;
                else if (($het >= 0)) $hett = 2;
                else $hett = 1;

                if (($agg>= 21)) $aggt = 7;
                else if (($agg >= 17)) $aggt = 6;
                else if (($agg >= 13)) $aggt = 5;
                else if (($agg >= 9)) $aggt = 4;
                else if (($agg >= 5)) $aggt = 3;
                else if (($agg >= 1)) $aggt = 2;
                else $aggt = 1;		

            }

            $cekst['acht'] = cektKodeEPPS($acht) ;
            $cekst['deft'] = cektKodeEPPS($deft) ;
            $cekst['ordt'] = cektKodeEPPS($ordt) ;
            $cekst['exht'] = cektKodeEPPS($exht) ;
            $cekst['autt'] = cektKodeEPPS($autt) ;
            $cekst['afft'] = cektKodeEPPS($afft) ;
            $cekst['intt'] = cektKodeEPPS($intt) ;
            $cekst['suct'] = cektKodeEPPS($suct) ;
            $cekst['domt'] = cektKodeEPPS($domt) ;
            $cekst['abat'] = cektKodeEPPS($abat) ;
            $cekst['nurt'] = cektKodeEPPS($nurt) ;
            $cekst['chgt'] = cektKodeEPPS($chgt) ;
            $cekst['endt'] = cektKodeEPPS($endt) ;
            $cekst['hett'] = cektKodeEPPS($hett) ;
            $cekst['aggt'] = cektKodeEPPS($aggt) ;

            $jenisl = $gender == 'L' ? 'l' : 'p';
            // $jenisp = $gender == 'P' ? 'p' : 'Perempuan';
            $achw = self::carinormaepps('ach'.$jenisl,$ach);
            $defw = self::carinormaepps("def".$jenisl,$def);
            $ordw = self::carinormaepps("ord".$jenisl,$ord);
            $exhw = self::carinormaepps("exh".$jenisl,$exh);
            $autw = self::carinormaepps("aut".$jenisl,$aut);
            $affw = self::carinormaepps("aff".$jenisl,$aff);
            $intw = self::carinormaepps("int".$jenisl,$int);
            $sucw = self::carinormaepps("suc".$jenisl,$suc);
            $domw = self::carinormaepps("dom".$jenisl,$dom);
            $abaw = self::carinormaepps("aba".$jenisl,$aba);
            $nurw = self::carinormaepps("nur".$jenisl,$nur);
            $chgw = self::carinormaepps("chg".$jenisl,$chg);
            $endw = self::carinormaepps("end".$jenisl,$end);
            $hetw = self::carinormaepps("het".$jenisl,$het);
            $aggw = self::carinormaepps("agg".$jenisl,$agg);


            $ceksw['achw'] = $achw ;
            $ceksw['defw'] = $defw ;
            $ceksw['ordw'] = $ordw ;
            $ceksw['exhw'] = $exhw ;
            $ceksw['autw'] = $autw ;
            $ceksw['affw'] = $affw ;
            $ceksw['intw'] = $intw ;
            $ceksw['sucw'] = $sucw ;
            $ceksw['domw'] = $domw ;
            $ceksw['abaw'] = $abaw ;
            $ceksw['nurw'] = $nurw ;
            $ceksw['chgw'] = $chgw ;
            $ceksw['endw'] = $endw ;
            $ceksw['hetw'] = $hetw ;
            $ceksw['aggw'] = $aggw ;



        $all_array['konsistensi'] = $con;
        $all_array['raw'] = $ceks;
        $all_array['T'] = $ceksw;
        $all_array['code'] = $cekst;
        return $all_array;
    }

    public static function carinormaepps($kolom,$value)
    {
        $query="SELECT $kolom FROM norma_epps WHERE nilai='$value'" ;
        $norma = DB::select(DB::raw($query));
        return $norma[0]->$kolom;
    }



}
