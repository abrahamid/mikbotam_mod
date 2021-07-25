<?php
require "function.php";
require "routeros_api.class.php";
$mikrotik	= getsetting()[0];
$mkIp           = $mikrotik['IP_router'];
$mkPort         = $mikrotik['Port'];
$mkUsername     = $mikrotik['Username_router'];
$mkPassword     = decrypturl($mikrotik['Pass_router']);
$nomerAdmin     =(6285641870839);
$dnsname    = $mikrotik['dnsname'];

$info       = json_decode(file_get_contents('php://input'), true);
$nomer      = $info['number'];
$nomerOnly  = numberOnly($nomer);
$pesan      = $info['message'];
//untuk menghaspu spasi
$pesan = preg_replace('/\s\s+/',' ',$pesan);
// memecah pesan dalam 2 blok array, kita ambil yang array pertama saja
$message    = explode(' ',$pesan,2); //
$command    = $message[0];
$command    = strtolower($command);
$value      = $message[1];

switch($command){
    case '!id':
    case 'id':
        // $detail = getDetailbyNomer($nomerOnly);
        // $text    = "Nama : *".$detail['nama']."*\n";
        // $text   .= "Nomer : *".$detail['nomer']."*\n";
        // $text   .= "Password : *".$detail['password']."*\n";
        // $text   .= "Batas PayLeter : ".rupiah($detail['batas']);
        $text   = "Nomer anda adalah ".$nomerOnly;
        break;
    case '!info':
    case 'info':
        $text    = "Apa yang bisa dilakukan robot ini?\n\n";            
        $text   .= "*!id* - ```Untuk menampilkan nomer telefon```\n\n";
        $text   .= "*!daftar nama password* - ```Untuk mendaftarkan nomer anda agar bisa membeli voucer```\ncontoh : *!daftar abraham abraham12*\n\n";
        $text   .= "*!wifi nominal* - ```Untuk membeli voucer wifi```\ncontoh : *!wifi 5000*\n\n";
        $text   .= "*!tagihan* - ```Untuk menampilkan jumlah hutang```\n\n";
        $text   .= "*!limit* - ```Untuk menampilkan limit maksimal hutang```\n\n";
        $text   .= "*!usage* - ```Untuk menampilkan sisa kuota```\n\n";
        $text   .= "*!harga* - ```Untuk menampilkan harga voucer```\n\n";
        $text   .= "*!tutorial* - ```Untuk menampilkan cara menggunakan Bot ini```\n\n";
        $text   .= "*!info* - ```Untuk menampilkan pesan ini```";
        break;
    
    case 'tutorial':
    case 'cara':
    case '!tutorial':
    case '!cara':
        $text    = "Cara menggunakan Bot untuk melakukan pembelian voucer secara otomatis\n\n";
        $text   .= "1. ketik *!dafta namaanda password*\n";
        $text   .= "Nama dan Password ini akan digunakan untuk login wifi, gunakan hanya 1 suku kata tanpa spasi\n\n";
        $text   .= "2. Jika sudah terregistrasi anda akan mendapatkan batas limit Payleter / hutang senilai ".rupiah(5000)." yang bisa anda gunakan untuk membeli voucer\n\n";
        $text   .= "3. Ketikan *!wifi nominal*\n";
        $text   .= "Ganti nominal sesuai dengan keinginan, \ncontoh : *!wifi 2000*\nBot akan otomatis membuatkan voucer senilai nominal yang dituliskan\n\n";
        $text   .= "Link login akan otomatis dibuatkan oleh bot, silahkan koneksikan ke wifi lalu klik link yang diberikan\n\n";
        $text   .= "Jika link tidak bisa ditekan silahkan simpan nomer Bot ini terlenih dahulu\n\n";
        $text   .= "Balas *!info* untuk menampilkan semua perintah";
        $text   .= "Lunasi tagihan agar anda bisa membeli voucer kembali, terimakasih.\n~ Abraham";
        break;

        //cek harga voucer
    case 'harga':
    case '!harga':
        $harga = getHarga();
        $text = "Harga voucer ".rupiah($harga)." /GB";
        break;

        //pembelian voucer wifi
    case '!wifi':
    case 'wifi':
        $nama = getName(numberOnly($nomer));
        if(empty($nama)){
            $text  = "Maaf anda belum terdaftar.\n";
            $text .= "Silahkan daftar terlebih dahulu dengan mengetik : \n";
            $text .= "*!daftar nama password*\n";
            $text .= "Nama hanya boleh 1 suku kata tanpa spasi\n";
            $text .= "Gunakan password yang tidak mudah diketahui orang lain\n";
            $text .= "Contoh :\n*!daftar abraham abraham99*";

        }else{
            if(empty($value)){
                $text = "nominal tidak boleh kosong";
            }else{
                if(preg_match('/^[0-9]+$/',$value)){
                    if($value > 999){
                        $API = new routeros_api();
                        if($API->connect($mkIp,$mkUsername,$mkPassword,$mkPort)){
                            $ARRAY = $API->comm("/ip/hotspot/user/print",["?name" => $nama, ]);
                            $hutang = cekHutang($nama);
                            $limit  = getLimit($nama);
                            $dnsname    = getsetting()[0]['dnsname'];
                            //DEBUG HASIL ARRAY
                            //$text = json_encode($ARRAY, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            if(empty($ARRAY)){
                                if($limit - $hutang - $value < 0){
                                    $text  = "*Pembelian gagal*\n";
                                    $text .= "Maaf nominal melebihi limit pembelian anda\n";
                                    $text .= "Anda masih memiliki tagihan senilai ".rupiah($hutang)."\n";
                                    $text .= "Batas limit hutang anda senilai ".rupiah($limit)."\n";
                                    $text .= "Segera lunasi tagihan anda agar dapat mengisi voucer\n";
                                }else {
                                    $detailAkun = getDetail($nama);
                                    $password   = $detailAkun['password'];

                                    $profile    = "USER";
                                    $harga = getHarga();
                                    $dataGB = $value / $harga;
                                    $dataB  = $dataGB * 1073741824 ;

                                    $add_user_api = $API->comm("/ip/hotspot/user/add", [
                                        "server" => "all",
                                        "profile" => $profile,
                                        "name" => $nama,
                                        "limit-bytes-total" => $dataB,
                                        "password" => $password,
                                        "comment" => "|Tanggal Daftar : " . date('d-m-Y') . " Nomer Pelanggan $nomerOnly",
                                    ]);
                                    $text   = "Username : $nama\nPassword : $password\nJumlah kuota : ".number_format($dataGB,2)." GB\n";
                                    if(strpos($dnsname,'http://') !== false) {
                                        $url = "$dnsname/login?username=$nama&password=$password";
                                    }else{
                                        $url = "http://$dnsname/login?username=$nama&password=$password";
                                    }
                                    $text   .= $url."\n";
                                    $text .= "*catatan :* ";
                                    $text .= "```Jika link tidak bisa di klik, silahkan simpan nomer ini terlebih dahulu\n";
                                    $text .= "Akun hanya bisa digunakan untuk Handphone ini.jika membutuhkan untuk handphone lain hubungi admin```\n\n";
                                    catatHutang($nama,$nomerOnly,$value);
                                    simpanPenjualan($nama,$value);
                                    $hutang = rupiah(cekHutang($nama));
                                    $text .= "total tagihan anda saat ini $hutang\n\n";
                                }
                            }else{
                                if($limit - $hutang - $value < 0){
                                    $text  = "*Pembelian gagal*\n\n";
                                    $text .= "Maaf nominal melebihi limit pembelian anda.\n";
                                    $text .= "Anda masih memiliki tagihan senilai ".rupiah($hutang).".\n";
                                    $text .= "Batas limit hutang anda senilai ".rupiah($limit).".\n";
                                    $text .= "Segera lunasi tagihan anda agar dapat mengisi voucer. ";
                                }else{
                                    $limit_total   = $ARRAY[0]['limit-bytes-total'];
                                    $id            = $ARRAY[0]['.id'];
                                    $password      = $ARRAY[0]['password'];
                                    $harga        = getHarga();

                                    $bytein= $ARRAY[0]['bytes-in'];
                                    $byteout=$ARRAY[0]['bytes-out'];
                                    $limitBytesTotal=$ARRAY[0]['limit-bytes-total'];
                                    $sisa = $limitBytesTotal - $bytein - $byteout;

                                    if($sisa < 0){
                                        $sisa = 0;
                                    }
                                    // perlu diubah
                                    //ubah 1500 ke harga per 1 GB wifi anda
                                    $dataGB = $value / $harga ;
                                    $dataB  = $dataGB * 1073741824 ;
                                    $limitAkhir = floor($limit_total + $dataB);
                                    
                                    
                                    // $text     = "Data $nama Berhasil dimasukan\n";
                                    // $text    .= "Total Kuota Awal ".formatBytes($limit_total)."\n";
                                    // $text    .= "Total Kuota Akhir ".formatBytes($limitAkhir)."\n";
                                    $text    = "Pembelian Berhasil\n";
                                    $text   .= "Kuota Semula ".formatBytes($sisa)."\n";
                                    $text   .= "Sisa Kuota Anda Saat ini ".formatBytes($limitAkhir - $ARRAY[0]['bytes-in'] - $ARRAY[0]['bytes-out'])."\n";
                                    $setuser = $API->comm("/ip/hotspot/user/set", [
                                       ".id" => "$id",
                                       "limit-bytes-total" => $limitAkhir,
                                    ]);
                                    $get   = $API->comm("/system/scheduler/print", ["?name" => $nama, ]);
                                    if(empty($get)){
                                        $text .= "Masa Aktif Selamanya\n";
                                    }else{
                                        $idschedul      = $get[0]['.id'];
                                        $data    = $get[0]['interval'];
                                        $hasil   = explode("w",$data);
                                        $week    = $hasil[0];
                                        $day     = explode("d",$hasil[1])[0];
                
                                        $hari    = ($week * 7) + $day;
                                        // perlu diubah
                                        /* ubah 9999 ke batas minimal pembelian untuk menambah masa aktif
                                        disini saya mencontohkan 
                                        apabila user membeli dengan nilai diatas Rp. 9999 maka masa aktif voucer ditambah 30 hari
                                        jika user membeli voucer dengan nominal dibawah atau sama dengan Rp. 9999 maka masa aktif voucer bertamabah 7 hari
                                        */
                                        if($value > 9999){
                                            $extday = 30;
                                        }else{
                                            $extday = 7;
                                        }
                                        $interval = ($hari + $extday)."d";
                                        $add_exp = $API->comm("/system/scheduler/set",[
                                           ".id" => $idschedul,
                                           "interval" => $interval
                                        ]);
                                        $getexp   = $API->comm("/system/scheduler/print", ["?name" => $nama, ]);
                                        $exp = $getexp[0]['next-run'];
                                        $text .= "Aktif hingga ".$exp."\n";
                                    }
                                    if(strpos($dnsname,'http://') !== false) {
                                        $url = "$dnsname/login?username=$nama&password=$password";
                                    }else{
                                        $url = "http://$dnsname/login?username=$nama&password=$password";
                                    }
                                    $text   .= "Login klik link dibawah\n";
                                    $text   .= $url."\n\n";
                                    $text   .= "*catatan :* ";
                                    $text   .= "```Jika link tidak bisa di klik, silahkan simpan nomer ini terlebih dahulu.\n";
                                    $text   .= "Akun hanya bisa digunakan untuk Handphone ini.jika membutuhkan untuk handphone lain hubungi admin.```\n\n";
                                    
                                    catatHutang($nama,numberOnly($nomer),$value);
                                    simpanPenjualan($nama,$value);
                                    $hutang = rupiah(cekHutang($nama));
                                    $text .= "total tagihan anda saat ini *$hutang*";
                                }

                            }
                        }else{
                            $text = "tidak dapat terhubung ke server";
                            $err = "user $nama gagal membeli voucer";
                            sendMassage($nomerAdmin,$err);
                        }
                    }else{
                        $text = "nominal pembelian paling kecil Rp. 1000";
                    }
                }else{
                    $text = "nominal hanya bisa di isi dengan angka";
                }
            }
        }
        break;
        
        //cek detail tagihan
    case '!tagihan':
    case 'tagihan':
    case '!hutang':
    case 'hutang':
        $nama = getName($nomerOnly);
        $detail = getHutang($nama);
        if(empty($detail)){
            $text   = "Selamat anda tidak memiliki tagihan";
        }else {
            $text = "Detail tagihan anda";
            foreach ($detail as $key => $value) {
                $text   .= "\n\nNama : ".$value['nama']."\n";
                //$text   .= "Tanggal : ".$value['tanggal']."\n";
                //$text   .= "Nominal : ".rupiah($value['nominal'])."\n";
                $keterangan = $value['keterangan'];
                $tanggal    = $value['tanggal'];
                if($keterangan == 'bulanan'){
                    $tanggal = $value['tanggal'];
                    $bulan = explode('-',$tanggal);
                    $bulan = getBulan($bulan[1]);
                    $text   .= "Internet Bulan *$bulan* Senilai ".rupiah($value['nominal']);
                }else {
                    $text   .= "Pembelian ".$value['keterangan']." Pada hari *".$value['tanggal']."* Senilai ".rupiah($value['nominal']);
                }
            }
            $text   .= "\n\nTotal tagihan anda ".rupiah(cekHutang($nama));
        }
        break;

        //cek limit payleter
    case '!limit':
    case 'limit':
        $nama       = getName(numberOnly($nomer));
        $limit      = getLimit($nama);
        $hutang     = cekHutang($nama);
        $sisaLimit  = $limit - $hutang;
        $text    = "Anda memiliki limit total limit ".rupiah($limit)."\n";
        $text   .= "Sisa limit anda senilai ".rupiah($sisaLimit);
        break;
    case '!daftar':
    case 'daftar':
    case '/daftar':
        //cek input
        $jumlah = count(explode(' ',$value));
        if($jumlah == 2 ){
            list($nama,$password) = explode(' ',$value);
            if(empty(getName($nomerOnly))){
                $hasil = simpan_data_client($nama,$password,$nomerOnly);
                if($hasil > 0 ){
                    $text    = "Pendaftaran berhasil\n";
                    $text   .= "Detail akun anda\n";
                    $text   .= "Nama : $nama\n";
                    $text   .= "Password : $password\n";
                    $text   .= "Nomer Whatsapp : $nomerOnly\n";
                    $text   .= "Limit PayLeter : Rp.5000";

                }else{
                    $text   = "Pendaftaran gagal, nama sudah digunakan\ngunakan nama lain atau hubungi admin jika masih terjadi masalah";
                    $err = "user $nama gagal mendaftar";
                    sendMessage($nomerAdmin,$err);
                }
            }else{
                $text = "Maaf Anda sudah terdaftar";
            }
        }else{
            $text  = "format yang anda masukan salah\n";
            $text .= "CONTOH : \n";
            $text .= "!daftar nama password\n";
            $text .= "nama hanya boleh 1 suku kata tanpa spasi, huruf besar kecil berpengaruh";
        }
        break;
    case '!usage':
    case 'quota':
    case 'kuota':
    case 'sisa':
        $nama   = getName($nomerOnly);
        $API = new routeros_api();
        if($API->connect($mkIp,$mkUsername,$mkPassword,$mkPort)){
            $ARRAY = $API->comm("/ip/hotspot/user/print",["?name" => $nama, ]);
            if(empty($ARRAY)){
                $text = "Akun tidak ditemukan, silahkam membeli voucer terlebih dahulu";
            }else{
                $limit_total   = $ARRAY[0]['limit-bytes-total'];
                $id            = $ARRAY[0]['.id'];
                $password      = $ARRAY[0]['password'];

                $bytein= $ARRAY[0]['bytes-in'];
                $byteout=$ARRAY[0]['bytes-out'];
                $sisa = $limit_total - $bytein - $byteout;
                $text    = "Username : $nama\n";
                $text   .= "Password : $password\n";
                $text   .= "Sisa kuota : ".formatBytes($sisa)."\n";
                if(strpos($dnsname,'http://') !== false) {
                    $url = "$dnsname/login?username=$nama&password=$password";
                }else{
                    $url = "http://$dnsname/login?username=$nama&password=$password";
                }
                $text   .= "Link Login : \n";
                $text   .= $url;
            }
        }else {
            $text = "Tidak dapat terhubung ke server, coba beberapa saat lagi";
        }
        break;

    case 'oke':
    case 'ok':
    case 'sip':
    case 'okey':
        $text   = "👍";
        break;

    case 'suwun':
    case 'nuwun':
    case 'swn':
        $text = "Sami sami";
        break;

    case 'makasih':
    case 'trim':
    case 'mksh':
    case 'terimakasih':
        $text = "Sama Sama";
        break;

    default:
        $text  = "```Maaf saya tidak paham apa yang anda maksud.\n";
        $text .= "Ketik atau balas``` *!info* ```untuk menampilkan daftar perintah.\n";
        $text .= "Pesan dibalas secara otomatis.```";
        break;
}

$hasil = sendMessage($nomer,$text);
var_dump($info);
?>
