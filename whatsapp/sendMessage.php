<?php
require 'function.php';


$nomer = $_GET['nomer'];
$pesan = $_GET['pesan'];

$nomer = preg_replace('/^08/','628',$nomer);
$nomer = $nomer."@c.us";

if(empty($nomer)||empty($pesan)){
    $masalah = array(
        'status'    => 'gagal',
        'error'     =>  'coba cek pesan atau nomer'
    );
    print_r($masalah);
    die;
}


$hasil = sendMessage($nomer,$pesan);
$hasil = json_decode($hasil, TRUE);
print_r($hasil);



?>