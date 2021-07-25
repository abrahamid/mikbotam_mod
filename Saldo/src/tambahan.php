<?php
require ("../config/system.config.php"); //ron on clear
//require ("../../config/system.config.php"); //run on tes
$koneksidb = mysqli_connect("$address","$username","$password","$database");

function duit($angka){
    $hasil_rupiah = "Rp" . number_format($angka,2,',','.');
    return $hasil_rupiah;
}

//mengirim pesan
function sendWhatsapp($nomer,$pesan){
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
function query($query){
    global $koneksidb;
    $result = mysqli_query($koneksidb,$query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)){
      $rows[] = $row;
    }
    return $rows;
}

function simpandata($nama,$idr){
	global $koneksidb;
	$tanggal = date("Y-m-d");
	$waktu = date("H:i:s");
	$query = "INSERT INTO `st_reportmanual` (`id`, `tanggal`, `waktu`, `nama`, `idr`) VALUES (NULL, '$tanggal', '$waktu', '$nama', '$idr');";
	mysqli_query($koneksidb,$query);

	return mysqli_affected_rows($koneksidb);
}

function modal($idr){
	global $koneksidb;
	$tanggal = date("Y-m-d");
	$waktu = date("H:i:s");
	$query = "INSERT INTO `st_modal` (`id`, `tanggal`, `waktu`, `idr`) VALUES (NULL, '$tanggal', '$waktu', '$idr');";
	mysqli_query($koneksidb,$query);

	return mysqli_affected_rows($koneksidb);
}

function hasilpenjualan($bualan){
	global $koneksidb;
	$year = date("Y");
	$totalBiaya = query("SELECT SUM(idr) FROM `st_reportmanual` WHERE tanggal LIKE '$year-$bualan%'")[0];
	return $totalBiaya["SUM(idr)"];
}

function totalmodal($bualan){
	global $koneksidb;
	$year = date("Y");
	$totalBiaya = query("SELECT SUM(idr) FROM `st_modal` WHERE tanggal LIKE '$year-$bualan%'")[0];
	return $totalBiaya["SUM(idr)"];
}
//masih proses pengembangan

function cari_mac($mac){
	global $koneksidb;
	$data = query("SELECT * FROM st_nomerwhatsapp WHERE mac='$mac'");
	return $data;

}

function simpan_data_client($nama,$password,$nomer,$mac){
	global $koneksidb;
	$query = "INSERT INTO `st_nomerwhatsapp` (`id`, `nama`, `password`, `mac`, `nomer`, `keterangan`, `batas`) VALUES (NULL, '$nama', '$password', '$mac', '$nomer', 'off', '5000');";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}

function notif($nama,$keterangan){
	global $koneksidb;
	$query = "UPDATE `st_nomerwhatsapp` SET `keterangan` = '$keterangan' WHERE `st_nomerwhatsapp`.`nama` = '$nama'";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}


function wa($nomer){
	if(strlen($nomer)===12){
	  $wa = $nomer + 6200000000000;
	  return $wa;
	}elseif(strlen($nomer)===11){
	  $wa = $nomer + 620000000000;
	  return $wa;
	}elseif(strlen($nomer)===10){
	  $wa = $nomer + 62000000000;
	  return $wa;
	}elseif($nomer=== '-'){
	  return "-";
	}else{
	  return "salah";
	}
}
function namauser($mac){
	$cari = query("SELECT * FROM st_mac WHERE addr='$mac'")[0];
	return $cari['nama'];
}

function getDetailbyName($nama){
	global $koneksidb;
	$haisl = query("SELECT * FROM st_nomerwhatsapp WHERE nama='$nama'");
	return $haisl;
}
function getHarga(){
	global $koneksidb;
	$harga = query("SELECT * FROM `st_harga` WHERE id=1");
	return $harga[0]['harga'];
}

function setHarga($idr){
	global $koneksidb;
	$query = "UPDATE `st_harga` SET `harga` = '$idr' WHERE `st_harga`.`id` = 1;";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}

function bayarHutang($id){
    global $koneksidb;
    $query = "UPDATE `wa_hutang` SET `status` = 'lunas' WHERE `wa_hutang`.`id` = $id;";
	mysqli_query($koneksidb,$query);
	return mysqli_affected_rows($koneksidb);
}

function getHutangByID($id){
	global $koneksidb;
	$data	= query("SELECT * FROM `wa_hutang` WHERE id='$id'");
	return $data;
}

function getHutang($nama){
	global $koneksidb;
	$data = query("SELECT * FROM `wa_hutang` WHERE nama='$nama' AND status='tempo'");
	return $data;
}
function getTotalHutang($nama){
    global $koneksidb;
    $hutang = query("SELECT SUM(IF(nama='$nama' AND status='tempo',nominal,0)) AS hutang FROM wa_hutang")[0];
    return $hutang['hutang'];
}
function catatHutang($nama,$nomer,$nominal,$keterangan){
    global $koneksidb;
    $hari = hari_ini();
    $tanggal = date("d-m-Y");
    $jam    = date('H:i:s');
    $date = "$hari, Tanggal $tanggal Pukul $jam";
    $query = "INSERT INTO `wa_hutang` (`id`, `nama`, `nomer`, `tanggal`, `nominal`, `keterangan`, `status`) VALUES (NULL, '$nama', '$nomer', '$date', '$nominal', '$keterangan', 'tempo');";
    mysqli_query($koneksidb,$query);
    return mysqli_affected_rows($koneksidb);
}
?>
