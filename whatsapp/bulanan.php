<?php
require "function.php";
$nama = query("SELECT DISTINCT nama FROM wa_hutang WHERE keterangan='bulanan' AND status='tempo' ORDER BY `wa_hutang`.`nama`");
foreach ($nama as $key => $value) {
    $jeneng = $value['nama'];
    $data = query("SELECT * FROM wa_hutang WHERE nama='$jeneng' AND keterangan='bulanan' AND status='tempo'");
    $nomer = $data[0]['nomer'];
    $text    = "Pemberitahuan Tagihan Internet Bulanan\n\n";
    $text   .= "Nama : $jeneng\n";
    $text   .= "Daftar Tagihan Anda : \n\n";
    foreach ($data as $k => $v) {    
        $nominal    = $v['nominal'];
        $tangal     = $v['tanggal'];
        $keterangan = $v['keterangan'];
        $tanggal     = $v['tanggal'];
        $bulan = explode('-',$tanggal);
        $bulan = getBulan($bulan[1]);
        if($keterangan == 'bulanan'){
            $text   .= "*Langganan Internet Bulan* Pada Bulan *$bulan* Dengan Nominal Tagihan *".rupiah($nominal)."*\n\n";
        }else {
            $text   .= "Pembelian $keterangan Pada hari *$tangal* Senilai ".rupiah($nominal)."\n\n";
        }

    }
    //$text = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $text   .= "Mohon Segera Lunasi Tagihan anda.\n";
    $text   .= "Pesan Dibuat Secara Otomatis Oleh System.\n";
    $text   .= "Balas dengan *!tagihan* Untuk menampilkan detail tagihan.";
    //$text   .= $nomer;
    //echo "$text\n=======================================================================================\n";
    //var_dump($data);
    sendMessage($nomer,$text);

}
?>