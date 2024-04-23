<?php

if(!function_exists('cek_umur_koma')){
    function cek_umur_koma(){
        $birthDate = new DateTime(Auth::user()->attribute->birthdate);
        $today = new DateTime("today");
        if ($birthDate > $today) { 
            exit("0,0");
        }
        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;
        $hasil=$y.".".$m;
        return $hasil;
    }
}

if(!function_exists('cekUmur')){
    function cekUmur(){
        $birthday = Auth::user()->attribute->birthdate;
        $now = date('Y-m-d');
        $umur = date_diff(date_create($birthday), date_create($now));
        return $umur->format("%y");
    }
}

if(!function_exists('cektKodeEPPS')){
    function cektKodeEPPS($value){    
        if($value == 0){ $code = "----";}
        else if($value == 1){ $code = "---";}
        else if($value == 2){ $code = "--";}
        else if($value == 3){ $code = "-";}
        else if($value == 4){ $code = "0";}
        else if($value == 5){ $code = "+";}
        else if($value == 6){ $code = "++";}
        else if($value == 7){ $code = "+++";}
    
        return $code;
    
    }
}

if(!function_exists('convertEPPS')){
    function convertEPPS($ceks)
    {
        $achtt="<b>Ach:</b><br>Subjek melakukan tindakan terbaik untuk dapat menyelesaikan tiap tugas yang diberikan dengan menggunakan usaha pikiran dan kemampuannya, agar dapat dipandang dapat menyelesaikan pekerjaan sulit oleh atasan. Ia tertarik untuk menyelesaikan tugas menantang dan permasalahan rumit lebih baik dari orang lain.";
        $deftt="<b>Def:</b><br>Subjek mudah terpengaruh dengan keadaan orang lain, memiliki ketertarikan tinggi mengenai kesuksesan orang lain. Ia mudah diberi instruksi dan dapat mengikuti atau memberikan apa yang diinginkan orang lain. Ia senang menunjukkan pekerjaan yang telah diselesaikan, dapat dengan mudah mengikuti petunjuk atasan. Ia cenderung mengandalkan orang lain dalam mengambil keputusan dan tindakan konformitasnya tinggi. Ia berperilaku sesuai norma yang ada dalam lingkungannya dengan menghindari cara-cara atau tindakan yang tidak wajar.";
        $ordtt="<b>Ord:</b><br>Subjek bekerja dengan sifat keteraturan yang tinggi dan terorganisir secara rapi. Demikian pula dalam melakukan perencanaan sebelum memulai aktivitas dilakukan secara rapi dan detail. Ia bertindak sesuai keteraturan dan prosedur, detail dalam bekerja dan berusaha segala sesuatu sesuai kebiasaan dan terstruktur berdasarkan petunjuk yang ada. Ia sangat teratur bukan hanya bekerja namun pada perilaku-perilaku umum lainnya sehingga tampak dirinya beraktivitas monoton dengan ketidaktertarikan pada perubahan. Ia kurang dapat bertindak fleksibel.";
        $exhtt="<b>Exh:</b><br>Subjek senang memamerkan diri di lingkungan sosial. Ia senang menjadi perhatian orang lain, terbuka dan mudah bergaul, senang bercerita mengenai pengalaman atau cerita-cerita yang menarik dirinya. Ia bangga dengan dirinya sendiri, berusaha menunjukkan kepintarannya kepada orang lain. Ia senang apabila orang lain tidak dapat menjawab pertanyaan yang diajukannya dan seringkali menggunakan istilah-istilah asing yang kurang dimengerti oleh orang lain agar tampak pintar.";
        $auttt="<b>Aut:</b><br>Subjek mudah bertindak atau bersikap sesuai keinginannya tanpa banyak hambatan. Ia tidak tergantung dengan orang lain dan mudah mengambil keputusan. Ia kurang mempertimbangkan lingkungan atau orang lain dalam melakukan sesuatu, mudah bersikap atau beraktivitas terhadap hal-hal yang tidak lazim bagi orang lain dan cenderung menghindari situasi-situasi yang menuntut dirinya menyesuaikan dengan lingkungannya. Ia mudah mengkritisi atasan atau otoritas yang ada dan cenderung menghindar dari rutinitas dan tanggung jawab.";
        $afftt="<b>Aff:</b><br>Subjek loyal terhadap orang lain dan dapat berpartisipasi dengan baik dalam kelompok. Ia mudah melakukan aktivitas untuk orang lain, berinteraksi dengan orang baru dan mencari komunitas baru. Ia terbuka dengan lingkungan sosial, mampu mengungkapkan perasaan sesungguhnya dan lebih senang beraktivitas bersama daripada sendiri. Hubungan sosial yang dijalin erat.";
        $inttt="<b>Int:</b><br>Subjek senang berintrospeksi dengan diri dan perasaannya. Ia senang mengamati tindakan orang lain, mencermati terhadap perilaku-perilaku yang dilakukan dan berusaha mengerti bagaimana perasaan orang lain apabila menghadapi suatu masalah. Ia seringkali menilai orang lain terhadap alasan-alasan atau dasar dari motivasi tindakan daripada apa yang mereka kerjakan. Ia senang menganalisa motivasi atau tindakan orang lain dan tertarik dengan prediksi terhadap bagaimana orang lain akan bertindak.";
        $suctt="<b>Suc:</b><br>Subjek cenderung menggantungkan dirinya kepada orang lain. Ia berusaha mencari dorongan diri dari pihak lain dan meminta bantuan apabila berhadapan dengan masalah. Ia sangat terbuka dengan masalahnya, mencari simpati dan berusaha mendapatkan pemahaman dari orang lain tentang masalahnya. Ia berusaha mendapatkan afeksi dan keramahan dari orang lain, senang dengan simpati atau perhatian yang diterimanya.";
        $domtt="<b>Dom:</b><br>Subjek mampu memberikan argumentasi pandangannya kepada orang lain dengan mudah. Ia ingin dianggap atau dipandang sebagai pemimpin atau ketua dalam kelompok. Ia mampu dengan mudah membuat keputusan berkaitan dengan permasalahan kelompok, mampu mempengaruhi keinginan dirinya kepada orang lain dan dapat melerai konflik dengan mudah. Ia senang memberikan petunjuk dan cara-cara melakukan pekerjaan.";
        $abatt="<b>Aba:</b><br>Subjek mudah merasa bersalah terhadap hal-hal yang dianggapnya tidak sesuai dan mudah menerima kesalahan dari orang lain. Ia menggangap sakit hati atau kesengsaraan diri lebih baik daripada kekerasan yang mungkin timbul. Ia merasa terlalu bersalah sehingga layak untuk dihukum. Ia kurang mampu berargumentasi atau mempertahankan pendapat. Pada situasi konflik ia cenderung diam dan cenderung menerima terhadap tuntutan yang ada. Ia menghindari konfrontasi dan akan merasa tertekan apabila tidak dapat menangani permasalahan. Ia merasa cemas terhadap tekanan yang ada. Ia merasa inferior dalam lingkungan sosial, apatis dalam pergaulan formal maupun non formal.";
        $nurtt="<b>Nur:</b><br>Subjek terbuka dan memiliki perhatian dengan orang lain. Ia senang membantu terhadap orang-orang yang mengalami permasalahan, cepat dalam memberikan bantuan tanpa adanya keinginan mendapatkan balasan. Ia santun dan memiliki simpati kepada orang lain, mudah memaafkan dan peka dalam memberikan perhatian, afeksi atau hubungan interpersonal yang dibutuhkan mereka. Hubungan sosial dapat dilakukan dengan mudah. Ia terbuka, mudah bergaul dan ramah kepada orang lain. Perilaku sosialnyalus.";
        $chgtt="<b>Chg:</b><br>Subjek tertarik dengan situasi-situasi baru, lingkungan maupun orang-orang baru. Rutinitas keseharian dilakukannya dengan cara melakukan sesuatu yang baru atau bereksperimen. Ia kurang betah terhadap rutinitas yang ada dan sulit untuk bertahan pada situasi yang monoton. Ia berusaha melakukan pekerjaan-pekerjaan dengan cara baru, berpindah tempat dengan situasi baru yang dianggapnya menantang. Pada rutinitas keseharian terlihat dengan seringkali mencari tempat makan baru atau mengikuti trend-trend sesuai dengan ketertarikan dirinya.";
        $endtt="<b>End:</b><br>Subjek memiliki tanggung jawab tinggi terhadap pekerjaan, senang menyelesaikan tugas sampai selesai dan bekerja keras terhadap tugas atau permasalahan yang dihadapi. Ia berusaha mendapatkan solusi terhadap permasalahan yang ada dan seringkali bekerja dengan waktu lebih untuk dapat menyelesaikan tugas. Ia tekun terhadap pekerjaan, tidak mudah jenuh terhadap pekerjaan dan mampu bertahan terhadap permasalahan rumit yang bagi orang lain dianggap terlalu menjenuhkan. Ia cenderung tidak senang apabila proses pekerjaannya diggu.";
        $hettt="<b>Het:</b><br>Subjek tertarik bergaul dengan lawan jenis. Dalam beraktivitas sosial, ia memilih lawan jenis untuk mendapatkan perhatian dan afeksi dari mereka. Ia memiliki ketertarikan fisik yang tinggi dengan lawan jenis, memiliki dorongan seksual yang tinggi dengan menyenangi atribut-atribut seksualitas seperti buku, film dan topik-topik lainnya yang menunjukkan kenikmatan seksual.";
        $aggtt="<b>Agg:</b><br>Subjek memiliki dorongan agresi yang tinggi dan mudah terhadap konflik. Ia mudah berselisih paham atau memberi kritik terbuka dan seringkali mendapatkan kesenangan terhadap konfrontasi yang ada. Ia mudah marah terhadap situasi yang menjadikan dirinya tidak nyaman. Ia mudah menyalahkan orang lain dan memiliki ketertarikan kontak fisik yang tinggi dalam menyelesaikan permasalahan.";
        $tt1="Subjek mudah terpengaruh dengan lingkungan atau keadaan dari orang lain, suka memamerkan keberhasilan pekerjaan dan dalam mengambil keputusan banyak menggantungkan kepada orang lain. Di sisi lain ia memiliki sikap ingin bebas dalam beraktivitas sesuai keinginan dirinya. Ia kurang bertanggung jawab terhadap akibat dari aktivitas yang dihasilkan dan tidak penurut. Tampak diriya mengalami konflik kepribadian. ";
        $tt2="Subjek mudah bersosialisasi dengan lingkungan dan berinteraksi terbuka dengan orang lain. Otonomi yang tinggi menjadikan dirinya +berperilaku seenaknya sendiri tanpa memperhatikan norma atau aturan sosial yang berlaku. Hubungan yang terjalin tidak mendalam dan mudah berkonflik dengan orang lain. ";
        $tt3="Subjek memiliki ketertarikan bersosialisasi namun peran yang dilakukannya pasif. Ia banyak berperan sebagai pengamat dan mencermati tingkah laku orang lain. Ia tergantung terhadap situasi sosial akan tetapi keterlibatan dalam kelompok tidak mendalam. ";
        $tt4="Subjek memiliki konflik kepribadian. Di satu sisi ia sangat tergantung dengan orang lain untuk mencari dorongan terhadap pemecahan masalah yang dihadapi, namun di sisi lain ia memiliki penghindaran atau pertentangan terhadap norma atau aturan sosial yang ada. Ia kurang dapat bersikap terbuka dengan perasaannya, ia memiliki dorongan afeksi dan perhatian yang tinggi namun ia kurang mampu bersikap wajar dalam mengekspresikan perasaannya. ";
        $tt5="Subjek memiliki konflik kepribadian. Ia memiliki keteraturan tinggi terhadap perencanaan dan aktivitas rutin akan tetapi ia juga cepat merasa bosan dengan situasi yang monoton. ";
        $tt6="Subjek mudah merasa bersalah terhadap permasalahan yang muncul. Ia mudah menerima kesalahan dari orang lain untuk menghindari konfrontasi. Ia juga dengan mudah memberikan argumentasi terhadap pandangan yang dianggapnya benar. Dorongan untuk menguasai atau mempengaruhi orang lain tidak diperankan dengan memadai sehingga ia mudah menyerah terhadap konflik yang ada. Tampak ia memiliki konflik terhadap konsep dirinya. ";
        $tt7="Subjek memiliki dorongan yang kuat untuk menyelesaikan suatu pekerjaan. Ia tekun dan tidak mudah jenuh terhadap permasalahan rumit yang dihadapi. Aktivitas penyelesaian tugas tidak disertai dengan dorongan untuk melakukan kinerja dengan lebih baik. Tampak dirinya sebagai penyelesai tugas namun tidak memiliki motivasi yang memadai untuk mencari tugas-tugas yang menantang. ";
        $tt8="Subjek memiliki dorongan sosial tinggi, memiliki kepekaan dan simpati terhadap orang lain. Ia dengan mudah memberikan keinginan atau harapan kepada orang lain namun ia juga mudah konflik. Ia senang terhadap situasi konflik dan mudah menyalahkan orang lain. Tampak peran sosial yang dilakukannya manipulatif untuk kepentingan dirinya sendiri. ";
    
        $explanation = array();
        if($ceks['achw'] > 75){array_push($explanation, $achtt);}
        if($ceks['defw'] > 75){array_push($explanation, $deftt);}
        if($ceks['ordw'] > 75){array_push($explanation, $ordtt);}
        if($ceks['exhw'] > 75){array_push($explanation, $exhtt);}
        if($ceks['autw'] > 75){array_push($explanation, $auttt);}
        if($ceks['affw'] > 75){array_push($explanation, $afftt);}
        if($ceks['intw'] > 75){array_push($explanation, $inttt);}
        if($ceks['sucw'] > 75){array_push($explanation, $suctt);}
        if($ceks['domw'] > 75){array_push($explanation, $domtt);}
        if($ceks['abaw'] > 75){array_push($explanation, $abatt);}
        if($ceks['nurw'] > 75){array_push($explanation, $nurtt);}
        if($ceks['chgw'] > 75){array_push($explanation, $chgtt);}
        if($ceks['endw'] > 75){array_push($explanation, $endtt);}
        if($ceks['hetw'] > 75){array_push($explanation, $hettt);}
        if($ceks['aggw'] > 75){array_push($explanation, $aggtt);}


        if (($ceks['defw']>60)&&($ceks['autw']>60))  array_push($explanation,$tt1);
        if (($ceks['autw']>60)&&($ceks['affw']>60))  array_push($explanation,$tt2);
        if (($ceks['intw']>60)&&($ceks['affw']>60))  array_push($explanation,$tt3);
        if (($ceks['sucw']>60)&&($ceks['autw']>60))  array_push($explanation,$tt4);
        if (($ceks['ordw']>60)&&($ceks['chgw']>60))  array_push($explanation,$tt5);
        if (($ceks['abaw']>60)&&($ceks['domw']>60))  array_push($explanation,$tt6);
        if (($ceks['endw']>60)&&($ceks['achw']<30))  array_push($explanation,$tt7);
        if (($ceks['nurw']>60)&&($ceks['aggw']>60))  array_push($explanation,$tt8);
        return $explanation;
    }
}

