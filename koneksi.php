<?php
$koneksi = new mysqli("localhost", "root", "", "toko_roti");

if($koneksi->connect_error){
    die("Koneksi Gagal : " . $koneksi->connect_error);
}
?>