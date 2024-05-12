<?php

namespace App\Http\Controllers\Test;

use App\Models\Packet;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LAFFController extends Controller
{
    public function getData($test_laff,$part,$id){
        if($test_laff == 'logika_verbal'){$test_id = 71;}
        else if($test_laff == 'apm'){$test_id = 72;}
        else if($test_laff == 'a1'){$test_id = 73;}

        $soal = Question::where('packet_id','=',$test_id)->where('number','=',$part)->first();
        $decode_soal = json_decode($soal->description,true);
        if($test_id == 73){
            $soal = $decode_soal[0]['soal'];
            $naskah = self::naskahA1();
    
            for($i=0;$i<count($soal);$i++){
                if($i <= 1){$soal[$i] = $naskah[0].''.$soal[$i];}
                else if( 1< $i && $i <= 5){$soal[$i] = $naskah[1].''.$soal[$i];}
                else if( 5< $i && $i <= 9){$soal[$i] = $naskah[2].''.$soal[$i];}
                else if( 9< $i && $i <= 13){$soal[$i] = $naskah[3].''.$soal[$i];}
                else if( 13< $i && $i <= 17){$soal[$i] = $naskah[4].''.$soal[$i];}
                else if( 17< $i && $i <= 21){$soal[$i] = $naskah[5].''.$soal[$i];}
                else if( 21< $i && $i <= 25){$soal[$i] = $naskah[6].''.$soal[$i];}
            }

            $decode_soal[0]['soal'] = $soal;
        }

        return response()->json([
            'quest' => $decode_soal,
            'num' => $id,
            'part'=> $part,
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

            return view('test.'.$path,[
                'part' => $part,
                'path' => $path,
                'test' => $test,
                'selection' => $selection,
                'packet' => $packet,
                'jumlah_soal' => $soal->amount,
            ]);
        }
    }
    public static function store(Request $request)
    {
        $jawaban = $request->jawaban;
        if($request->packet_id == 71){
            $kunci=strtoupper("CDBCBDBBACABBCB");
            $table='logika';
        }
        if($request->packet_id == 72){
            $kunci=strtoupper("EAGDCAFAHDEFBABDFGCHHGFCGBGEFEDHEACB");
            $table='apm';
        }
        if($request->packet_id == 73){
            $kunci=strtoupper("dcbcdbbccdbcccadbdabcccacc");
            $table='a1';
        }

        $save_value = 0;
        for($i=1;$i <= count($jawaban);$i++){
            if($kunci[$i-1] == $jawaban[$i]){
                $save_value++;
            }
        }

        $last_save['jawaban'] = $request->jawaban;
        $last_save['benar'] = $save_value;

        if($request->packet_id == 71){
            $select = DB::table('norma_aptitude')->select($table)->where('nilai', $save_value)->first();
            $last_save['iq'] = $select->$table;
        }
        else if($request->packet_id == 73){
            if($save_value<4)$ws=0;
            else if($save_value<6){$ws=1;}
            else if($save_value<7){$ws=2;}
            else if($save_value<9){$ws=3;}
            else if($save_value<11){$ws=4;}
            else if($save_value<13){$ws=5;}
            else if($save_value<15){$ws=6;}
            else if($save_value<17){$ws=7;}
            else if($save_value<19){$ws=8;}
            else if($save_value<21){$ws=9;}
            else {$ws=10;}

            $last_save['iq'] = $ws;
        
        }
        else{
            if($save_value<5)$ws=0;
            else if($save_value<12){$ws=5;}
            else if($save_value<17){$ws=10;}
            else if($save_value<22){$ws=25;}
            else if($save_value<27){$ws=50;}
            else if($save_value<31){$ws=75;}
            else if($save_value<33){$ws=90;}
            else {$ws=95;}

            $last_save['iq'] = $ws;
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

    public static function naskahA1(){
                
        $naskah[0]='Mono dan Widagno sedang membicarakan darma wisata serikat pekerja yang akan diadakan pada hari Sabtu. Mono menawarkan diri untuk menjemput Widagno dengan keluarganya dan membawa mereka ke tempat darma wisata. Namun Widagno mengatakan bahwa pada hari itu biasanya ia mendapatkan giliran tugas menjaga mesin induk dari jam 12 sampai jam 7. Akan tetapi Mono tahu dengan pasti bahwa pabrik tutup pada hari itu sehingga semua karyawan dapat menikmati darma wisata.';

        $naskah[1]='Orang mengatakan bahwa sebagai seorang pendidik salah satu hal yang perlu disadari adalah bahwa ia bekerja bersama-sama dengan orang lain. Kenyataan yang sederhana ini merupakan pedoman penting bagi semua tindakannya. Sifat terpenting yang diminta daripadanya oleh teman-teman sekerjanya bukanlah kecerdasannya yang tinggi dan pengetahuan yang seluas-luasnya, melainkan watak demokratis serta kepribadian gotong-royong dan semangat musyawarah. Mereka lebih menghargai kejujuran terhadap tugas dan kepandaian bergaul daripada ocehan tentang besarnya jumlah anak-anak yang diasuhnya. Salah satu alat penting untuk menjalin kelancaran hubungan dengan teman-teman sekerja dan orang lain adalah kemampuan mengatur dan mengutarakan isi hati dalam tulisan maupun secara lisan. Meskipun demikian tidak banyak orang yang menghiraukan perlunya mempelajari kecakapan pokok ini. Pada dasarnya, makin luas bidang tugas seorang pendidik, makin diperlukan kelancaran menyatakan pikiran baik secara tertulis maupun dalam pembicaraan.';

        $naskah[2]='Saya mempunyai seorang pemimpin yang baik sekali. Ia memberi penghargaan kepada kecakapan saya yang lebih besar dibandingkan dengan kemampuan saya sebenarnya, sehingga saya selalu bekerja keras dari apa yang sebenarnya dapat saya lakukan. Bilamana ia menyerahkan suatu tugas kepada saya dan telah memberi keterangan-keterangan yang cukup tentang tugas itu, ia tidak selalu melakukan periksa pekerjaan saya, melainkan memberikan kesempatan kepada saya untuk menyelesaikan bagian-bagian kecil tugas itu. Ia mempunyai pengetahuan yang luas sekali tentang pekerjaan-pekerjaan dalam bagian dinas yang dipimpinnya dan selalu bersedia mencurahkan perhatiannya pada soal-soal yang kurang saya mengerti. Ia selalu tersenyum, sabar dan ramah-tamah dan selalu memperhatikan kegembiraannya bilamana pekerjaan-pekerjaan diselesaikan dengan lancar dan sebaik-baiknya.';

        $naskah[3]='Menurut pengertian saya, istilah pemilik alat tepat sekali dikenalkan pada tiap-tiap orang yang membantu memberikan modal berupa benda-benda atau alat-alat yang dibutuhkan oleh perusahaan. Pada dasarnya orang-orang ini adalah pemilik benda-benda atau alat-alat itu.

        <br>Di antara pembeli dan pemilik saham perusahaan adalah perseroan tanggung-jiwa dan bank yang menjadi penanam modal dalam segala macam perusahaan atau perindustrian atas nama para pemilik saham dan para penabungnya. Premi yang saya bayarkan kepada perseroan tanggung-jiwa dan tabungan-tabungan yang saya simpan dalam bank, ditanam dalam bermacam industri. Dengan demikian secara tidak langsung saya telah menjadi penanam modal untuk produksi, dan dalam pengertian itu saya adalah seorang dari beribu-ribu pemilik alat.';

        $naskah[4]='Di suatu negeri berlaku suatu peraturan yang menyatakan bahwa jika seorang pegawai diangkat ke jabatan yang lebih tinggi, ia akan tetap menerima gaji yang sama seperti gajinya sebelum pengangkatan sampai ternyata ia telah bekerja dengan memuaskan dalam jabatan baru. Segera setelah pimpinannya menyatakan bahwa ia telah bekerja dengan memuaskan ia akan diberi gaji minimal untuk jabatan baru tersebut, jika ternyata gaji baru ini lebih besar jumlahnya dibandingkan gaji pada jabatan lama. Seandainya ia seterusnya dapat menunjukkan pekerjaan yang memuaskan, selambat-lambatnya pada permulaan bulan ke tujuh dari pengangkatannya ia akan menerima kenaikan gaji sedikitnya 10% di atas gaji minimal dalam kedudukan baru, atau di atas gaji sebelum pengangkatan jika ini ternyata lebih besar, dengan ketentuan bahwa gaji baru ini tidak melebihi gaji maksimal untuk jabatan baru tersebut.';

        $naskah[5]='Beberapa ratus tahun yang lalu para penemu dan para penyelidik bekerja dengan sembunyi-sembunyi, menyembunyikan pengetahuan mereka dari mata umum, seolah mereka itu adalah para penjahat besar. Kenyataannya mereka diperlakukan sebagai penjahat dan disiksa, dibakar atau dikucilkan. Namun akhirnya orang menyadari kenyataan bawa ilmu pengetahuan dan penyelidikan sebenarnya sangat berfaedah bagi umum, dan dengan timbulnya keinsyafan ini terjadilah perubahan-perubahan dalam kehidupan manusia. Tidak banyak orang yang menyadari betapa kelancaran hidup mereka telah mengambil korban kepatahan hati para cerdik pandai karena dihina oleh masyarakat. Pada saat ini para penyelidik kita bekerja terang-terangan dengan menggunakan alat-alat yang diciptakan untuk kepentingan umat manusia. Tiada satu pun yang mustahil dalam abad dimana kita berbicara kepada kotak dan kemudian meminta kotak itu berbicara kepada kita. Kita boleh mengharapkan dengan keyakinan bahwa penyelidikan dan penemuan baru akan melahirkan "Dunia Baru".';

        $naskah[6]='Mardi telah dinyatakan diterima sebagai pegawai penjaga ketel uap. Ia tahu secara umum bahwa air dipanaskan dalam ketel untuk membuat uap dan uap ini digunakan untuk menggerakkan mesin-mesin dalam pabrik, termasuk mesin pembangkit tenaga listrik. Akan tetapi bagian-bagian kecil dalam tugasnya baru akan diterangkan kemudian. Pemimpinnya menunjukkan kepadanya klep-bahaya dan menerangkan bahwa pada tekanan 12 atmosfer klep-bahaya itu akan terbuka dengan sendirinya. Ia menunjukkan juga kepadanya klep lain yang mengawasi banyaknya air yang mengalir ke dalam ketel dan menerangkan bahwa tinggi permukaan air dalam tabung-pengawas-air sedikitnya harus berisi setengah untuk menjaga agar ketel tidak terlanjur kering. Ia memperlihatkan juga bagaimana mengatur api dalam dapur di bawah ketel agar tidak terlalu panas dan bagaimana mengawasi besarnya tekanan uap yang sedang dibuat.';

        return $naskah;
    }
}