if(!function_exists('isA')){
    function isA($angkanya)
    {
        if(($angkanya=="a")||($angkanya=="A")){
            $hasil=1;
        }else $hasil=0;

        return $hasil;
    }
}
if(!function_exists('isB')){
    function isB($angkanya)
    {
        if(($angkanya=="b")||($angkanya=="B"))
        {
            $hasil=1;
        }else $hasil=0;

        return $hasil;
    }
}
// Set tanggal lengkap
if(!function_exists('setFullDate')){
    function setFullDate($date){
        $explode1 = explode(" ", $date);
        $explode2 = explode("-", $explode1[0]);
        $month = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        return $explode2[2]." ".$month[$explode2[1]-1]." ".$explode2[0];
    }
}

//cek akses soal
if(!function_exists('existTest')){
    function existTest($id){

        // Get tests
        if(Auth::user()->role->is_global != 1) {
            // $tests = Test::all();
    
            if(Auth::user()->role->is_global === 0 && Auth::user()->role->id === 2){
                $tests = Auth::user()->attribute->company->tests;

            }
            elseif(Auth::user()->role->is_global === 0) {
                $tests = Auth::user()->attribute->position->tests;
                // $tests = Auth::user()->attribute->company->tests;
            }

            $cek_tests = array();
            for($i=0;$i<count($tests);$i++){
                $cek_tests[$i] = $tests[$i]->id;
            }
            $inArray = in_array($id, $cek_tests);

            return $inArray;
        }
    }
}

