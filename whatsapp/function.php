<?php
require ("../config/system.config.php");

$koneksidb = mysqli_connect("$address","$username","$password","$database");


function query($query){
    global $koneksidb;
    $result = mysqli_query($koneksidb,$query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)){
      $rows[] = $row;
    }
    return $rows;
}

function encrypturl($pamerbojo) {
        $kunciobeng = '4ku4ll';
        for ($i = 0; $i < strlen($pamerbojo); $i++) {
                $buahnanas = substr($pamerbojo, $i, 1);
                $kunciinggris = substr($kunciobeng, ($i % strlen($kunciobeng)) - 1, 1);
                $buahnanas = chr(ord($buahnanas) + ord($kunciinggris));
                $serondenggosong .= $buahnanas;
        }
        return base64_encode($serondenggosong);
}

function decrypturl($pamerbojo) {
        $pamerbojo = base64_decode($pamerbojo);
        $serondenggosong = '';
        $kunciobeng = '4ku4ll';
        for ($i = 0; $i < strlen($pamerbojo); $i++) {
                $buahnanas = substr($pamerbojo, $i, 1);
                $kunciinggris = substr($kunciobeng, ($i % strlen($kunciobeng)) - 1, 1);
                $buahnanas = chr(ord($buahnanas) - ord($kunciinggris));
                $serondenggosong .= $buahnanas;
        }
        return $serondenggosong;
}

function rupiah($angka){
    $hasil_rupiah = "Rp" . number_format($angka,2,',','.');
    return $hasil_rupiah;
}


//mengirim pesan
function sendMessage($nomer,$pesan){
    $curl = curl_init();

    $nomer = preg_replace('/^08/','628',$nomer);
    $nomer = preg_replace('/\D/','',$nomer);
    if(preg_match('/[0-9]+$/',$nomer)){
    $nomer = $nomer."@c.us";
    }
    $data = array(
        'number' => $nomer,
        'message' => $pesan
    );
    $data = http_build_query($data);

    curl_setopt_array($curl, array(
    CURLOPT_PORT => "8081",
    CURLOPT_URL => "http://localhost/sendMessage",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "Accept: application/json"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    return $response;
    }
}

function numberOnly($nomer){
    $nomer = preg_replace('/(@c.us)/','',$nomer);
    return $nomer;
}

function hari_ini(){
	$hari = date ("D");
 
	switch($hari){
		case 'Sun':
			$hari_ini = "Minggu";
		break;
 
		case 'Mon':			
			$hari_ini = "Senin";
		break;
 
		case 'Tue':
			$hari_ini = "Selasa";
		break;
 
		case 'Wed':
			$hari_ini = "Rabu";
		break;
 
		case 'Thu':
			$hari_ini = "Kamis";
		break;
 
		case 'Fri':
			$hari_ini = "Jumat";
		break;
 
		case 'Sat':
			$hari_ini = "Sabtu";
		break;
		
		default:
			$hari_ini = "Tidak di ketahui";		
		break;
	}
 
	return $hari_ini;
 
}

function simpan_data_client($nama,$password,$nomer){
	global $koneksidb;
	$query = "INSERT INTO `st_nomerwhatsapp` (`id`, `nama`, `password`, `mac`, `nomer`, `keterangan`, `batas`) VALUES (NULL, '$nama', '$password', '', '$nomer', 'off', '5000');";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}

function getHarga(){
	global $koneksidb;
	$harga = query("SELECT * FROM `st_harga` WHERE id=1");
	return $harga[0]['harga'];
}

function getName($nomer){
    global $koneksidb;
    $data = query("SELECT * FROM `st_nomerwhatsapp` WHERE nomer='$nomer'")[0];
    return $data['nama'];
}
//mencatat hutang
function catatHutang($nama,$nomer,$nominal){
    global $koneksidb;
    $hari = hari_ini();
    $tanggal = date("d-m-Y");
    $jam    = date('H:i:s');
    $date = "$hari, Tanggal $tanggal Pukul $jam";
    $query = "INSERT INTO `wa_hutang` (`id`, `nama`, `nomer`, `tanggal`, `nominal`, `keterangan`, `status`) VALUES (NULL, '$nama', '$nomer', '$date', '$nominal', 'wifi', 'tempo');";
    mysqli_query($koneksidb,$query);
    return mysqli_affected_rows($koneksidb);
}
//cek total hutang
function cekHutang($nama){
    global $koneksidb;
    $hutang = query("SELECT SUM(IF(nama='$nama' AND status='tempo',nominal,0)) AS hutang FROM wa_hutang")[0];
    return $hutang['hutang'];
}
//cek tagihan secara mendetail
function getHutang($nama){
	global $koneksidb;
	$data = query("SELECT * FROM `wa_hutang` WHERE nama='$nama' AND status='tempo'");
	return $data;
}
//cek batas pay leter
function getLimit($nama){
    global $koneksidb;
    $batas = query("SELECT batas FROM `st_nomerwhatsapp` WHERE nama='$nama'")[0];
    return $batas['batas'];
}

function formatBytes($size, $decimals = 0){
    $unit = array(
    '0' => 'Byte',
    '1' => 'KiB',
    '2' => 'MiB',
    '3' => 'GiB',
    '4' => 'TiB',
    '5' => 'PiB',
    '6' => 'EiB',
    '7' => 'ZiB',
    '8' => 'YiB'
    );
    
    for($i = 0; $size >= 1024 && $i <= count($unit); $i++){
    $size = $size/1024;
    }
    
    return round($size, $decimals).' '.$unit[$i];
}

function getDetail($nama){
    global $koneksidb;
    $detail = query("SELECT * FROM `st_nomerwhatsapp` WHERE nama='$nama'");
    return $detail[0];
}
function getDetailbyNomer($nomer){
    global $koneksidb;
    $detail = query("SELECT * FROM `st_nomerwhatsapp` WHERE nomer='$nomer'");
    return $detail[0];
}

function getSetting(){
    global $koneksidb;
    $result = query("SELECT * FROM `st_mikbotam`");
    return $result;
}

function simpanPenjualan($nama,$idr){
	global $koneksidb;
	$tanggal = date("Y-m-d");
	$waktu = date("H:i:s");
	$query = "INSERT INTO `st_reportmanual` (`id`, `tanggal`, `waktu`, `nama`, `idr`) VALUES (NULL, '$tanggal', '$waktu', '$nama', '$idr');";
	mysqli_query($koneksidb,$query);

	return mysqli_affected_rows($koneksidb);
}

function bayarHutang($id){
    global $koneksidb;
    $query = "UPDATE `wa_hutang` SET `status` = 'lunas' WHERE `wa_hutang`.`id` = $id;";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}

function getBulan($bulan){
    if(empty($bulan)){
        $bulan  = date("m");
    }
    switch($bulan){
        case '01':
            return "Januari";
        break;
        case '02':
            return "Februari";
        break;
        case '03':
            return "Maret";
        break;
        case '04':
            return "April";
        break;
        case '05':
            return "Mei";
        break;
        case '06':
            return "Juni";
        break;
        case '07':
            return "Juli";
        break;
        case '08':
            return "Agustus";
        break;
        case '09':
            return "Septermber";
        break;
        case '10':
            return "Oktober";
        break;
        case '11':
            return "November";
        break;
        case '12':
            return "Desmber";
        break;
    }
}

?>
