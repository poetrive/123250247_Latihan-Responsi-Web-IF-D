<?php
session_start();
if(!isset($_SESSION["status_login"]) || $_SESSION["status_login"] !== true){
    header("Location: login.php");
    exit();
}
include "koneksi.php";

$id = $_GET["id"];

$stmt = $koneksi->prepare("SELECT foto FROM kue WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($foto);
$stmt->fetch();
$stmt->close();

if($foto){
    if(file_exists("uploads/" . $foto)){
        unlink("uploads/" . $foto);
    }

    $stmt = $koneksi->prepare("DELETE FROM kue WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: dashboard.php");
exit();
?>