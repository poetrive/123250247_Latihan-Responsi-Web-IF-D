<?php
session_start();
if(!isset($_SESSION["status_login"]) || $_SESSION["status_login"] !== true) {
    header("Location: login.php");
    exit();
}
include "koneksi.php";

$pesan = "";

if(isset($_POST["submit"])) {
    $nama  = trim($_POST["nama"]);                 
    $harga = $_POST["harga"];
    $stok  = $_POST["stok"];

    if($nama == ""){                          
        $pesan = "Nama kue tidak boleh kosong!";
    }else{
        if(!is_dir("uploads")){
            mkdir("uploads", 0777, true);
        }

        $namaBerkas = $_FILES["foto"]["name"];
        $lokasiSementara = $_FILES["foto"]["tmp_name"];
        $namaBaru = uniqid() . "_" . $namaBerkas;
        $lokasiTujuan = "uploads/" . $namaBaru;

        if(move_uploaded_file($lokasiSementara, $lokasiTujuan)){
            $stmt = $koneksi->prepare("INSERT INTO kue (nama, harga, stok, foto) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $nama, $harga, $stok, $namaBaru);

            if($stmt->execute()){
                header("Location: dashboard.php");
                exit();
            }else{
                $pesan = "Gagal menyimpan data: " . $koneksi->error;
            }
        }else{
            $pesan = "Unggah foto gagal!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4>Tambah Kue</h4>

                <?php if($pesan != ""){ ?>
                    <div class="alert alert-danger"><?php echo $pesan; ?></div>
                <?php } ?>

                <form action="tambah_kue.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kue</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
                    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>