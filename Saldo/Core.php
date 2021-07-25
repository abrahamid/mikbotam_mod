<?php
   //=====================================================START====================//

   /*

   Core.php
   Edit hanya textnya saja
    */

   //=====================================================START SCRIPT====================//

   date_default_timezone_set('Asia/Jakarta');
   include 'src/FrameBot.php';
   require_once '../config/system.conn.php';
   $mkbot = new FrameBot($token, $usernamebot);
   require_once '../config/system.byte.php';
   require_once '../Api/routeros_api.class.php';
   require_once 'src/tambahan.php';

   //Any commands akan di cegah dengan ini jika  perlu silahakan dihapus /* dan  */

   
   $mkbot->cmd('*', 'Maaf Saya tidak tahu apa yang anda maksud, ketik /help untuk menampilkan semua perintah');

   //Start commands
   $mkbot->cmd('/start|/Start', function () {
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      if (has($idtelegram) == false) {

         $text = "";
         //Ubah text dibawah ini untuk user yang belum terdaftar
         // GANTI NAMA SEBUTAN
         $text    = "Selamat datang @$nametelegram\nSilahkan Daftar Terlebih Dahulu Dengan Menulis /daftar ";
         $options = [
            'parse_mode' => 'html'
         ];
         return Bot::sendMessage($text, $options);
      } else {
         $text = "";

         //ubah text ini untuk user yang sudah terdaftar

         $text = "Hai @$nametelegram ,\nGunakan /help untuk menampilkan informasi ";
      }

      $options = [
         'parse_mode' => 'html'
      ];

      return Bot::sendMessage($text, $options);
   });   

   //daftar commands
   $mkbot->cmd('/daftar', function () {
      include ('../config/system.conn.php');
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];

      Bot::sendChatAction('typing');
      $ids = $info['chat']['id'];

      if (empty($nametelegram)) {
         $text = 'Maaf Akun Telegram anda belum terpasang username silahkan pasang terlebih dahulu username anda';
      } else {

         if (has($idtelegram) == false) {
            $cek = daftar($idtelegram, $nametelegram);

            if (empty($cek)) {
               $text .= "Mohon Maaf system kami mengalami gangguan silahkan hubungi Adminstator untuk reservasi layanan ini\n";
            } else {
               $text .= "<code>   Customer ID $idtelegram   </code>\n";
               $text .= "<code>========================</code>\n";
               $text .= "<code>  ID User  :</code> <code>$idtelegram</code>\n";
               $text .= "<code>  Username :</code> @$nametelegram\n";
               $text .= "<code>  Status   : Terdaftar </code>\n";
               $text .= "<code>========================</code>\n";
               $text .= "Silahkan Isi saldo anda Di outlet kami 😊 \n\n";
               $text .= "Terima kasih atas kepercayaan anda mengunakan layanan kami\n";
            }
         } else {
            $text .= "Maaf Anda sudah terdaftar dalam layanan ini\n\n";
            $text .= "<code>    Informasi ID Anda</code>\n";
            $text .= "<code>========================</code>\n";
            $text .= "<code>  ID User  : </code> <code>$idtelegram</code>\n";
            $text .= "<code>  Username : </code> @$nametelegram\n";
            $text .= "<code>  Status   : Terdaftar </code>\n";
            $text .= "<code>========================</code>\n";
         }
      }

      $options = [
         'parse_mode' => 'html'
      ];
      return Bot::sendMessage($text, $options);
   });   

   //help commands
   $mkbot->cmd('/help|!Help|info|Info', function () {
      include ('../config/system.conn.php');
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      if ($idtelegram == $id_own) {
         $text .= "/menu - Menu Voucher\n";
         $text .= "/daftar - daftar layanan\n";
         $text .= "/ceksaldo - ceksaldo layanan\n";
         $text .= "/cek id - Status user\n";
         $text .= "/qrcode - Terjemahkan QRCODE\n";
         $text .= "/deposit - Permintaan deposit\n\n\n";
         $text .= "Admin commands==============\n";
         $text .= "dbg - Debug message\n";
         $text .= "/daftarid - daftar user manual\n";
         $text .= "/topdown - mengurangi jumlah saldo user\n";
         $text .= "/topup - TOP UP SALDO USER\n";
         $text .= "/dbg - debug message\n";
         $text .= "/hotspot - Hotspot monitor\n";
         $text .= "/resource - resource router\n";
         $text .= "/netwatch - netwatch router\n";
         $text .= "/report - report mikhbotam\n";
         $text .= "?hs [username] - mencari detail user hotspot\n\n";
         $text .= "/modal [nominal] - mencatat modal yang dikeluarkan untuk pembelian paket internet ke isp\n\n";
         $text .= "/add [username] [password] [nominal] - untuk membuat user baru\n\n";
         $text .= "/masuk [username] [nominal] - untuk menambah kuota user, data sudah otomatis tercatat ke sistem\n\n";
         $text .= "/simpan [nama] [nominal] - untuk pencatatan manual pembelian user yang tidak dilakukan dengan perintah '/masuk'\n\n";
         $text .= "/hasil [bulan dala angka] - untuk menampilkan pendapatan pendapatan pada bulan yang tertulis, bisa dikosongi untuk menampilkan pendapatan bulan saat itu juga\n\n";
         $text .= "/hasildetail [bulan dalam angka] - menampilkan detail pembelian user pada bulan yang disebutkan\n\n";
      } else {
         $text .= "/menu - Menu Voucher\n";
         $text .= "/daftar - daftar layanan\n";
         $text .= "/ceksaldo - ceksaldo layanan\n";
         $text .= "/cek id - Status user\n";
         $text .= "/qrcode - Terjemahkan QRCODE\n";
         $text .= "/user - mencari keberadaan user hotspot. Contoh : /user wahyu\n";
         $text .= "/deposit - Permintaan deposit\n\n\n";
      }

      $optionss = ['parse_mode' => 'html', ];
      Bot::sendMessage($text, $optionss);
   });   

   //deposit commands
   $mkbot->cmd('/deposit|/request', function ($jumlah) {
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];

      $text         = "";

      if (!empty($jumlah)) {
         if (has($idtelegram) == false) {
            //jika user belum terdaftar
            $text = 'Anda tidak terdaftar Silahkan daftar terlebih dahulu ke admin atau /daftar sebelum request top up saldo';
         } else {
            if (preg_match('/^[0-9]+$/', $jumlah)) {
               if (strlen($jumlah) < 7) {
                  //jika user belum terdaftar
                  $text .= "@$usernamepelanggan Permintaan deposit  sebesar " . rupiah($cek) . " sudah kami terima, \nSilahkan kirimkan foto bukti pembayaran  disertai dengan Caption #konfirmasi deposit $cek\n\nKonfirmasi selambatnya 2 jam setelah permintaan deposit";
                  $textsend = "";
                  $textsend .= "<code>User :  </code>@$nametelegram \n";
                  $textsend .= "<code>ID   : </code> <code>$idtelegram </code>\n";
                  $textsend .= "<code>Request pengisian saldo </code>\n";
                  $textsend .= "<code>Nominal :" . rupiah($jumlah) . "</code>\n";
                  $textsend .= "<code>Silahkan tindak lanjut \nAtau Hubungi user </code> @$nametelegram \n\n";
                  $textsend .= "Dengan Menekan tombol dibawah ini saldo user otomatis terisi  ";

                  //-===================rubah texnya saja ya
                  $kirimpelangan = [
                     'chat_id' => $id_own,
                     'reply_markup' => json_encode([
                        'inline_keyboard' => [
                           [
                              ['text' => 'QUICK TOP UP', 'callback_data' => '12'],
                           ],
                           [
                              ['text' => '' . rupiah($jumlah) . '', 'callback_data' => 'tp|' . $jumlah . '|' . $idtelegram . '|' . $nametelegram . ''],
                           ],
                           [
                              ['text' => 'OR COSTUM', 'callback_data' => '12'],
                           ],
                           [
                              ['text' => '10000', 'callback_data' => 'tp|10000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '15000', 'callback_data' => 'tp|15000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '20000', 'callback_data' => 'tp|20000|' . $idtelegram . '|' . $nametelegram . ''],
                           ],
                           [

                              ['text' => '25000', 'callback_data' => 'tp|25000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '30000', 'callback_data' => 'tp|30000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '50000', 'callback_data' => 'tp|50000|' . $idtelegram . '|' . $nametelegram . ''],
                           ],
                           [

                              ['text' => '100000', 'callback_data' => 'tp|100000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '150000', 'callback_data' => 'tp|150000|' . $idtelegram . '|' . $nametelegram . ''],
                              ['text' => '200000', 'callback_data' => 'tp|200000|' . $idtelegram . '|' . $nametelegram . ''],
                           ],

                        ]]),
                     'parse_mode' => 'html'

                  ];

                  Bot::sendMessage($textsend, $kirimpelangan);
               } else {
                  $text = 'Maaf Maksimal deposit Top Up Rp 1.000.000.00';
               }
            } else {
               $text = 'Maaf input Nominal saldo hanya berupa angka saja';
            }
         }
      } else {
         $text .= "Perintah ini di gunakan untuk Request Deposit Saldo kepada Adminstator \n";
         $text .= "Anda dapat  Custom Request Deposit dengan cara \n";
         $text .= "/deposit (nominal)\n";
         $text .= "Contoh\n";
         $text .= "/deposit 1000 \n";
         $text .= "/deposit 70000 \n";
         $text .= "Atau menekan tombol dibawah ini \n";
         $options = [
            'reply_markup' => json_encode([
               'inline_keyboard' => [
                  [
                     ['text' => ' REQUEST ', 'callback_data' => '12'],
                  ],
                  [
                     ['text' => '10000', 'callback_data' => 'tps|10000'],
                     ['text' => '15000', 'callback_data' => 'tps|15000'],
                     ['text' => '20000', 'callback_data' => 'tps|20000'],
                  ],
                  [

                     ['text' => '25000', 'callback_data' => 'tps|25000'],
                     ['text' => '30000', 'callback_data' => 'tps|30000'],
                     ['text' => '50000', 'callback_data' => 'tps|50000'],
                  ],
                  [

                     ['text' => '100000', 'callback_data' => 'tps|100000'],
                     ['text' => '150000', 'callback_data' => 'tps|150000'],
                     ['text' => '200000', 'callback_data' => 'tps|200000'],
                  ],

               ]]),
            'parse_mode' => 'html'

         ];
      }

      return Bot::sendMessage($text, $options);
   });
   //cekid commands
   $mkbot->cmd('/cekid|/Cekid', function ($jumlah) {
      include ('../config/system.conn.php');
      $info   = bot::message();
      $iduser = $info['from']['id'];
      $msgid  = $info['message_id'];
      $name   = $info['from']['username'];
      $id     = $info['from']['id'];

      if (has($id) == false) {
         $text = "<code>    Informasi ID Anda</code>\n";
         $text .= "<code>========================</code>\n";
         $text .= "<code>  ID User  :</code> <code>$id</code>\n";
         $text .= "<code>  Username :</code> @$name\n";
         $text .= "<code>  Status   : - </code>\n";
         $text .= "<code>========================</code>\n";
      } else {
         $text = "<code>    Informasi ID Anda</code>\n";
         $text .= "<code>========================</code>\n";
         $text .= "<code>  ID User  : </code> <code>$id</code>\n";
         $text .= "<code>  Username : </code> @$name\n";
         $text .= "<code>  Status   : Terdaftar </code>\n";
         $text .= "<code>========================</code>\n";
      }

      $options = [
         'parse_mode' => 'html'
      ];
      return Bot::sendMessage($text, $options);
   });
   $mkbot->cmd('/sethutang',function($nama,$nominal,$keterangan){
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      if($idtelegram == $id_own){
         $detail = getDetailbyName($nama);
         if(empty($detail)){
            $text = "Nama tidak ditemukan di dalam database\nSilahkan masukan data ke dalam database terlebih dahulu";
         }else {
            $nomer   = $detail[0]['nomer'];
            $set = catatHutang($nama,$nomer,$nominal,$keterangan);
            if($set > 0){
               $text = "Hutang Senilai ".rupiah($nominal)." Atas nama $nama Berhasil Dicatat";
            }else {
               $text = "Hutang gagal dicatat";
            }
         }
      }else {
         $text = "Anda bukan admin";
      }

      return Bot::sendMessage($text);
   });

   $mkbot->cmd('/hutang',function($nama){
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      
      if($idtelegram == $id_own){
         if(empty($nama)){
            $ARRAY = query("SELECT DISTINCT nama FROM wa_hutang ORDER BY `wa_hutang`.`nama` asc");
            $text = "Daftar pemilik hutang\n\n";
            foreach ($ARRAY as $key => $value) {
               $nama = $value['nama'];
               $totalhutang   = getTotalHutang($nama);
               if($totalhutang > 0){
                  $text .= "$nama ".rupiah($totalhutang)."\n";
               }
            }
            $totalall = rupiah(query("SELECT SUM(IF(status='tempo',nominal,0)) AS hutang FROM wa_hutang")[0]['hutang']);
            $text .= "Total semua hutang $totalall";
         }else {         
            $data = getHutang($nama);
            if(empty($data)){
               $text = "$nama Tidak memiliki tagihan";
            }else {
               //$text = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
               $text = "Data Hutang $nama\n\n";
               foreach ($data as $key => $value) {
                  $text .= "Nama : ".$value['nama']."\n";
                  $text .= "Tanggal : ".$value['tanggal']."\n";
                  $text .= "Nominal : ".$value['nominal']."\n";
                  $text .= "/lunas".$value['id']."\n\n";
               }
               $hutang = rupiah(getTotalHutang($nama));
               $text .= "Sisa Hutang $hutang";
            }
         }
      }else {
         $text = "anda bukan admin";
      }
      return Bot::sendMessage($text);
   });

   $mkbot->cmd('/simpan',function($nama,$idr){
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      if($idtelegram == $id_own){
         $bulan = date("m");
         $hasil = simpandata($nama,$idr);
         if($hasil > "0"){
            $text = "pembelian atas nama $nama senilai $idr berhasil dimasukan\n";
            $text .= "Pemasukan Bulan ini : ".hasilpenjualan($bulan)."\n";
            $text .= "Total modal : ".totalmodal($bulan)."\n";
         }else{
            $text = "pembelian atas nama $nama senilai $idr gagal dimasukan";
         }
      }else{
         $text = "Anda bukan admin";
      }

      $options = [
         'parse_mode' => 'html'
      ];
      Bot::sendMessage($text, $options);
   });

   $mkbot->cmd('/hasil',function($bulan){
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      if(empty($bulan)){
         $bulan = date("m");
      }
      $jual = hasilpenjualan($bulan);
      $modal = totalmodal($bulan);
      $untung = duit($jual - $modal);
      $text = "penjualan bulan $bulan : ".duit($jual)."\n";
      $text .= "modal bulan ini : ".duit($modal)."\n";
      $text .= "keuntungan bulan ini $untung";

      return Bot::sendMessage($text);

   });
   
   $mkbot->cmd('/modal',function($idr){
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      $bulan = date("m");
      $data = modal($idr);
      if($data > 0){
      $text = "modal $idr berhasil dimasukan \n";
      $text .= "total modal : ".totalmodal($bulan)."\n";
      }else{
         $text = "data gagal disimpan";
      }
      return Bot::sendMessage($text);

   });

   //set harga per GB
   $mkbot->cmd('/setharga',function($idr){
      include ('../config/system.conn.php');
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      if($idtelegram == $id_own){
         $hargaAwal = getHarga();
         if(empty($idr)|| $idr <0 ){
            $text = "maaf nominal tidak boleh kosong atau lebih kecil dari 0";
         }elseif($hargaAwal == $idr){
            $text = "harga yang anda masukan sama dengan harga sebelumnya";
         }else{
            $result = setHarga($idr);
            if($result > 0){
               $text = "Harga berhasil diubah dari ".duit($hargaAwal)." / GB menjadi ".duit($idr)." /GB";
            }else{
               $text = "Koneksi ke database gagal";
            }
         }

      }else{
         $text = "Maaf Anda Bukan Admin";
      }
      $options = ['parse_mode' => 'html', ];
      Bot::sendMessage($text, $options);
   });
   
   $mkbot->cmd('/setno',function($nama,$password,$nomer,$mac){
      $info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      $table   = strtolower($table);
      $data    = strtolower($data);
      $nomer = wa($nomer);
      if(empty($nama)||empty($mac)||empty($nomer)){
         $text = "Data yang dimasuk belum lengkap, gunakan format /setno nama macaddress(kapital) nomerhp(0854xxxxx)";
      }else{
         if($nomer == "salah"){
            $text = "Format nomer hp salah, gunakan 08xxxx";
         }else{
            $data = simpan_data_client($nama,$password,$nomer,$mac);
            if($data > 0){
               $text = "Data ".$nama." berhasil disimpan";
            }else{
               $text = "Data ".$nama." gagal disimpan";
            }
         }
      }
      $options = [
         'parse_mode' => 'html'
      ];

      return Bot::sendMessage($text, $options);

   });
   //cek mac
   $mkbot->cmd('/mac', function($mac){
      include ('../config/system.conn.php');
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');
      if($idtelegram == $id_own){
         if(empty($mac)){
            $text = "harap masukan mac address terlebih dahulu\n";
         }else{
            $hasil = cari_mac($mac);
            if(empty($hasil)){
               $text = "data tidak ditemukan";
            }else{
               $hasil = $hasil[0];
               $text  = "Nama : ".$hasil['nama']."\n";
               $text .= "Nomer HP : <a href='https://wa.me/".$hasil['nomer']."'>".$hasil['nomer']."</a>\n";
               $text .= "Whatsapp Notifikasi : ".$hasil['keterangan']."\n";
            }
         }


      }else{
         $text = "MAAF ANDA BUKAN ADMIN";
      }
      $optionss = ['parse_mode' => 'html', ];
      Bot::sendMessage($text, $optionss);
   });


   //daftar manual khusus Administator
   $mkbot->cmd('/daftarid', function ($id, $name, $notlp, $saldo) {
      include ('../config/system.conn.php');
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      if ($idtelegram == $id_own) {
         if (empty($id) && empty($name) && empty($notlp) && empty($saldo)) {
            $text = "Maaf Format anda salah \n\nMohon masukan format dengan benar \n/daftar noid namauser notlpn saldo";
         } else {

            $lihat = lihatuser($id);

            if (empty($lihat)) {
               $text = daftarid($id, $name, $notlp, $saldo);
            } else {
               $text = "User sudah terdaftar periksa kembali ";
            }
         }
      } else {

         $text = "Maaf..! Aksess Hanya untuk Administator";
      }

      $options = [
         'parse_mode' => 'html'
      ];
      return Bot::sendMessage($text, $options);
   });
   //topdown khusus Administator
   $mkbot->cmd('/topdown', function ($id, $jumlahan) {
      $info       = bot::message();
      $msgid      = $info['message_id'];
      $name       = $info['from']['username'];
      $idtelegram = $info['from']['id'];
      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         if (!empty($id) && !empty($jumlahan)) {
            if (has($id) == false) {
               $text = 'Data id tidak terdaftar silahkan periksa kembali';
            } else {

               if (preg_match('/^[0-9]+$/', $jumlahan)) {
                  if (strlen($jumlahan) < 7) {
                     $topdown = topdown($id, $jumlahan);
                     $text    = "<code>     Informasi refund</code>\n";
                     $text .= "<code>========================</code>\n";
                     $text .= "<code>  ID User     : $id</code>\n";
                     $text .= "<code>  Saldo akhir : " . lihatsaldo($id) . "</code>\n";
                     $text .= "<code>Penarikan saldo berhasil</code>\n";
                  } else {
//=================>>> GANTI NOMINAL TOP DOWN
                     $text = 'Maaf Maksimal refund Rp 999.999.00';
                  }
               } else {
                  $text = 'Maaf input saldo hanya berupa angka saja';
               }
            }
         } else {
            $text = "Maaf format anda salah /topdown (id) (jumlah)";
         }
      } else {
         $text = "Maaf..! Aksess Hanya untuk Administator";
      }

      $optionss = ['parse_mode' => 'html', ];
      Bot::sendMessage($text, $optionss);
   });
   //topup khusus Administator
   $mkbot->cmd('/topup', function ($id, $jumlah) {

      $info       = bot::message();
      $msgid      = $info['message_id'];
      $name       = $info['from']['username'];
      $idtelegram = $info['from']['id'];
      Bot::sendChatAction('typing');
      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         if (!empty($id) && !empty($jumlah)) {
            if (has($id) == false) {
               $text = 'Data id tidak terdaftar silahkan periksa kembali';
            } else {

               if (preg_match('/^[0-9]+$/', $jumlah)) {
                  if (strlen($jumlah) < 7) {
                     $text = topupresseller($id, $name, $jumlah, $id_own);

                     $kirimpelangan = [
                        'chat_id' => $id,
                        'reply_markup' => json_encode([
                           'inline_keyboard' => [
                              [
                                 ['text' => '🔎 Beli Voucher', 'callback_data' => 'Menu'],
                                 ['text' => '📛 Promo Hot', 'callback_data' => 'informasi'],
                              ], ]]),
                        'parse_mode' => 'html'

                     ];
                     Bot::sendMessage($text, $kirimpelangan);
                  } else {
//==============>>>>>>ganti MAX TOP UP
                     $text = 'Maaf Maksimal Top Up Rp 999.999.00';
                  }
               } else {
                  $text = 'Maaf input saldo hanya berupa angka saja';
               }
            }
         } else {
            $text = "Maaf format anda salah /topup (id) (jumlah)";
         }
      } else {
         $text = "Maaf..! Aksess Hanya untuk Administator";
      }

      $options = [
         'parse_mode' => 'html'
      ];
      return Bot::sendMessage($text, $options);
   });
   //lihatsaldo commands
   $mkbot->cmd('/lihatsaldo|/ceksaldo', function ($jumlah) {
      include ('../config/system.conn.php');
      $info   = bot::message();
      $iduser = $info['from']['id'];
      $msgid  = $info['message_id'];
      $name   = $info['from']['username'];
      $id     = $info['from']['id'];
      $lihat  = lihatuser($id);
      $ids    = $info['chat']['id'];

      if (empty($lihat)) {
         $text = 'anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar';
      } else {
         $angka = lihatsaldo($id);
         $text  = "<code>      Informasi Saldo</code>\n";
         $text .= "<code>========================</code>\n";
         $text .= "<code>  ID User : $id</code>\n";
         $text .= "<code>  Name    : $name</code>\n";
         $text .= "<code>  Saldo   : " . rupiah($angka) . "</code>\n";
         $text .= "<code>========================</code>\n";
      }

      $options = [
         'parse_mode' => 'html'
      ];
      return Bot::sendMessage($text, $options);
   });
   //resource commands
   $mkbot->cmd('/resource|/Resource', function () {

      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         $API = new routeros_api();

         if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $jambu         = $API->comm("/system/health/print");
            $dhealth       = $jambu['0'];
            $ARRAY         = $API->comm("/system/resource/print");
            $jeruk         = $ARRAY['0'];
            $memperc       = ($jeruk['free-memory'] / $jeruk['total-memory']);
            $hddperc       = ($jeruk['free-hdd-space'] / $jeruk['total-hdd-space']);
            $mem           = ($memperc * 100);
            $hdd           = ($hddperc * 100);
            $sehat         = $dhealth['temperature'];
            $platform      = $jeruk['platform'];
            $board         = $jeruk['board-name'];
            $version       = $jeruk['version'];
            $architecture  = $jeruk['architecture-name'];
            $cpu           = $jeruk['cpu'];
            $cpuload       = $jeruk['cpu-load'];
            $uptime        = $jeruk['uptime'];
            $cpufreq       = $jeruk['cpu-frequency'];
            $cpucount      = $jeruk['cpu-count'];
            $memory        = formatBytes($jeruk['total-memory']);
            $fremem        = formatBytes($jeruk['free-memory']);
            $mempersen     = number_format($mem, 3);
            $hdd           = formatBytes($jeruk['total-hdd-space']);
            $frehdd        = formatBytes($jeruk['free-hdd-space']);
            $hddpersen     = number_format($hdd, 3);
            $sector        = $jeruk['write-sect-total'];
            $setelahreboot = $jeruk['write-sect-since-reboot'];
            $kerusakan     = $jeruk['bad-blocks'];
            $text .= "";
            $text .= "<b> Resource</b>  $sehat C\n";
            $text .= "<code>Boardname: $board</code>\n";
            $text .= "<code>Platform : $platform</code>\n";
            $text .= "<code>Uptime is: " . formatDTM($uptime) . "</code>\n";
            $text .= "<code>Cpu Load : $cpuload%</code>\n";
            $text .= "<code>Cpu type : $cpu</code>\n";
            $text .= "<code>Cpu Hz   : $cpufreq Mhz/$cpucount core</code>\n==========================\n";
            $text .= "<code>Free memory and memory \n$memory-$fremem/$mempersen %</code>\n==========================\n";
            $text .= "<code>Free disk and disk      \n$hdd-$frehdd/$hddpersen %</code>\n==========================\n";
            $text .= "<code>Since reboot, bad blocks \n$sector-$setelahreboot/$kerusakan%</code>\n==========================\n";
         }
      } else {
         $text = "Maaf..! Aksess Hanya untuk Adminstator";
      }

      $options = ['parse_mode' => 'html', ];
      Bot::sendMessage($text, $options);
   });
   //Hotspot commands khusus Adminstator
   $mkbot->cmd('!Hotspot|?hotspot|/hotspot|/Hotspot|!Hotspot', function ($user, $telo) {

      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         $API = new routeros_api();

         if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            if ($user == 'aktif') {
               if ($telo != "") {
                  $pepaya = $API->comm("/ip/hotspot/active/print", ["?server" => "" . $telo . ""]);
                  $anggur = count($pepaya);
                  $apel   = $API->comm("/ip/hotspot/active/print", ["count-only" => "", "?server" => "" . $telo . ""]);
               } else {
                  $pepaya = $API->comm("/ip/hotspot/active/print");
                  $anggur = count($pepaya);
                  $apel   = $API->comm("/ip/hotspot/active/print", ["count-only" => "", ]);
               }

               $text .= "User Aktif $apel item\n\n";

               for ($i = 0; $i < $anggur; $i++) {
                  $mangga    = $pepaya[$i];
                  $id        = $mangga['.id'];
                  $server    = $mangga['server'];
                  $user      = $mangga['user'];
                  $address   = $mangga['address'];
                  $mac       = $mangga['mac-address'];
                  $namaTerdaftar = namauser($mac);
                  $uptime    = $mangga['uptime'];
                  $usesstime = $mangga['session-time-left'];
                  $bytesi    = formatBytes($mangga['bytes-in'], 2);
                  $byteso    = formatBytes($mangga['bytes-out'], 2);
                  $sisaKuota = $mangga['limit-bytes-total-nice'];
                  $loginby   = $mangga['login-by'];
                  $comment   = $mangga['comment'];
                  $text .= "";
                  $text .= "👤 User aktif\n";
                  $text .= "┠ ID :$id\n";
                  $text .= "┠ User  : <code>$user</code>\n";
                  $text .= "┠ Nama Terdaftar  : $namaTerdaftar\n";
                  $text .= "┠ IP    : $address\n";
                  $text .= "┠ MAC : <code>$mac</code>\n";
                  $text .= "┠ Uptime : $uptime\n";
                  $text .= "┠ Byte IN      : $bytesi\n";
                  $text .= "┠ Byte OUT   : $byteso\n";
                  $text .= "┠ Sisa Kuota : $sisaKuota\n";
                  $text .= "┠ Commnet : $comment\n";
                  $text .= "┠ Sesion  : $usesstime\n";
                  $text .= "┗ Login    : $loginby\n \n";
                  $text .= "/see_$server\n \n";
               }
               $options = [
                  'parse_mode' => 'html'
               ];

               $arr2       = str_split($text, 4000);
               $amount_gen = count($arr2);

               for ($i = 0; $i < $amount_gen; $i++) {
                  $texta = $arr2[$i];
                  Bot::sendMessage($texta, $options);
               }
            } elseif ($user == 'user') {
               $ARRAY = $API->comm("/ip/hotspot/user/print");
               $num   = count($ARRAY);
               $text  = "Total $num User\n\n";

               for ($i = 0; $i < $num; $i++) {
                  $no     = $i;
                  $data   = $ARRAY[$i]['.id'];
                  $dataid = str_replace('*', 'id', $data);
                  $server = $ARRAY[$i]['server'];
                  $name   = $ARRAY[$i]['name'];
                  $data3  = $ARRAY[$i]['password'];
                  $data4  = $ARRAY[$i]['mac-address'];
                  $data5  = $ARRAY[$i]['profile'];
                  $data6  = $ARRAY[$i]['limit-uptime'];
                  $text .= "";
                  $text .= "👥 ($dataid)\n";
                  $text .= "┣Nama : $name\n";
                  $text .= "┣password : $data3 \n";
                  $text .= "┣mac : $data4\n";
                  $text .= "┣Profil : $data5\n\n";
                  $text .= "┗RemoveNow User /rEm0v$dataid\n\n";
               }
               $options = [
                  'parse_mode' => 'html'
               ];

               $arr2       = str_split($text, 4000);
               $amount_gen = count($arr2);

               for ($i = 0; $i < $amount_gen; $i++) {
                  $texta = $arr2[$i];

                  Bot::sendMessage($texta, $options);
               }
            } else {
               $text .= "";
               $text = "User list or aktif\n";
               $text .= "Filter by server\n";
               $serverhot = $API->comm('/ip/hotspot/print');

               foreach ($serverhot as $index => $jambu) {
                  $sapubasah      = str_replace('-', '0', $jambu['name']);
                  $sapubasahbasah = str_replace(' ', '11', $sapubasah);

                  $text .= "/see_" . $sapubasahbasah . "\n";
               }

               $keyboard    = [['!Hotspot user', '!Hotspot aktif'], ['!Menu', '!Help'], ['!Hide'], ];
               $replyMarkup = ['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true, 'selective' => true];
               $options     = [
                  'reply' => true,
                  'reply_markup' => json_encode($replyMarkup),
               ];
               Bot::sendMessage($text, $options);
            }
         } else {
            $text    = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
            $options = [
               'reply' => true,
            ];
            Bot::sendMessage($text, $options);
         }
      } else {
         $denid = "Maaf..! Aksess Hanya untuk Administator";
         Bot::sendMessage($denid);
      }
   });

   //User commands khusus Administator
   $mkbot->cmd('?hs|!User|?user|!user|/user|', function ($name) {

      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         $API = new routeros_api();

         if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $ARRAY = $API->comm("/ip/hotspot/user/print", ["?name" => $name, ]);
            $get   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);

            if (empty($ARRAY)) {
               $texta = "User tidak ditemukan...";
            } else {

               foreach ($ARRAY as $index => $baris) {
                  $text = "";
                  $text .= "Hotspot Client";
                  $text .= "\n=======================\n";
                  $text .= "Nama     :" . $baris['name'] . "\n";
                  $text .= "Password :" . $baris['password'] . "\n";
                  $text .= "Nama Terdaftar  :" . namauser($baris['mac-address'])."\n";
                  $text .= "Uptime   :" . formatDTM($baris['uptime']) . "\n";
                  $text .= "Upload   :" . formatBytes($baris['bytes-in']) . "\n";
                  $text .= "Downlaod :" . formatBytes($baris['bytes-out']) . "\n";
                  $text .= "Sisa Kuota :" . formatBytes(($baris['limit-bytes-total'])-($baris['bytes-in'])-($baris['bytes-out']))."\n";
                  $text .= "Profil   :" . $baris['profile'] . "\n";
                  if ($baris['profile'] != ("30day" || "1day" || "7day"))
                 {
                   $text .= "Aktif Sampai :". $baris['comment']."\n";
                 }
                  $data   = $baris['.id'];
                  $dataid = str_replace('*', 'id', $data);
               }

               foreach ($get as $index => $baris) {
                  $experid = "";
                  $experid .= "Start-time : <b>" . $baris['start-date'] . " " . $baris['start-time'] . "</b>\n";
                  $experid .= "Interval   : <b>" . $baris['interval'] . "</b>\n";
                  $experid .= "expired    : <b>" . $baris['next-run'] . "</b>\n<code>=======================</code>\n";
               }

               $texta = "<code>" . $text . "</code>$experid\nRemove User /rEm0v$dataid\n\n";
            }
         }

         $options = ['parse_mode' => 'html', ];
         Bot::sendMessage($texta, $options);
      } else {
        $API = new routeros_api();

        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
           $ARRAY = $API->comm("/ip/hotspot/user/print", ["?name" => $name, ]);
           $get   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);


           if (empty($ARRAY)) {
              $texta = "User tidak ditemukan...";
           } else {

              foreach ($ARRAY as $index => $baris) {
                 $text = "";
                 $text .= "Hotspot Client";
                 $text .= "\n=======================\n";
                 $text .= "Nama     :" . $baris['name'] . "\n";
                 // $text .= "Password :" . $baris['password'] . "\n";
                 $text .= "Uptime   :" . formatDTM($baris['uptime']) . "\n";
                 $text .= "Upload   :" . formatBytes($baris['bytes-in']) . "\n";
                 $text .= "Downlaod :" . formatBytes($baris['bytes-out']) . "\n";
                 $text .= "Sisa Kuota :" . formatBytes(($baris['limit-bytes-total'])-($baris['bytes-in'])-($baris['bytes-out']))."\n";
                 $text .= "Profil   :" . $baris['profile'] . "\n";
                 if ($baris['profile'] != "30day" || "1day" || "7day")
                 {
                   $text .= "Aktif Sampai :". $baris['comment']."\n";
                 }
                 $data   = $baris['.id'];
                 $dataid = str_replace('*', 'id', $data);
              }

              foreach ($get as $index => $baris) {
                 $experid = "";
                 $experid .= "Waktu Dibuat : <b>" . $baris['start-date'] . " " . $baris['start-time'] . "</b>\n";
                 $experid .= "Masa Aktif   : <b>" . $baris['interval'] . "</b>\n";
                 $experid .= "Aktif Sampai    : <b>" . $baris['next-run'] . "</b>\n<code>=======================</code>\n";
              }

              $texta = "<code>" . $text . "</code>$experid\n\n";
           }
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($texta, $options);
         // $denid = "Maaf..! Aksess Hanya untuk Administator";
         // Bot::sendMessage($denid);
      }
   });


   $mkbot->cmd('/masuk',function($name,$idr,$nomer){
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');
         if($idtelegram == $id_own){
            if(empty($name)||empty($idr)){
               $text  = "Format yang anda masukan salah\n";
               $text .= "Contoh : /masuk [nama][nominal]";
            }else{
               $API = new routeros_api();
               if($API->connect($mikrotik_ip,$mikrotik_username,$mikrotik_password,$mikrotik_port)){
                  $ARRAY = $API->comm("/ip/hotspot/user/print",["?name" => $name, ]);
                  if(empty($ARRAY)){
                     $text = "$name tidak ditemukan";
                  }else{
                     $limit_total   = $ARRAY[0]['limit-bytes-total'];
                     $id            = $ARRAY[0]['.id'];
                     $password      = $ARRAY[0]['password'];
                     $harga        = getHarga();
                     // perlu diubah
                     //ubah 1500 ke harga per 1 GB wifi anda
                     $dataGB = $idr / $harga ;
                     $dataB  = $dataGB * 1073741824 ;
                     $limitAkhir = floor($limit_total + $dataB);
                     
                     
                     $text     = "Data $name Berhasil dimasukan\n";
                     $text    .= "Total Kuota Awal ".formatBytes($limit_total)."\n";
                     $text    .= "Total Kuota Akhir ".formatBytes($limitAkhir)."\n";
                     $text    .= "Sisa Kuota ".formatBytes($limitAkhir - $ARRAY[0]['bytes-in'] - $ARRAY[0]['bytes-out'])."\n";
                     $setuser = $API->comm("/ip/hotspot/user/set", [
                        ".id" => "$id",
                        "limit-bytes-total" => $limitAkhir,
                     ]);

                     $get   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);
                     if(empty($get)){
                        $text .= "Masa Aktif Selamanya atau voucer belum digunakan";
                        $options = [
                           'parse_mode'   => 'html',
                           'reply'        => true
                        ];
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
                        if($idr > 9999){
                           $extday = 30;
                        }else{
                           $extday = 7;
                        }
                        $interval = ($hari + $extday)."d";
                        $add_exp = $API->comm("/system/scheduler/set",[
                           ".id" => $idschedul,
                           "interval" => $interval
                        ]);
                        $getexp   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);
                        $exp = $getexp[0]['next-run'];
                        
                        if(strpos($dnsname,'http://') !== false) {
                           $url = "$dnsname/login?username=$name&password=$password";
                        }else{
                           $url = "http://$dnsname/login?username=$name&password=$password";
                        }
                        $keyboard[] = [
                           ['text' => 'Go to Login', 'url' => $url],
                        ];
                        
                        $text .= "Aktif hingga ".$exp."\n";
                        
                        if(empty($nomer)){
                           echo 'nomer tidak diset';
                        }else{
                           $pesan  = $text;
                           $pesan .= $url;
                           send_whatsapp($nomer,$pesan);
                        }

                        $options = [
                           'parse_mode'   => 'html',
                           'reply_markup' => ['inline_keyboard' => $keyboard],
                           'reply'        => true
                        ];
                     }
                     simpandata($name,$idr);
                     $detail = getDetailbyName($nama);
                  }
               }else{
                  $text = "Tidak dapat terhubung ke mikrotik";
               }
            }
         }else{
            $text = "Maaf anda bukan administrator";
         }
         Bot::sendMessage($text,$options);
   });



   // $mkbot->cmd('/com',function($name,$idr){
   //    $info         = bot::message();
   //    $msgid        = $info['message_id'];
   //    $nametelegram = $info['from']['username'];
   //    $idtelegram   = $info['from']['id'];
   //    Bot::sendChatAction('typing');

   //    include ('../config/system.conn.php');
   //       if($idtelegram == $id_own){
   //          if(empty($name)||empty($idr)){
   //             $text  = "Format yang anda masukan salah\n";
   //             $text .= "Contoh : /masuk [nama][nominal]";
   //          }else{
   //             $API = new routeros_api();
   //             if($API->connect($mikrotik_ip,$mikrotik_username,$mikrotik_password,$mikrotik_port)){
   //                $ARRAY = $API->comm("/ip/hotspot/user/print",["?comment" => $name, ]);
   //                if(empty($ARRAY)){
   //                   $text = "$name tidak ditemukan";
   //                }else{
   //                   $limit_total   = $ARRAY[0]['limit-bytes-total'];
   //                   $id            = $ARRAY[0]['.id'];
   //                   $password      = $ARRAY[0]['password'];
   //                   // perlu diubah
   //                   //ubah 1500 ke harga per 1 GB wifi anda
   //                   $dataGB = $idr / 1500 ;
   //                   $dataB  = $dataGB * 1073741824 ;
   //                   $limitAkhir = floor($limit_total + $dataB);
                     
                     
   //                   $text     = "Data $name Berhasil dimasukan\n";
   //                   $text    .= "Total Kuota Awal ".formatBytes($limit_total)."\n";
   //                   $text    .= "Total Kuota Akhir ".formatBytes($limitAkhir)."\n";
   //                   $text    .= "Sisa Kuota ".formatBytes($limitAkhir - $ARRAY[0]['bytes-in'] - $ARRAY[0]['bytes-out'])."\n";
   //                   $setuser = $API->comm("/ip/hotspot/user/set", [
   //                      ".id" => "$id",
   //                      "limit-bytes-total" => $limitAkhir,
   //                   ]);

   //                   $get   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);
   //                   if(empty($get)){
   //                      $text .= "Masa Aktif Selamanya atau voucer belum digunakan";
   //                      $options = [
   //                         'parse_mode'   => 'html',
   //                         'reply'        => true
   //                      ];
   //                   }else{
   //                      $idschedul      = $get[0]['.id'];
   //                      $data    = $get[0]['interval'];
   //                      $hasil   = explode("w",$data);
   //                      $week    = $hasil[0];
   //                      $day     = explode("d",$hasil[1])[0];

   //                      $hari    = ($week * 7) + $day;
   //                      // perlu diubah
   //                      /* ubah 9999 ke batas minimal pembelian untuk menambah masa aktif
   //                      disini saya mencontohkan 
   //                      apabila user membeli dengan nilai diatas Rp. 9999 maka masa aktif voucer ditambah 30 hari
   //                      jika user membeli voucer dengan nominal dibawah atau sama dengan Rp. 9999 maka masa aktif voucer bertamabah 7 hari
   //                      */
   //                      if($idr > 9999){
   //                         $extday = 30;
   //                      }else{
   //                         $extday = 7;
   //                      }
   //                      $interval = ($hari + $extday)."d";
   //                      $add_exp = $API->comm("/system/scheduler/set",[
   //                         ".id" => $idschedul,
   //                         "interval" => $interval
   //                      ]);
   //                      $getexp   = $API->comm("/system/scheduler/print", ["?name" => $name, ]);
   //                      $exp = $getexp[0]['next-run'];
                        
   //                      if(strpos($dnsname,'http://') !== false) {
   //                         $url = "$dnsname/login?username=$name&password=$password";
   //                      }else{
   //                         $url = "http://$dnsname/login?username=$name&password=$password";
   //                      }
   //                      $keyboard[] = [
   //                         ['text' => 'Go to Login', 'url' => $url],
   //                      ];
                        
   //                      $text .= "Aktif hingga ".$exp;
                        
   //                      $options = [
   //                         'parse_mode'   => 'html',
   //                         'reply_markup' => ['inline_keyboard' => $keyboard],
   //                         'reply'        => true
   //                      ];
   //                   }
   //                   simpandata($name,$idr);
   //                }
   //             }else{
   //                $text = "Tidak dapat terhubung ke mikrotik";
   //             }
   //          }
   //       }else{
   //          $text = "Maaf anda bukan administrator";
   //       }
   //       Bot::sendMessage($text,$options);
   // });


   $mkbot->cmd('/add',function($name,$password,$idr){
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');
      if($idtelegram == $id_own){
         $formatperintah = "\ngunakan perintah seperti contoh dibawah\n /add [nama][password][nominal]";
         if(empty($name)){
            $text = "nama tidak boleh kososng$formatperintah";
         }elseif(empty($password)){
            $text = "password tidak boleh kosong$formatperintah";
         }elseif(empty($idr)){
            $text = "nominal tidak boleh kosong$formatperintah";
         }else{
            $API = new routeros_api();
            if($API->connect($mikrotik_ip,$mikrotik_username,$mikrotik_password,$mikrotik_port)){
               $ARRAY = $API->comm("/ip/hotspot/user/print",["?name" => $name ]);
               if(empty($ARRAY)){
                  // perlu diubah
                  $harga = getHarga();
                  $dataGB = $idr / $harga;
                  $dataB  = $dataGB * 1073741824 ;
                  //ubah profile sesuai dengan profile yang anda gunakan, saya rekomendasikan membuat profile melalui mikbotam
                  $profile = "30day";

                  $add_user_api = $API->comm("/ip/hotspot/user/add", [
                     "server" => "all",
                     "profile" => $profile,
                     "name" => $name,
                     "limit-bytes-total" => $dataB,
                     "password" => $password,
                     "comment" => "| ID : $usernamepelanggan | voc : Rp.$idr | tgl : " . date('d-m-Y') . " | MIKBOTAM MOD |",
                  ]);

                  $caption  = "Username : $name\nPassword : $password\nJumlah kuota : ".number_format($dataGB,2)." GB\n";
                  //disini saya menngunakan profile "30day" sehingga saat voucer dibuat akan menampilkan masa aktif "30day" seperti nama profile
                  $caption .= "Masa Aktif : $profile";

                  if(strpos($dnsname,'http://') !== false) {
                     $url = "$dnsname/login?username=$name&password=$password";
                  }else{
                     $url = "http://$dnsname/login?username=$name&password=$password";
                  }
                  $qrcode     = 'http://qrickit.com/api/qr.php?d=' . urlencode($url) . '&addtext=' . urlencode($Name_router) . '&txtcolor=000000&fgdcolor=000000&bgdcolor=FFFFFF&qrsize=500';
                  $keyboard[] = [
                     ['text' => 'Go to Login', 'url' => $url],
                  ];
                  simpandata($name,$idr);
                  $options = [
                     'chat_id' => $chatidtele,
                     'caption' => $caption,
                     'reply_markup' => ['inline_keyboard' => $keyboard],
                     'parse_mode' => 'html'
                  ];
                  Bot::sendPhoto($qrcode,$options);
               }else{
                  $text = "user $name sudah digunakan, gunakan nama lain";
               }

            }else{
               $text = "Tidak dapat terhubung ke mikrotik";
            }
         }         
      }else{
         $text = "Maaf anda bukan administrator";
      }

      $options = [
         'parse_mode' => 'html',
         'reply' => true
      ];
      Bot::sendMessage($text,$options);
   });

   $mkbot->cmd('/hasildetail', function($bulan){
      $info   = bot::message();
      $id     = $info['chat']['id'];
      $idtelegram = $info['from']['id'];
      $msgid  = $info['message_id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');

      if($idtelegram == $id_own){
         $year = date('Y');
         if(empty($bulan)){
            $bulan = date('m');
            $year  = date('Y');
         }
         $ARRAY = query("SELECT DISTINCT nama FROM st_reportmanual ORDER BY `st_reportmanual`.`nama` DESC");
         $text = "Laporan bulan ini\n";
         foreach($ARRAY as $k => $v){
            $nama = $v['nama'];
            $totalpembelian = query("SELECT SUM(idr) FROM st_reportmanual WHERE nama='$nama' && tanggal LIKE '%$year-$bulan%'")[0]['SUM(idr)'];
            //$hasil = json_encode($totalpembelian, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if($totalpembelian > 0){
               $text .= "$nama ".duit($totalpembelian)."\n";
            }

         }
         $text .= "\n";
         $jual = hasilpenjualan($bulan);
         $modal = totalmodal($bulan);
         $untung = duit($jual - $modal);
         $text .= "penjualan bulan $bulan : ".duit($jual)."\n";
         $text .= "modal bulan ini : ".duit($modal)."\n";
         $text .= "keuntungan bulan ini $untung";
      //$text .= json_encode($ARRAY, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      }else{
         $text = "Maaf anda bukan administrator";
      }
      Bot::sendMessage($text,$options);
   });

   //report commands khusus Administator
   $mkbot->cmd('/report', function ($name) {
      include ('../config/system.conn.php');
      $info   = bot::message();
      $id     = $info['chat']['id'];
      $idtelegram = $info['from']['id'];
      $msgid  = $info['message_id'];
      Bot::sendChatAction('typing');

      if ($idtelegram == $id_own) {
         $text .= "<code>      " . date('d-m-Y') . "</code>\n";
         $text .= "=========================\n";
         $text .= "Total Voucher Bulan ini\n";
         $text .= "" . countvoucher() . " Voucher\n";
         $text .= "=========================\n";
         $text .= "Top up Debit Bulan ini\n";
         $text .= "" . rupiah(getcounttopup()) . "\n";
         $text .= "=========================\n";
         $text .= "Mutasi Voucher Bulan ini\n";
         $text .= "" . rupiah(estimasidata()) . "\n";
         $text .= "=========================\n";
         $text .= "User + Bulan ini\n";
         $text .= "+ " . countuser() . " User\n";
         $text .= "=========================\n";
      } else {
         $text = "Maaf..! Aksess Hanya untuk Administator";
      }

      $options = [
         'parse_mode' => 'html',
      ];
      Bot::sendMessage($text, $options);
   });
   //netwatch commands khusus Administator
   $mkbot->cmd('/netwatch|/Netwatch', function () {
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      Bot::sendChatAction('typing');

      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         $API = new routeros_api();

         if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $ARRAY = $API->comm("/tool/netwatch/print");
            $num   = count($ARRAY);
            $text .= "Daftar Host Netwatch $num\n\n";

            for ($i = 0; $i < $num; $i++) {
               $no       = $i + 1;
               $host     = $ARRAY[$i]['host'];
               $interval = $ARRAY[$i]['interval'];
               $timeout  = $ARRAY[$i]['timeout'];
               $status   = $ARRAY[$i]['status'];
               $since    = $ARRAY[$i]['since'];
               $text .= "📝 Netwatch$no\n";
               $text .= "┠ Host : $host \n";

               if ($status == "up") {
                  $text .= "┠ Status : ✔ UP \n";
               } else {
                  $text .= "┠ Status : ⚠ Down \n";
               }

               $text .= "┗ Since : $since \n\n";
            }
         } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
         }

         $arr2       = str_split($text, 4000);
         $amount_gen = count($arr2);

         for ($i = 0; $i < $amount_gen; $i++) {
            $texta   = $arr2[$i];
            $options = ['parse_mode' => 'html'];
            Bot::sendMessage($arr2[$i], $options);
         }
      } else {
         $text = "Maaf..! Aksess Hanya untuk Administator";
         Bot::sendMessage($text);
      }
   });
   //debug message semua
   $mkbot->cmd('dbg', function ($pesan) {
      $info    = bot::message();
      $id      = $info['chat']['id'];
      $text    = "<code>" . json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</code>";
      $options = ['parse_mode' => 'html', ];
      return Bot::sendMessage($text, $options);
   });
   //qrcode terjemah qrcode
   $mkbot->cmd('/qrcode', function () {
      include ('../config/system.conn.php');
      $info        = bot::message();
      $ambilgambar = $info['reply_to_message']['photo'][0]['file_id'];

      if (empty($ambilgambar)) {
         $text = "Balas Gambar/foto QRcode";
         Bot::sendMessage($text);
      } else {
         $cek           = Bot::getFile($ambilgambar);
         $hasilkirimaaa = json_decode($cek, true);
         $hasilurl      = $hasilkirimaaa['result']['file_path'];
         $urlkirim      = 'http://api.qrserver.com/v1/read-qr-code/?fileurl=https://api.telegram.org/file/bot' . $token . '/' . $hasilurl;
         $hasilurla     = file_get_contents($urlkirim);
         $hasilkirim    = json_decode($hasilurla, true);
         $terjemah      = "Hasil Scan QRCODE \n " . $hasilkirim[0]['symbol'][0]['data'];
         return Bot::sendMessage($terjemah);
      }
   });
   //see_ melihat user aktif
   $mkbot->regex('/^\/see_/', function ($matches) {
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      $isipesan     = $info['text'];
      Bot::sendChatAction('typing');
      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         if ($isi == '/see_') {
            $text .= "⛔  Periksa \n\n<b>KETERANGAN   :</b>\nTidak Ditemukan ";
         } else {
            $sapubasah  = str_replace('/see_', '', $isipesan);
            $sapulantai = str_replace('0', '-', $sapubasah);
            $sapuujuk   = str_replace('11', ' ', $sapulantai);
            $sapulidi   = str_replace('@' . $usernamebot . '', '', $sapuujuk);
            $API        = new routeros_api();

            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
               $pepaya = $API->comm("/ip/hotspot/active/print", ["?server" => $sapulidi]);

               if (empty($pepaya)) {
                  $texta = "Tidak ada user aktif server $sapulidi";
                  Bot::sendMessage($texta);
               }

               for ($i = 0; $i < count($pepaya); $i++) {
                  $mangga    = $pepaya[$i];
                  $id        = $mangga['.id'];
                  $server    = $mangga['server'];
                  $user      = $mangga['user'];
                  $address   = $mangga['address'];
                  $mac       = $mangga['mac-address'];
                  $uptime    = $mangga['uptime'];
                  $usesstime = $mangga['session-time-left'];
                  $bytesi    = formatBytes($mangga['bytes-in'], 2);
                  $byteso    = formatBytes($mangga['bytes-out'], 2);
                  $loginby   = $mangga['login-by'];
                  $comment   = $mangga['comment'];
                  $text .= "";
                  $text .= "👤 User aktif $server\n";
                  $text .= "┠ ID :$id\n";
                  $text .= "┠ User  : $user\n";
                  $text .= "┠ IP    : $address\n";
                  $text .= "┠ Uptime : $uptime\n";
                  $text .= "┠ Byte IN      : $bytesi\n";
                  $text .= "┠ Byte OUT   : $byteso\n";
                  $text .= "┠ Sesion  : $usesstime\n";
                  $text .= "┗ Login    : $loginby\n \n";

                  Bot::sendMessage($text);
                  $total = "Total login $server " . count($pepaya);
                  Bot::sendMessage($total);
               }
            }
         }
      } else {
         $denid = "Maaf..! Aksess Hanya untuk Administator";
         Bot::sendMessage($denid);
      }
   });



   $mkbot->regex('/\/lunas/', function () {
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      $isipesan     = $info['text'];
      Bot::sendChatAction('typing');
      include ('../config/system.conn.php');
      if($idtelegram == $id_own){
         if($isipesan == "/lunas"){
            $text = "id tidak ditemukan";
         }else {
            $id = str_replace('/lunas','',$isipesan);
            $data = getHutangByID($id)[0];
            $nama = $data['nama'];
            $tanggal = $data['tanggal'];
            $nominal = rupiah($data['nominal']);
            $keterangan = $data['keterangan'];
            $exe = bayarHutang($id);
            if($exe >0 ){
               $text = "Hutang senilai $nominal atas nama $nama $tanggal telah dibayar\n\n";
               $datautang = getHutang($nama);
               if(empty($datautang)){
                  $text .= "semua tagihan sudah lunas";
               }else {
                  
               
                  $text .= "Data Tagihan $nama\n\n";
                  
                  foreach ($datautang as $key => $value) {
                     $text .= "Nama : ".$value['nama']."\n";
                     $text .= "Tanggal : ".$value['tanggal']."\n";
                     $text .= "Nominal : ".rupiah($value['nominal'])."\n";
                     $text .= "/lunas".$value['id']."\n\n";
                  }
                  $hutang = rupiah(getTotalHutang($nama));
                  $text .= "Sisa Hutang $hutang";
               }
            }else {
               $text = "gagal";
            }
         }
      }else {
         $text = 'anda bukan admin';
      }
      return Bot::sendMessage($text);
   });

   $mkbot->regex('/^\/rEm0vid/', function ($matches) {
      $info         = bot::message();
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];
      $isipesan     = $info['text'];
      Bot::sendChatAction('typing');
      $text = "";
      include ('../config/system.conn.php');

      if ($idtelegram == $id_own) {
         if ($isipesan == '/rEm0vid') {
            $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\nTidak Ditemukan Id User";
         } else {
            $id  = str_replace('/rEm0vid', '*', $isipesan);
            $ids = str_replace('@' . $usernamebot, '', $id);
            $API = new routeros_api();

            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
               $ARRAY  = $API->comm("/ip/hotspot/user/print", ["?.id" => $ids, ]);
               $data1  = $ARRAY[0]['.id'];
               $data2  = $ARRAY[0]['name'];
               $data3  = $ARRAY[0]['password'];
               $data5  = $ARRAY[0]['profile'];
               $ARRAY2 = $API->comm("/ip/hotspot/user/remove", ["numbers" => $ids, ]);
               $texta  = json_encode($ARRAY2);

               if (strpos(strtolower($texta), 'no such item') !== false) {
                  $gagal = $ARRAY2['!trap'][0]['message'];
                  $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
               } elseif (strpos(strtolower($texta), 'invalid internal item number') !== false) {
                  $gagal = $ARRAY2['!trap'][0]['message'];
                  $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
               } elseif (strpos(strtolower($texta), 'default trial user can not be removed') !== false) {
                  $gagal = $ARRAY2['!trap'][0]['message'];
                  $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
               } else {
                  $text .= "Berhasil Dihapus\n\n";
                  $text .= "<code>ID         : $ids</code>\n";
                  $text .= "<code>Server     : $data1</code>\n";
                  $text .= "<code>Name       : $data2</code>\n";
                  $text .= "<code>Password   : $data3</code>\n";
                  $text .= "<code>Profile    : $data5</code>\n";
                  sleep(2);
                  $ARRAY3 = $API->comm("/ip/hotspot/user/print");
                  $jumlah = count($ARRAY3);
                  $text .= "Jumlah user saat ini : $jumlah user";
               }
            } else {
               $text = "Gagal Periksa sambungan Kerouter";
            }
         }

         $options = ['parse_mode' => 'html', ];
         $texta   = json_encode($ARRAY2);
         return Bot::sendMessage($text, $options);
      } else {
         $denid = "Maaf..! Aksess Hanya untuk Administator";
         Bot::sendMessage($denid);
      }
   });
   $mkbot->cmd('!Menu|/Menu|/menu', function () {
   	$info         = bot::message();
      $ids          = $info['chat']['id'];
      $msgid        = $info['message_id'];
      $nametelegram = $info['from']['username'];
      $idtelegram   = $info['from']['id'];

      $text         = "";
   	      if (has($idtelegram)) {
      include ('../config/system.conn.php');
      $data = json_decode($voucher_1, true);
      if (!empty($data)) {
      $text .= "<i>Silahkan Pilih voucher dibawah ini</i>\n\n";
      $text .= "<code>Daftar Voucher :</code>\n";

      foreach ($data as $hargas) {
         $textlist = $hargas['Text_List'];

         $text .= "<code>$textlist  </code>\n";
      }

      for ($i = 0; $i < count($data); $i++) {
         ${'database' . $i}

         = ['text' => $data[$i]['Voucher'] . '', 'callback_data' => 'Vcr' . $data[$i]['id'] . ''];
      }

      $vouchernamea0 = array_filter(
         [
            $database0,
            $database1

         ]);

      $vouchernameb1 = array_filter(
         [
            $database2,
            $database3

         ]);

      $vouchernamec2 = array_filter(
         [
            $database4,
            $database5

         ]);
      $vouchernamed3 = array_filter(
         [
            $database6,
            $database7

         ]);
      $vouchernamee4 = array_filter(
         [
            $database8,
            $database9

         ]);
      $vouchernamef5 = array_filter(
         [
            $database10,
            $database11

         ]);
      $vouchernameg6 = array_filter(
         [
            $database12,
            $database13

         ]);
      $vouchernameh7 = array_filter(
            [
            $database14,
            $database15

         ]);
      $vouchernamei8 = array_filter(
         [
            $database16,
            $database17

         ]);
      $vouchernamej9 = array_filter(
            [
            $database18,
            $database19

         ]);
      $vouchernamek10 = array_filter(
            [
            $database20,
            $database21

         ]);
      $vouchernamel11 = array_filter(
         [
            $database22,
            $database23

         ]);

      $menu_idakhir = [
         ['text' => '💰 Cek Saldo', 'callback_data' => 'ceksaldo'],
         ['text' => '🔖 iNFORMASI', 'callback_data' => 'informasi'],
      ];

      $send = [];
      array_push($send, $vouchernamea0);
      array_push($send, $vouchernameb1);
      array_push($send, $vouchernamec2);
      array_push($send, $vouchernamed3);
      array_push($send, $vouchernamee4);
      array_push($send, $vouchernamef5);
      array_push($send, $vouchernameg6);
      array_push($send, $vouchernameh7);
      array_push($send, $vouchernamei8);
      array_push($send, $vouchernamej9);
      array_push($send, $vouchernamek10);
      array_push($send, $vouchernamel11);
      array_push($send, $menu_idakhir);

      $options = [
         'reply_markup' => json_encode(['inline_keyboard' => $send]),
         'parse_mode' => 'html'
      ];

      Bot::sendMessage($text, $options);
      unset($data, $voucher_1);
      } else {

      Bot::sendMessage('Maaf system tidak terdapat voucher');
      }
   }else{
   	Bot::sendMessage('Anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar');
   	 }
   });
   $mkbot->on('callback', function ($command) {

      $message           = Bot::message();
      $id                = $message['from']['id'];
      $usernamepelanggan = $message['from']['username'];
      $namatele          = $message['from']['first_name'];
      $chatidtele        = $message["message"]['chat']['id'];
      $message_idtele    = $message["message"]["message_id"];

      include ('../config/system.conn.php');

      if (has($id)) {

         if (strpos($command, 'Vcr') !== false) {
            $data  = json_decode($voucher_1, true);
            $cekid = "Vcr" . $data[0]['id'] . ",Vcr" . $data[1]['id'] . ",Vcr" . $data[2]['id'] . ",Vcr" . $data[3]['id'] . ",Vcr" . $data[4]['id'] . ",Vcr" . $data[5]['id'] . "Vcr" . $data[6]['id']."Vcr" . $data[7]['id'] . "Vcr" . $data[8]['id'] .  "Vcr" . $data[9]['id'] . "Vcr" . $data[10]['id'] . "Vcr" . $data[11]['id'] . "Vcr" . $data[12]['id'] . "Vcr" . $data[13]['id'] . "Vcr" . $data[14]['id'] . "Vcr" . $data[15]['id'] . "Vcr" . $data[16]['id'] . "Vcr" . $data[17]['id'] . "Vcr" . $data[18]['id'] . "Vcr" . $data[19]['id'] . "Vcr" . $data[20]['id'] . "Vcr" . $data[21]['id'] . "Vcr" . $data[22]['id'] ;

            if (preg_match('/' . $command . '/i', $cekid)) {
               $API = new routeros_api();

               foreach ($data as $datas => $getdata) {
                  $getid2         = $getdata['id'];
                  $princevoc      = $getdata['price'];
                  $profile        = $getdata['profile'];
                  $length         = $getdata['length'];
                  $vouchername    = $getdata['Voucher'];
                  $markup         = $getdata['markup'];
                  $server         = $getdata['server'];
                  $type           = $getdata['type'];
                  $typechar       = $getdata['typechar'];
                  $Color          = $getdata['Color'];
                  $limituptime    = $getdata['Limit'];
                  $limit_download = toBytes($getdata['limit_download']);
                  $limit_upload   = toBytes($getdata['limit_upload']);
                  $limit_total    = toBytes($getdata['limit_total']);

                  if ($command == 'Vcr' . $getid2) {
                     if (sisasaldo($id, $princevoc) == true) {
                        $limitsaldo .= "Maaf saldo anda tidak mencukupi untuk melakukan pembelian voucher\n";

                        $options = [
                           'chat_id' => $chatidtele,
                           'message_id' => (int) $message['message']['message_id'],
                           'text' => $limitsaldo,
                           'reply_markup' => json_encode([
                              'inline_keyboard' => [
                                 [
                                    ['text' => '🔙Back', 'callback_data' => 'Menu'],
                                 ], ]]),
                           'parse_mode' => 'html'

                        ];

                        Bot::editMessageText($options);
                     } else {
                        $sendupdate = "";
                        $sendupdate .= "<code>  Beli Voucher " . rupiah($princevoc) . "   </code>\n";
                        $sendupdate .= "<code>========================</code>\n";
                        $sendupdate .= "<code>  ID User  :</code> <code>$id</code>\n";
                        $sendupdate .= "<code>  Username :</code> @$usernamepelanggan\n";
                        $sendupdate .= "<code>  Status   : Pending </code>\n";
                        $sendupdate .= "<code>========================</code>\n";
                        $sendupdate .= "Mohon ditunggu Voucher akan segera dibuat\n";

                        $options = [
                           'chat_id' => $chatidtele,
                           'message_id' => (int) $message['message']['message_id'],
                           'text' => $sendupdate,
                           'parse_mode' => 'html'

                        ];

                        Bot::editMessageText($options);

                        $delete = [
                           'chat_id' => $chatidtele,
                           'message_id' => (int) $message['message']['message_id'],
                        ];
								sleep(1);
                        Bot::deleteMessage($delete);

                        if ($type == 'up') {
                           $usernamereal = make_string($length, $typechar);
                           $passwordreal = make_string($length, $typechar);
                        } else {
                           $usernamereal = make_string($length, $typechar);
                           $passwordreal = $usernamereal;
                        }

                        switch ($limituptime) {
                           case null:
                              $limituptimereal = '00:00:00';
                              break;
                           case '00:00:00':
                              $limituptimereal = '00:00:00';
                              break;
                           default:
                              $limituptimereal = $limituptime;

                              if (strpos(strtolower($limituptimereal), 'h') !== false) {
                                 $uptime = str_replace('h', ' Jam', $limituptime);
                              } elseif (strpos(strtolower($limituptime), 'd') !== false) {
                                 $uptime = str_replace('d', ' Hari', $limituptime);
                              }

                              $echoexperid .= "<code>  Experid    :</code> <code>{$uptime}</code>\n";
                              break;
                        }

                        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                           $add_user_api = $API->comm("/ip/hotspot/user/add", [
                              "server" => $server,
                              "profile" => $profile,
                              "name" => $usernamereal,
                              "limit-uptime" => $limituptimereal,
                              "limit-bytes-out" => $limit_upload,
                              "limit-bytes-in" => $limit_download,
                              "limit-bytes-total" => $limit_total,
                              "password" => $passwordreal,
                              "comment" => "| ID : $usernamepelanggan | voc : $princevoc | tgl : " . date('d-m-Y') . " | MIKBOTAM |",
                           ]);

                           if ($type == 'up') {
                              $caption = "";
                              $caption .= "<code>=========================</code>\n";
                              $caption .= "<code>  ID         : $add_user_api</code>\n";
                              $caption .= "<code>  Username   :</code> <code>$usernamereal</code>\n";
                              $caption .= "<code>  Password   :</code> <code>$passwordreal</code>\n";
                              $caption .= $echoexperid;
                              $caption .= "<code>=========================</code>\n";
                              $caption .= "<code>GUNAKAN INTERNET DGN BIJAK</code>\n";
                              $caption .= "<code>=========================</code>\n";
                           } else {
                              $caption = "";
                              $caption .= "<code>=========================</code>\n";
                              $caption .= "<code>  ID         : $add_user_api</code>\n";
                              $caption .= "<code>  ID Voucher :</code> <code>$usernamereal</code>\n";
                              $caption .= $echoexperid;
                              $caption .= "<code>=========================</code>\n";
                              $caption .= "<code>GUNAKAN INTERNET DGN BIJAK</code>\n";
                              $caption .= "<code>=========================</code>\n";
                           }

                           //cek apakah ada kesalahan pada setting voucher.
                           $cekvalidasiadd = json_encode($add_user_api);

                           if (strpos(strtolower($cekvalidasiadd), '!trap')) {
                              //salah maka bot akan dianggap salah
                              $ganguan = true;
                           } else {

                        //benar maka bot akan send voucher

                          //cek dnsname sudah ada http belum?
                              if (strpos($dnsname, 'http://') !== false) {
                                 $url = "$dnsname/login?username=$usernamereal&password=$passwordreal";
                              } else {
                                 $url = "http://$dnsname/login?username=$usernamereal&password=$passwordreal";}

                              $qrcode     = 'http://qrickit.com/api/qr.php?d=' . urlencode($url) . '&addtext=' . urlencode($Name_router) . '&txtcolor=000000&fgdcolor=' . $Color . '&bgdcolor=FFFFFF&qrsize=500';
                              $keyboard[] = [
                                 ['text' => 'Go to Login', 'url' => $url],
                              ];

                              $options = [
                                 'chat_id' => $chatidtele,
                                 'caption' => $caption,
                                 'reply_markup' => ['inline_keyboard' => $keyboard],
                                 'parse_mode' => 'html'
                              ];
                              $succes = Bot::sendPhoto($qrcode, $options);
                           }

                           $success = json_decode($succes, true);
                           if ($success['ok'] !== true) {
                              $errorprint = true;
                           }
                        } else {
                           $ganguan = true;
                        }

                        break;
                     }
                  }
               }

               if (!empty($ganguan)) {
                 //remove User jika terjadi error
                  if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                     $ARRAY2 = $API->comm("/ip/hotspot/user/remove", ["numbers" => $add_user_api, ]);
                  }

                  $gagal .= "<code>  Beli Voucher " . rupiah($princevoc) . "   </code>\n";
                  $gagal .= "<code>========================</code>\n";
                  $gagal .= "<code>  ID User  :</code> <code>$id</code>\n";
                  $gagal .= "<code>  Username :</code> @$usernamepelanggan\n";
                  $gagal .= "<code>  Status   : inVaild Conect Server </code>\n";
                  $gagal .= "<code>========================</code>\n";
                  $gagal .= "Maaf server mengalami gangguan silahkan hubungi admin atau tunggu 5 menit dan generate ulang\n";
                  $options = [
                     'chat_id' => $chatidtele,
                     'parse_mode' => 'html'

                  ];
                  $keterangan = 'gagal';
                  Bot::sendMessage($gagal, $options);

                  $set = belivoucher($id, $usernamepelanggan, '0', '0', $usernamereal, $passwordreal, $profile, $keterangan);
               } elseif (!empty($errorprint)) {

                 //remove User jika terjadi error
                  if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                     $ARRAY2 = $API->comm("/ip/hotspot/user/remove", ["numbers" => $add_user_api, ]);
                  }

                  $gagalprint .= "";
                  $gagalprint .= "<code>   Beli Voucher " . rupiah($princevoc) . "  </code>\n";
                  $gagalprint .= "<code>========================</code>\n";
                  $gagalprint .= "<code>  ID User  :</code> <code>$id</code>\n";
                  $gagalprint .= "<code>  Username :</code> @$usernamepelanggan\n";
                  $gagalprint .= "<code>  Status   : inVaild Print Voucher </code>\n";
                  $gagalprint .= "<code>========================</code>\n";
                  $gagalprint .= "Maaf server mengalami gangguan silahkan hubungi admin atau tunggu 5 menit dan generate ulang\n";
                  $options    = ['chat_id' => $chatidtele, 'parse_mode' => 'html'];
                  $keterangan = 'gagalprint';
                  Bot::sendMessage($gagalprint, $options);

                  $set = belivoucher($id, $usernamepelanggan, '0', '0', $usernamereal, $passwordreal, $profile, $keterangan);
               } else if (!empty($succes)) {

//===================>>NAMBAH MENU SISA SALDO==============
               	$angka = lihatsaldo($id);
                  $Success = "";
                  $Success = "<code>  Beli Voucher " . rupiah($princevoc) . "   </code>\n";
                  $Success .= "<code>========================</code>\n";
                  $Success .= "<code>  ID User  :</code> <code>$id</code>\n";
                  $Success .= "<code>  Username :</code> @$usernamepelanggan\n";
                  $Success .= "<code>  Status   : Success </code>\n";
                  $Success .= "<code>  Saldo    : " . rupiah($angka - $princevoc + $markup) . "</code>\n";
                  $Success .= "<code>========================</code>\n";

                  if (isset($Success)) {
                     $saldoawal   = lihatsaldo($id);
                     $keterangan  = 'Success';
                     $markupakhir = minus($princevoc, $markup);
                     $set         = belivoucher($id, $usernamepelanggan, $markupakhir, $markup, $usernamereal, $passwordreal, $profile, $keterangan);
                     $angka       = lihatsaldo($id);
                     $options     = [
                        'chat_id' => $chatidtele,
                        'reply_markup' => json_encode([
                           'inline_keyboard' => [
                              [
                                 ['text' => '⏱ History', 'callback_data' => 'VMarkup|' . $princevoc . '|' . $markup . '|' . $markupakhir . '|' . $saldoawal . '|' . $angka . ''],
                                 ['text' => '🔙 Back', 'callback_data' => 'Menu'],
                              ], [
                                 ['text' => '🔖 Cek Saldo', 'callback_data' => 'notifsaldo'],
                              ]
                           ]]),
                        'parse_mode' => 'html'

                     ];

                     Bot::sendMessage($Success, $options);
                  }
               }
            } else {
               $Success = "";
               $Success = "Maaf voucher ini tidak lagi tersedia \n";

               $options = [
                  'chat_id' => $chatidtele,
                  'parse_mode' => 'html'

               ];

               Bot::sendMessage($Success, $options);
            }
         } elseif ($command == 'Menu') {
            $text = "";
            $data = json_decode($voucher_1, true);
            $text .= "<i>Silahkan Pilih voucher dibawah ini</i>\n\n";
            $text .= "<code>Daftar Voucher :</code>\n";
            foreach ($data as $hargas) {
               $textlist = $hargas['Text_List'];
               $text .= "<code>$textlist </code>\n";
            }

            $datavoc = json_decode($voucher_1, true);
            for ($i = 0; $i < count($datavoc); $i++) {
               ${
                  'database' . $i
               }

               = ['text' => $datavoc[$i]['Voucher'] . '', 'callback_data' => 'Vcr' . $datavoc[$i]['id'] . ''];
            }

            $vouchernamea0 = array_filter(
               [
                  $database0,
                  $database1
      
               ]);
      
            $vouchernameb1 = array_filter(
               [
                  $database2,
                  $database3
      
               ]);
      
            $vouchernamec2 = array_filter(
               [
                  $database4,
                  $database5
      
               ]);
            $vouchernamed3 = array_filter(
               [
                  $database6,
                  $database7
      
               ]);
            $vouchernamee4 = array_filter(
               [
                  $database8,
                  $database9
      
               ]);
            $vouchernamef5 = array_filter(
               [
                  $database10,
                  $database11
      
               ]);
            $vouchernameg6 = array_filter(
               [
                  $database12,
                  $database13
      
               ]);
            $vouchernameh7 = array_filter(
                  [
                  $database14,
                  $database15
      
               ]);
            $vouchernamei8 = array_filter(
               [
                  $database16,
                  $database17
      
               ]);
            $vouchernamej9 = array_filter(
                  [
                  $database18,
                  $database19
      
               ]);
            $vouchernamek10 = array_filter(
                  [
                  $database20,
                  $database21
      
               ]);
            $vouchernamel11 = array_filter(
               [
                  $database22,
                  $database23
      
               ]);
      
            $menu_idakhir = [
               ['text' => '💰 Cek Saldo', 'callback_data' => 'ceksaldo'],
               ['text' => '🔖 iNFORMASI', 'callback_data' => 'informasi'],
            ];
            $send = [];
            array_push($send, $vouchernamea0);
            array_push($send, $vouchernameb1);
            array_push($send, $vouchernamec2);
            array_push($send, $vouchernamed3);
            array_push($send, $vouchernamee4);
            array_push($send, $vouchernamef5);
            array_push($send, $vouchernameg6);
            array_push($send, $vouchernameh7);
            array_push($send, $vouchernamei8);
            array_push($send, $vouchernamej9);
            array_push($send, $vouchernamek10);
            array_push($send, $vouchernamel11);
            array_push($send, $menu_idakhir);

            $options = [
               'chat_id' => $chatidtele,
               'message_id' => (int) $message['message']['message_id'],
               'text' => $text,
               'reply_markup' => json_encode(['inline_keyboard' => $send]),
               'parse_mode' => 'html'

            ];

            Bot::editMessageText($options);
         } elseif ($command == 'ceksaldo') {

            if (has($id) == false) {
               $text = 'Anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar';
            } else {
               $angka = lihatsaldo($id);
               $text  = "<code>      Informasi Saldo</code>\n";
               $text .= "<code>========================</code>\n";
               $text .= "<code>  ID User : $id</code>\n";
               $text .= "<code>  Name    : @$usernamepelanggan</code>\n";
               $text .= "<code>  Saldo   : " . rupiah($angka) . "</code>\n";
               $text .= "<code>========================</code>\n";
            }

            $options = [
               'chat_id' => $chatidtele,
               'message_id' => (int) $message['message']['message_id'],
               'text' => $text,
               'reply_markup' => json_encode([
                  'inline_keyboard' => [
                     [
                        ['text' => '🔙 Back', 'callback_data' => 'Menu'],
                     ], ]]),
               'parse_mode' => 'html'

            ];

            Bot::editMessageText($options);
         } elseif ($command == 'informasi') {
            $text    = 'Tidak ada informasi terkini';
            $options = [
               'chat_id' => $chatidtele,
               'message_id' => (int) $message['message']['message_id'],
               'text' => $text,
               'reply_markup' => json_encode([
                  'inline_keyboard' => [
                     [
                        ['text' => 'Back', 'callback_data' => 'Menu'],
                     ], ]]),
               'parse_mode' => 'html'

            ];

            Bot::editMessageText($options);
         } elseif (strpos($command, 'tps') !== false) {
            if (preg_match('/^tps/', $command)) {
               $cekdata  = explode('|', $command);
               $cek      = $cekdata[1];
               $text .= "@$usernamepelanggan Permintaan deposit  sebesar " . rupiah($cek) . " sudah kami terima, \nSilahkan kirimkan foto bukti pembayaran  disertai dengan Caption #konfirmasi deposit $cek\n\nKonfirmasi selambatnya 2 jam setelah permintaan deposit";
               $options = [
                  'chat_id' => $chatidtele,
                  'message_id' => (int) $message['message']['message_id'],
                  'text' => $text,
                  'parse_mode' => 'html'

               ];

               Bot::editMessageText($options);

               $textsend = "";
               $textsend .= "<code>User :  </code>@$usernamepelanggan \n";
               $textsend .= "<code>ID   : </code> <code>$id </code>\n";
               $textsend .= "<code>Request pengisian saldo </code>\n";
               $textsend .= "<code>Nominal :" . rupiah($cek) . "</code>\n";
               $textsend .= "<code>Silahkan tindak lanjut \nAtau Hubungi user </code> @$usernamepelanggan \n\n";
               $textsend .= "Dengan Menekan tombol dibawah ini saldo user otomatis terisi  ";

               $kirimpelangan = [
                  'chat_id' => $id_own,
                  'reply_markup' => json_encode([
                     'inline_keyboard' => [
                        [
                           ['text' => 'QUICK TOP UP', 'callback_data' => '12'],
                        ],
                        [
                           ['text' => '' . rupiah($cek) . '', 'callback_data' => 'tp|' . $cek . '|' . $id . '|' . $usernamepelanggan . ''],
                        ],
                        [
                           ['text' => 'OR COSTUM', 'callback_data' => '12'],
                        ],
                        [
                           ['text' => '10000', 'callback_data' => 'tp|10000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '15000', 'callback_data' => 'tp|15000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '20000', 'callback_data' => 'tp|20000|' . $id . '|' . $usernamepelanggan . ''],
                        ],
                        [

                           ['text' => '25000', 'callback_data' => 'tp|25000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '30000', 'callback_data' => 'tp|30000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '50000', 'callback_data' => 'tp|50000|' . $id . '|' . $usernamepelanggan . ''],
                        ],
                        [

                           ['text' => '100000', 'callback_data' => 'tp|100000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '150000', 'callback_data' => 'tp|150000|' . $id . '|' . $usernamepelanggan . ''],
                           ['text' => '200000', 'callback_data' => 'tp|200000|' . $id . '|' . $usernamepelanggan . ''],
                        ],
                        [

                           ['text' => 'Reject Request', 'callback_data' => 'tp|reject|' . $id . '|reject']
                        ],

                     ]]),
                  'parse_mode' => 'html'

               ];

               Bot::sendMessage($textsend, $kirimpelangan);
            }
         } elseif (strpos($command, 'tp') !== false) {

            if (preg_match('/^tp/', $command)) {
               $cekdata     = explode('|', $command);
               $cekkodeunik = $cekdata[0];
               $jumlah      = $cekdata[1];
               $iduser      = $cekdata[2];
               $namauser    = $cekdata[3];
               $text        = "";
               if ($jumlah == 'reject') {

                  $text = "Masa tunggu konfirmasi deposit telah habis permintaan deposit telah kadaluarsa.\nSilahkan konfirmasi deposit selambatnya 2 jam setelah Request Deposit.\n\nTerima kasih.";
                  //kirim ke user
                  $kirimpelangan = [
                     'chat_id' => $iduser,
                     'parse_mode' => 'html'

                  ];
                  Bot::sendMessage($text, $kirimpelangan);

                  $options = [
                     'chat_id' => $chatidtele,
                     'message_id' => (int) $message['message']['message_id'],
                     'text' => 'Reject Deposit berhasil',
                     'parse_mode' => 'html'
                  ];
                  Bot::editMessageText($options);


               } else {

                  if ($id == $id_own) {
                     if (!empty($iduser) && !empty($jumlah)) {
                        if (has($iduser) == false) {
                           $text = 'Data id ' . $iduser . 'tidak terdaftar silahkan periksa kembali';
                        } else {

                           if (preg_match('/^[0-9]+$/', $jumlah)) {
                              if (strlen($jumlah) < 7) {
                                 $text = topupresseller($iduser, $namauser, $jumlah, $id_own);

                                 //kirim ke user
                                 $kirimpelangan = [
                                    'chat_id' => $iduser,
                                    'reply_markup' => json_encode([
                                       'inline_keyboard' => [
                                          [
                                             ['text' => '🔎 Beli Voucher', 'callback_data' => 'Menu'],
                                             ['text' => '📛 Promo Hot', 'callback_data' => 'informasi'],
                                          ], ]]),
                                    'parse_mode' => 'html'
                                 ];
                                 Bot::sendMessage($text, $kirimpelangan);
                                 //
                              } else {

//==============================>>>UBAH MAXIMAL TOP UP
                                 $text = 'Maaf Maksimal Top Up Rp 999.999.00';
                              }
                           } else {
                              $text = 'Maaf nominal tidak berupa angka ';
                           }
                        }
                     } else {
                        $text = "Maaf format data salah ";
                     }
                  } else {
                     $text = "Maaf..! Aksess Hanya untuk Administator";
                  }
                  $options = [
                     'chat_id' => $chatidtele,
                     'message_id' => (int) $message['message']['message_id'],
                     'text' => $text,
                     'parse_mode' => 'html'
                  ];
                  Bot::editMessageText($options);
               }
            }
         } elseif (strpos($command, 'VMarkup') !== false) {
            $cekdata     = explode('|', $command);
            $cekkodeunik = $cekdata[0];
            $princevoc   = $cekdata[1];
            $markup      = $cekdata[2];
            $markupakhir = $cekdata[3];
            $saldoawal   = $cekdata[4];
            $saldo       = $cekdata[5];
            $text        = "";

            if (!empty($princevoc)) {
               $text .= "<code>Saldo Awal    = </code>" . rupiah($saldoawal) . " \n";
               $text .= "<code>Voucher Price = </code>" . rupiah($princevoc) . " \n";
               $text .= "<code>Total Markup  = </code>" . rupiah($markup) . " \n";
               $text .= "#  Voucher-Markup \n";
               $text .= "# " . rupiah($princevoc) . " - " . rupiah($markup) . " = " . rupiah($markupakhir) . " \n";
               $text .= "# Saldoawal-Markup Akhir \n";
               $text .= "# " . rupiah($saldoawal) . " - " . rupiah($markupakhir) . " = " . rupiah($saldo) . " \n";
               $text .= "<b>Sisa saldo </b> : " . rupiah($saldo) . " \n";
            } else {
               $text = "Maaf format data salah ";
            }

            $options = [
               'chat_id' => $chatidtele,
               'message_id' => (int) $message['message']['message_id'],
               'text' => $text,
               'reply_markup' => json_encode([
                  'inline_keyboard' => [
                     [
                        ['text' => '🔙 Back', 'callback_data' => 'Menu'],
                     ], ]]),
               'parse_mode' => 'html'

            ];

            Bot::editMessageText($options);
         } elseif (strpos($command, 'notifsaldo') !== false) {

            if (has($id) == false) {
               $text = 'Anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar';
            } else {
               $angka = lihatsaldo($id);
               if ($angka < 5000) {
                  $text = "ID anda  : $id \nSisa saldo anda  : " . rupiah($angka) . "\n⚠ Segera isi ulang saldo anda";
               } else {
                  $text = "ID anda  : $id \nSisa saldo anda  : " . rupiah($angka);
               }
            }
            Bot::answerCallbackQuery($text, $options = ['show_alert' => true]);
         }
         } else {
         $text    = 'Maaf  anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar';
         $options = [
            'chat_id' => $chatidtele,
            'message_id' => (int) $message['message']['message_id'],
            'text' => $text,
         ];
         Bot::editMessageText($options);
      }
   });
   $mkbot->on('photo', function () {
      $info           = bot::message();
      $nametelegram   = $info['from']['username'];
      $idtelegram     = $info['from']['id'];
      $caption        = strtolower($info['caption']);
      $explode        = explode(' ', $caption);
      $konfirmasitext = $explode['0'];
      $deposittext    = $explode['1'];
      $jumlahtext     = $explode['2'];

      if (!empty($caption)) {
         include ('../config/system.conn.php');
         if (has($idtelegram)) {
         	//cek kandungan
         	  if (preg_match('/^#konfirmasi/', $konfirmasitext)) {
         	  	//cek lagi sesuai format
            if ($konfirmasitext == '#konfirmasi' && $deposittext == 'deposit' && !empty($jumlahtext)) {
               if (preg_match('/^[0-9]+$/', $jumlahtext)) {
                  $fototerbaik = $info['photo'][3]['file_id'];
                  $fotomedium  = $info['photo'][2]['file_id'];
                  $fotorendah  = $info['photo'][1]['file_id'];
                  $fotojelek   = $info['photo'][0]['file_id'];
                  $caption     = "Lapor ! konfirmasi deposit dari @$nametelegram Jumlah " . rupiah($jumlahtext) . " Silahkan di periksa dan ditindak lanjut";
                  if (!empty($fototerbaik)) {
                     Bot::sendPhoto($fototerbaik, $options = ['chat_id' => $id_own, 'caption' => $caption, 'parse_mode' => 'html']);
                     Bot::sendMessage("konfirmasi deposit sudah kami dan akan segera kami prosses mohon tunggu\n\nTerima kasih");
                  } elseif (!empty($fotomedium)) {
                     Bot::sendPhoto($fotomedium, $options = ['chat_id' => $id_own, 'caption' => $caption, 'parse_mode' => 'html']);
                     Bot::sendMessage("konfirmasi deposit sudah kami terima, akan segera kami prosses mohon tunggu\n\nTerima kasih");
                  } elseif (!empty($fotorendah)) {
                     Bot::sendMessage("Maaf foto anda tidak jelas system kami tidak dapat membaca foto anda  \n");
                  } else {
                     Bot::sendMessage("Maaf foto anda tidak jelas system kami tidak dapat membaca foto anda  \n");
                  }
               } else {
                  Bot::sendMessage("Maaf Jumlah deposit hanya berupa angka saja   \n");
               }
            } else {
               Bot::sendMessage("Silahkan konfirmasi deposit disertai dengan keterangan di foto Contoh format keterangan : konfirmasi deposit 20000 ");
            }
         	  }
         } else {
            Bot::editMessageText('Maaf anda tidak terdaftar silahkan daftar terlebih dahulu ke admin atau klik /daftar');
         }
      }
   });
   $mkbot->run();

   /*Please contact @Bangachil for bugs
   history
   1 Maret 2019
   -Make ceksaldo command
   -Make cekid
   2 Maret 2019
   -Make callback data
   -Make menu command
   -Make array menu
   -Make callback answer
   3 Maret 2019
   -Make database Saldo
   -bugs fix daftar
   -bugs fix menu
   -bugs fix saldo minus
   -bugs fix topup
   -Make topup send to ID
   -Make button menu
   10 Maret 2019
   -make emoticon button menu
   -make cek id calbback
   -make voucher defalut disable
   -make voucher null
   19 Maret 2019
   -bugs fix menu command
   -bugs fix callback answer
   -bugs fix list Voucher array
   -bugs fix database
   Version update 1.2.3
   20 maret 2019
   -Make User id callback
   -remove emotion calbback
   -remove array_filter
   -move data callback
   -move ceksaldo
   -Make ceksaldo cek id
   Version update 1.2.11

   2 april 2019
   -remove start auto join
   -make hitspot view
   -make remove user hotspot cmd
   -make help cmd
   -Make hass user
   Version update 1.2.13

   3 april 2019
   -edited vcr callback data

   Version update 1.2.14
   8 april 2019
   -Penambahan type char
   -color qrcode
   -bugs fix saldo minus
   Version update 1.3.00

   -10 april 2019
   -Perispan nonsaldo
   -edit text
   -Version update 1.4.00
   11 april 2019

   -Version update 1.5.00

   Thanks to topupGroup an member , SengkuniCode, and to all user support mini project
   Thanks to SengkuniCode for web ui,

    */

