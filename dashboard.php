<?php
session_start();
if(!isset($_SESSION["status_login"]) || $_SESSION["status_login"] !== true){
    header("Location: login.php");
    exit();
}
include "koneksi.php";

$hasil = $koneksi->query("SELECT * FROM kue");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Katalog Kue</h3>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>

        <div class="row">
            <?php while($baris = mysqli_fetch_assoc($hasil)){ ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="uploads/<?php echo $baris['foto']; ?>"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;"
                             alt="foto kue">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $baris["nama"]; ?></h5>
                            <p class="card-text mb-1">Harga: Rp <?php echo $baris["harga"]; ?></p>
                            <p class="card-text">Stok: <?php echo $baris["stok"]; ?></p>
                            <a href="edit_kue.php?id=<?php echo $baris['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_kue.php?id=<?php echo $baris['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus kue ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <a href="tambah_kue.php" class="btn btn-primary mb-4">Tambah Kue</a>
    </div>
</body>
</html>