// Scoring DISC MOST
if(!function_exists('discScoringM')){
    function discScoringM($number){
        $score = round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Scoring DISC LEAST
if(!function_exists('discScoringL')){
    function discScoringL($number){
        $score = 100 - round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Meranking score
if(!function_exists('sortScore')){
    function sortScore($array){
        $ordered_array = $array;
        arsort($ordered_array);
        $i = 1;
        $last_value = '';
        foreach($ordered_array as $ordered_key=>$ordered_value){
            $ordered_array[$ordered_key] = array();
            $ordered_array[$ordered_key]['rank'] = $ordered_value == $last_value ? ($i-1) : $i;
            $ordered_array[$ordered_key]['score'] = $ordered_value;
            $last_value = $ordered_value;
            $i++;
        }
        return $ordered_array;
    }
}

// Membuat kode
if(!function_exists('setCode')){
    function setCode($array){
        $new_array = array();
        $i = 1;
        while($i<=4){
            foreach($array as $key=>$value){
                if($array[$key]['rank'] == $i){
                    if($array[$key]['score'] < 50){
                        $new_value = "L".$key;
                        array_push($new_array, $new_value);
                    }
                    else{
                        $new_value = "H".$key;
                        array_push($new_array, $new_value);
                    }
                }
            }
            $i++;
        }
        return $new_array;
    }
}

// Menghapus array yang bervalue kosong
if(!function_exists('removeEmptyArray')){
    function removeEmptyArray($array, $key = null){
        if($key == null){
            $array_count_values = array_count_values($array);
            if($array_count_values[""] == count($array)){
                unset($array);
            }
        }
        else{
            $array_count_values = array_count_values($array[$key]);
            if($array_count_values[""] == count($array[$key])){
                unset($array[$key]);
            }
        }
    }
}

// Generate string ke url
if(!function_exists('generate_url')){
    function generate_url($string){
        $url = trim($string);
        $url = strtolower($url);
        $url = str_replace(" ", "-", $url);
        return $url;
    }
}

// Hitung umur / usia
if(!function_exists('generate_age')){
    function generate_age($date){
        $birthdate = new DateTime($date);
        $today = new DateTime('today');

        $y = $today->diff($birthdate)->y;
        return $y;
    }
}

// Acak huruf
if(!function_exists('shuffleString')){
    function shuffleString($length){
        $string = '1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
        $shuffle = substr(str_shuffle($string), 0, $length);
        return $shuffle;
    }
}

/*
 *
 * IST Helpers
 *
 */

// Konversi nilai GE
if(!function_exists('convert_GE')){
    function convert_GE($score){
        $array = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 5,
            7 => 6,
            8 => 6,
            9 => 7,
            10 => 7,
            11 => 8,
            12 => 8,
            13 => 9,
            14 => 9,
            15 => 10,
            16 => 10,
            17 => 11,
            18 => 11,
            19 => 12,
            20 => 12,
            21 => 13,
            22 => 13,
            23 => 14,
            24 => 14,
            25 => 15,
            26 => 15,
            27 => 16,
            28 => 17,
            29 => 18,
            30 => 19,
            31 => 20,
            32 => 20,
        ];
        return array_key_exists($score, $array) ? $array[$score] : 0;
    }
}