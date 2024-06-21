<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OccupationController extends Controller
{
    public function getData($num){
        $soal = self::soal();

        return response()->json([
            'quest' => $soal,
            'num'=> $num
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
            $packet = Packet::where('test_id','=',103)->where('status','=',1)->first();

            // View
            return view('test/occupational', [
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

    public static function soal()
    {
        $jawaba[0]='Senang memberikan tugas kepada orang lain';
        $jawaba[1]='Suka mengevaluasi suatu pekerjaan';
        $jawaba[2]='Mengikuti aturan dan prosedur yang tidak boleh ditinggalkan';
        $jawaba[3]='Merasa cemas sebelum sesuatu jelas terjadi';
        $jawaba[4]='Seringkali terbuka dengan permasalahan orang lain';
        $jawaba[5]='Berusaha memahami mengapa orang lain melakukan sesuatu';
        $jawaba[6]='Terkadang suka melakukan hal-hal di luar prosedur';
        $jawaba[7]='Mudah mengambil kesimpulan';
        $jawaba[8]='Saya beritahu ketika orang lain berbuat salah';
        $jawaba[9]='Mengikuti prosedur yang sudah ada';
        $jawaba[10]='Peduli dengan batas waktu yang ditetapkan';
        $jawaba[11]='Jarang ejekan menjadikan marah ';
        $jawaba[12]='Seringkali terbuka dan memberi dukungan ketika dibutuhkan';
        $jawaba[13]='Menganggap orang-orang yang pintar mendorong organisasi';
        $jawaba[14]='Sering mengacu pada aturan dan prosedur';
        $jawaba[15]='Cepat dalam mengambil keputusan';
        $jawaba[16]='Tetap akan melakukannya meski orang lain tidak setuju';
        $jawaba[17]='Mudah menerapkan ide baru';
        $jawaba[18]='Peduli dengan tugas sampai selesai';
        $jawaba[19]='Melihat dari sisi positif';
        $jawaba[20]='Melihat pendapat orang lain sebelum mengambil keputusan';
        $jawaba[21]='Tertarik dengan hal-hal baru';
        $jawaba[22]='Perhatian dengan hal-hal kecil';
        $jawaba[23]='Menempatkan kemajuan karir sebagai aspek yang penting';
        $jawaba[24]='Banyak bicara';
        $jawaba[25]='Mengubah sikap sesuai dengan situasi yang ada';
        $jawaba[26]='Seringkali mengacu pada aturan dan prosedur';
        $jawaba[27]='Percaya orang lain dapat diandalkan';
        $jawaba[28]='Terbuka dengan permasalahan orang lain';
        $jawaba[29]='Sering melihat jauh ke depan';
        $jawaba[30]='Peduli dengan tugas sampai selesai';
        $jawaba[31]='Mudah mengambil kesimpulan';
        $jawaba[32]='Nyaman dengan banyak orang di sekeliling saya';
        $jawaba[33]='Orang yang teratur';
        $jawaba[34]='Mementingkan keteraturan';
        $jawaba[35]='Jarang menunjukkan perasaan kepada orang lain';
        $jawaba[36]='Berusaha melihat sudut pandang orang lain';
        $jawaba[37]='Peduli dengan tugas sampai selesai';
        $jawaba[38]='Sering melihat jauh ke depan';
        $jawaba[39]='Orang yang ambisius';
        $jawaba[40]='Mudah membawa diri dalam situasi formal';
        $jawaba[41]='Mengandalkan aturan dan prosedur';
        $jawaba[42]='Peduli dengan aturan prosedur';
        $jawaba[43]='Berusaha tetap sibuk';
        $jawaba[44]='Tidak terlalu menunjukkan kelebihan saya';
        $jawaba[45]='Suka memahami mengapa orang lain melakukan sesuatu';
        $jawaba[46]='Peduli dengan batas waktu yang ditetapkan';
        $jawaba[47]='Senang dengan kemenangan';
        $jawaba[48]='Menjaga prestasi saya untuk diri sendiri';
        $jawaba[49]='Mengandalkan prosedur yang sudah ada';
        $jawaba[50]='Peduli dengan hal-hal detail';
        $jawaba[51]='Menganggap diri ambisius';
        $jawaba[52]='Mudah terbuka dan memberi dukungan ketika dibutuhkan';
        $jawaba[53]='Menganggap orang-orang yang pintar mendorong organisasi';
        $jawaba[54]='Senang dengan target-target jangka panjang';
        $jawaba[55]='Ragu dalam mengambil keputusan penting';
        $jawaba[56]='Melihat pendapat orang lain sebelum mengambil keputusan';
        $jawaba[57]='Mudah menerapkan ide baru';
        $jawaba[58]='Mengubah gaya bicara saya dengan siapa saya bicara';
        $jawaba[59]='Puas dengan kemenangan';
        $jawaba[60]='Melihat pendapat orang lain sebelum mengambil keputusan';
        $jawaba[61]='Senang dengan hal-hal atau cara baru';
        $jawaba[62]='Seringkali mengacu pada aturan dan prosedur';
        $jawaba[63]='Sulit untuk memberi tantangan kepada orang lain';
        $jawaba[64]='Terbuka dan memberi dukungan ketika dibutuhkan';
        $jawaba[65]='Mengubah sikap sesuai dengan situasi yang ada';
        $jawaba[66]='Peduli dengan tugas sampai selesai';
        $jawaba[67]='Cukup cepat dalam mengambil keputusan';
        $jawaba[68]='Menjaga prestasi saya untuk diri sendiri';
        $jawaba[69]='Sering memandang jauh ke depan';
        $jawaba[70]='Merasa yakin dengan masa depan';
        $jawaba[71]='Mengatakan sesuai apa yang saya pikirkan';
        $jawaba[72]='Orang yang teratur';
        $jawaba[73]='Teratur dalam bekerja';
        $jawaba[74]='Mudah percaya dengan orang lain';
        $jawaba[75]='Mudah membawa diri dalam situasi formal';
        $jawaba[76]='Peduli dengan tugas sampai selesai';
        $jawaba[77]='Tidak senang membicarakan perasaan saya';
        $jawaba[78]='Suka melakukan dengan cara saya sendiri';
        $jawaba[79]='Seringkali mengacu pada aturan dan prosedur';
        $jawaba[80]='Suka melakukan banyak hal untuk dikerjakan';
        $jawaba[81]='Mudah memberi dukungan ketika dibutuhkan';
        $jawaba[82]='Sering melihat jauh ke depan';
        $jawaba[83]='Menempatkan kemajuan karir sebagai aspek yang penting';
        $jawaba[84]='Aktif berbicara';
        $jawaba[85]='Melakukan pendekatan dengan cara yang sudah terbiasa';
        $jawaba[86]='Nyaman dengan banyak orang di sekeliling saya';
        $jawaba[87]='Suka mendiskusikan hal-hal teoritis';
        $jawaba[88]='Menikmati aktivitas kompetisi';
        $jawaba[89]='Berusaha melihat sudut pandang orang lain';
        $jawaba[90]='Mengubah sikap sesuai dengan situasi yang ada';
        $jawaba[91]='Orang yang kompetitif';
        $jawaba[92]='Percaya diri dengan orang baru dikenal';
        $jawaba[93]='Termasuk mudah dalam menemukan sesuatu';
        $jawaba[94]='Menempatkan kemajuan karir sebagai aspek yang penting';
        $jawaba[95]='Menjaga prestasi saya untuk diri sendiri';
        $jawaba[96]='Suka berusaha hal-hal baru';
        $jawaba[97]='Sering berusaha tetap sibuk';
        $jawaba[98]='Menjaga prestasi saya untuk diri sendiri';
        $jawaba[99]='Menjadi bosan dengan kebiasaan';
        $jawaba[100]='Cukup cepat dalam mengambil keputusan';
        $jawaba[101]='Mudah membawa diri dalam situasi formal';
        $jawaba[102]='Peduli dengan aturan prosedur';
        $jawaba[103]='Mudah mengambil kesimpulan';
        $jawaba[104]='Berusaha melihat sudut pandang orang lain';
        $jawaba[105]='Mengubah cara berbicara sesuai dengan lawan bicara';
        $jawaba[106]='Berusaha melihat dari sisi positif';
        $jawaba[107]='Nyaman dengan banyak orang di sekeliling saya';
        $jawaba[108]='Peduli dengan batas waktu yang ditetapkan';
        $jawaba[109]='Cukup ambisius dalam mengejar sesuatu';
        $jawaba[110]='Mudah terbuka dengan permasalahan orang lain';
        $jawaba[111]='Senang dengan target-target jangka panjang';
        $jawaba[112]='Mudah mengandalkan orang lain';
        $jawaba[113]='Terkadang kurang bersimpati dengan orang lain';
        $jawaba[114]='Memperhatikan hal-hal detail';
        $jawaba[115]='Senang dengan kemenangan';
        $jawaba[116]='Suka melakukan dengan cara saya sendiri';
        $jawaba[117]='Peduli dengan hal-hal detail';
        $jawaba[118]='Jarang menunjukkan perasaan kepada orang lain';
        $jawaba[119]='Melihat pendapat orang lain sebelum mengambil keputusan';
        $jawaba[120]='Senang dengan target-target jangka panjang';
        $jawaba[121]='Berusaha tetap sibuk';
        $jawaba[122]='Biasanya sangat terbuka';
        $jawaba[123]='Peduli dengan batas waktu yang ditetapkan';
        $jawaba[124]='Berusaha tetap sibuk';
        $jawaba[125]='Tidak memamerkan kelebihan saya';
        $jawaba[126]='Berbicara dengan cara berbeda';
        $jawaba[127]='Jarang menunjukkan perasaan kepada orang lain';
        $jawaba[128]='Memiliki kebutuhan bersosialisasi tinggi';
        $jawaba[129]='Peduli dengan aturan prosedur';
        $jawaba[130]='Cepat bosan dengan rutinitas';
        $jawaba[131]='Senang dengan kemenangan';
        $jawaba[132]='Mudah membawa diri dalam situasi formal';
        $jawaba[133]='Menghormati orang-orang yang saya kagumi dalam organisasi';
        $jawaba[134]='Cepat menerapkan gagasan';
        $jawaba[135]='Orang yang ambisius';
        $jawaba[136]='Merasa percaya diri dengan orang baru dikenal';
        $jawaba[137]='Mudah menerapkan suatu cara baru';
        $jawaba[138]='Mudah mengambil kesimpulan';
        $jawaba[139]='Cukup cepat dalam mengambil keputusan';
        $jawaba[140]='Nyaman dengan banyak orang di sekeliling saya';
        $jawaba[141]='Berusaha hal-hal baru';
        $jawaba[142]='Mudah percaya dengan orang lain';
        $jawaba[143]='Sering berbicara';
        $jawaba[144]='Menempatkan aturan sebagai hal penting';
        $jawaba[145]='Kemajuan karir sebagai aspek yang penting';
        $jawaba[146]='Kurang menonjolkan kelebihan saya';
        $jawaba[147]='Mengubah sikap sesuai dengan situasi yang ada';
        $jawaba[148]='Tidak senang membicarakan perasaan saya';
        $jawaba[149]='Berusaha melihat sudut pandang orang lain';
        $jawaba[150]='Sering melihat jauh ke depan';
        $jawaba[151]='Menikmati aktivitas berkompetisi';
        $jawaba[152]='Terbuka dan memberi dukungan ketika dibutuhkan';
        $jawaba[153]='Peduli dengan tugas sampai selesai';
        $jawaba[154]='Suka melakukan banyak hal untuk dikerjakan';
        $jawaba[155]='Orang yang teratur';

        $jawabb[0]='Menjual bukan kelebihan saya';
        $jawabb[1]='Berusaha menghindari pekerjaan yang banyak menggunakan angka';
        $jawabb[2]='Jarang mengikuti pekerjaan sampai akhir';
        $jawabb[3]='Sulit untuk nyaman';
        $jawabb[4]='Dapat dan sering membuat keputusan tanpa konsultasi';
        $jawabb[5]='Menikmati pemecahan masalah terkait dengan angka';
        $jawabb[6]='Perhatian terhadap hal-hal detail';
        $jawabb[7]='Kurang terdorong dengan target';
        $jawabb[8]='Senang menjual ide kepada orang lain';
        $jawabb[9]='Mendasari kesimpulan berdasarkan fakta dan data';
        $jawabb[10]='Sering lupa dengan hal-hal detail';
        $jawabb[11]='Merasa tenang';
        $jawabb[12]='Tidak memamerkan kelebihan';
        $jawabb[13]='Tertarik dengan pekerjaan berkaitan dengan angka-angka';
        $jawabb[14]='Senang dengan target-target jangka panjang';
        $jawabb[15]='Senang dengan kemenangan';
        $jawabb[16]='Mudah melakukan negosiasi';
        $jawabb[17]='Mendasari keputusan berdasarkan fakta dan data';
        $jawabb[18]='Senang dengan target-target jangka panjang';
        $jawabb[19]='Mudah untuk santai';
        $jawabb[20]='Menunjukkan keberhasilan kepada orang lain';
        $jawabb[21]='Menikmati pemecahan masalah terkait dengan angka';
        $jawabb[22]='Tidak menyukai pola pikir yang terlalu jauh';
        $jawabb[23]='Menganggap partisipasi lebih penting daripada kemenangan';
        $jawabb[24]='Senang menjual gagasan atau ide';
        $jawabb[25]='Mendasari kesimpulan dari fakta dan data';
        $jawabb[26]='Mengubah gaya bicara tergantung dengan siapa saya bicara';
        $jawabb[27]='Merasa tenang';
        $jawabb[28]='Mudah membawa diri dalam situasi formal';
        $jawabb[29]='Menikmati pemecahan masalah terkait dengan angka';
        $jawabb[30]='Mengubah sikap sesuai dengan situasi yang ada';
        $jawabb[31]='Berusaha tetap sibuk';
        $jawabb[32]='Menganggap bernegosiasi adalah kelebihan saya';
        $jawabb[33]='Mudah mengambil Keputusan berdasarkan fakta dan data';
        $jawabb[34]='Mengubah gaya bicara sesuai pendengar';
        $jawabb[35]='Mudah untuk santai';
        $jawabb[36]='Percaya diri dengan situasi baru';
        $jawabb[37]='Menikmati pemecahan masalah terkait dengan angka';
        $jawabb[38]='Berlaku sama pada setiap orang';
        $jawabb[39]='Suka melakukan banyak hal untuk dikerjakan';
        $jawabb[40]='Mudah menjual ide kepada orang lain';
        $jawabb[41]='Menikmati pemecahan masalah terkait dengan angka';
        $jawabb[42]='Cepat bosan dengan rutinitas';
        $jawabb[43]='Sering merasa tenang';
        $jawabb[44]='Terkadang risih untuk bertemu orang baru';
        $jawabb[45]='Jarang mencari kesalahan dari suatu pekerjaan';
        $jawabb[46]='Mudah jemu dengan rutinitas';
        $jawabb[47]='Mudah untuk bersantai';
        $jawabb[48]='Mudah melakukan negosiasi';
        $jawabb[49]='Mudah melihat kesalahan dari suatu pernyataan';
        $jawabb[50]='Tertarik dengan hal-hal baru';
        $jawabb[51]='Merasa nyaman';
        $jawabb[52]='Senang berkelompok';
        $jawabb[53]='Suka mengevaluasi suatu pekerjaan';
        $jawabb[54]='Menganggap rutinitas seringkali membosankan';
        $jawabb[55]='Menganggap situasi santai mudah didapatkan';
        $jawabb[56]='Menawarkan ide kepada orang lain';
        $jawabb[57]='Mudah melihat kesalahan dari suatu gagasan orang lain';
        $jawabb[58]='Suka dengan pekerjaan rutin';
        $jawabb[59]='Tidak senang dengan pekerjaan yang terlalu banyak pada waktu yang sama';
        $jawabb[60]='Nyaman dengan banyak orang di sekeliling saya';
        $jawabb[61]='Suka mengevaluasi suatu pekerjaan';
        $jawabb[62]='Menganggap sebagai penemu / pencipta';
        $jawabb[63]='Tetap tenang meskipun akan terjadi hal-hal yang penting';
        $jawabb[64]='Bernegosiasi dengan mudah';
        $jawabb[65]='Mudah melihat kesalahan dari suatu pendapat';
        $jawabb[66]='Mudah menerapkan suatu ide baru';
        $jawabb[67]='Tidak senang membicarakan perasaan saya';
        $jawabb[68]='Senang berkumpul dengan banyak orang';
        $jawabb[69]='Suka mengevaluasi suatu pekerjaan';
        $jawabb[70]='Cemas dengan hal-hal yang saya anggap penting';
        $jawabb[71]='Membiarkan orang lain mengambil alih situasi';
        $jawabb[72]='Mudah melihat kesalahan dari suatu argumen';
        $jawabb[73]='Mudah menciptakan sesuatu';
        $jawabb[74]='Merasa cemas sebelum sesuatu jelas terjadi';
        $jawabb[75]='Lebih memilih menjaga jarak dengan orang lain';
        $jawabb[76]='Senang mengevaluasi suatu pekerjaan';
        $jawabb[77]='Cemas terhadap sesuatu hal yang akan terjadi';
        $jawabb[78]='Suka memimpin kelompok';
        $jawabb[79]='Cepat melihat kesalahan dari pernyataan';
        $jawabb[80]='Merasa cemas sebelum sesuatu jelas terjadi';
        $jawabb[81]='Berbicara banyak dan sering dilakukan';
        $jawabb[82]='Mudah menerapkan suatu ide baru';
        $jawabb[83]='Jarang menunjukkan perasaan kepada orang lain';
        $jawabb[84]='Senang memberikan tugas kepada orang lain';
        $jawabb[85]='Jarang tertarik dengan tindakan orang lain';
        $jawabb[86]='Senang memberikan tugas kepada orang lain';
        $jawabb[87]='Tertarik menganalisa orang lain';
        $jawabb[88]='Cemas dengan hal-hal yang saya anggap penting';
        $jawabb[89]='Biasanya terbuka dengan seseorang';
        $jawabb[90]='Cepat dalam menciptakan sesuatu';
        $jawabb[91]='Kurang nyaman membicarakan perasaan saya kepada orang lain';
        $jawabb[92]='Suka memimpin kelompok';
        $jawabb[93]='Berupaya memahami orang lain melakukan sesuatu';
        $jawabb[94]='Merasa cemas sebelum sesuatu jelas terjadi';
        $jawabb[95]='Banyak berbicara';
        $jawabb[96]='Sedikit memiliki ide-ide original';
        $jawabb[97]='Merasa orang lain mengetahui perasaan saya';
        $jawabb[98]='Senang memberikan tugas kepada orang lain';
        $jawabb[99]='Tertarik menganalisa orang lain';
        $jawabb[100]='Khawatir dengan hal-hal yang saya anggap penting';
        $jawabb[101]='Terbuka dengan lingkungan sekitar';
        $jawabb[102]='Menganggap diri sendiri pintar mendorong organisasi';
        $jawabb[103]='Menganggap orang lain dapat diandalkan';
        $jawabb[104]='Senang memberikan tugas kepada orang lain';
        $jawabb[105]='Suka memahami mengapa orang lain melakukan sesuatu';
        $jawabb[106]='Cepat mudah sedih';
        $jawabb[107]='Pendiam';
        $jawabb[108]='Suka membahas hal-hal teoritis';
        $jawabb[109]='Percaya dengan orang lain';
        $jawabb[110]='Suka memimpin kelompok';
        $jawabb[111]='Tertarik menganalisa orang lain';
        $jawabb[112]='Sulit untuk memberi tantangan kepada orang lain';
        $jawabb[113]='Tetap akan melakukan meski banyak yang menentang';
        $jawabb[114]='Menganggap diri pintar mendorong organisasi';
        $jawabb[115]='Orang-orang menurut saya dapat diandalkan';
        $jawabb[116]='Mengatakan sesuai apa yang saya pikirkan';
        $jawabb[117]='Suka memahami mengapa orang lain melakukan sesuatu';
        $jawabb[118]='Tidak mudah marah terhadap ejekan';
        $jawabb[119]='Suka melakukan dengan cara saya sendiri';
        $jawabb[120]='Suka mendiskusikan hal-hal teoritis';
        $jawabb[121]='Dapat dengan cepat percaya kepada orang lain';
        $jawabb[122]='Menganggap ide hanya untuk diri saya sendiri';
        $jawabb[123]='Tertarik menganalisa orang lain';
        $jawabb[124]='Sulit untuk menentang pendapat orang lain';
        $jawabb[125]='Tetap akan melakukannya meski orang lain tidak setuju';
        $jawabb[126]='Menganggap mudah untuk mendorong organisasi';
        $jawabb[127]='Memandang dengan rasa curiga';
        $jawabb[128]='Mengatakan sesuai apa yang saya pikirkan';
        $jawabb[129]='Suka memahami mengapa orang lain melakukan sesuatu';
        $jawabb[130]='Suka membahas berbagai teoritis';
        $jawabb[131]='Sulit marah terhadap hinaan';
        $jawabb[132]='Senang mengerjakan sesuatu dengan cara saya sendiri';
        $jawabb[133]='Melakukan pekerjaan dengan cara baru';
        $jawabb[134]='Tidak tertarik berdiskusi tentang konsep yang bersifat abstrak';
        $jawabb[135]='Sulit memberi tantangan kepada orang lain';
        $jawabb[136]='Mengingatkan ketika orang lain berbuat salah';
        $jawabb[137]='Melakukan pendekatan dengan cara-cara yang sudah lazim';
        $jawabb[138]='Sulit tersinggung dengan ejekan';
        $jawabb[139]='Merasa yakin dengan masa depan';
        $jawabb[140]='Tetap akan melakukannya meski orang lain kurang sependapat';
        $jawabb[141]='Mengikuti aturan yang sudah ada';
        $jawabb[142]='Lebih cepat menyerah';
        $jawabb[143]='Mengikuti apa yang orang lain akan lakukan';
        $jawabb[144]='Melakukan pendekatan dengan cara-cara normatif';
        $jawabb[145]='Melihat dari sisi positif';
        $jawabb[146]='Mengatakan sesuai apa yang ada dalam pikiran saya';
        $jawabb[147]='Melakukan pendekatan dengan cara-cara yang sudah terbiasa';
        $jawabb[148]='Merasa yakin dengan masa depan';
        $jawabb[149]='Memberitahu ketika orang lain berbuat salah';
        $jawabb[150]='Mengikuti aturan yang sudah ada';
        $jawabb[151]='Merasa yakin dengan masa depan';
        $jawabb[152]='Mengatakan sesuai apa yang saya pikirkan';
        $jawabb[153]='Cenderung mengikuti prosedur yang sudah ada';
        $jawabb[154]='Memandang dari sisi positif';
        $jawabb[155]='Mengerjakan sesuatu dengan kebiasaan yang sudah ada';

        $data=array();
        $data['jawabA'] = $jawaba;
        $data['jawabB'] = $jawabb;
        return $data;
    }

}
