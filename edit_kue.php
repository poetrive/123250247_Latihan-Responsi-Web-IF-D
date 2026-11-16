<?php
session_start();
if(!isset($_SESSION["status_login"]) || $_SESSION["status_login"] !== true){
    header("Location: login.php");
    exit();
}
include "koneksi.php";

$id = $_GET["id"];
$stmt = $koneksi->prepare("SELECT * FROM kue WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hasil = $stmt->get_result();
$data = $hasil->fetch_assoc();
$stmt->close();

if(!$data){
    header("Location: dashboard.php");
    exit();
}

$pesan = "";

if(isset($_POST["submit"])){
    $nama  = trim($_POST["nama"]);                
    $harga = $_POST["harga"];
    $stok  = $_POST["stok"];
    $foto  = $data["foto"];

    if($nama == ""){
        $pesan = "Nama kue tidak boleh kosong.";
    }else{
        if($_FILES["foto"]["name"] != ""){
            if(!is_dir("uploads")){              
                mkdir("uploads", 0777, true);
            }
            $fotoBaru = uniqid() . "_" . $_FILES["foto"]["name"];

            if(move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $fotoBaru)){
                if(file_exists("uploads/" . $data["foto"])){
                    unlink("uploads/" . $data["foto"]);
                }
                $foto = $fotoBaru;
            }else{
                $pesan = "Unggah foto gagal.";
            }
        }

        if($pesan == ""){
            $stmt = $koneksi->prepare("UPDATE kue SET nama = ?, harga = ?, stok = ?, foto = ? WHERE id = ?");
            $stmt->bind_param("siisi", $nama, $harga, $stok, $foto, $id);

            if($stmt->execute()){
                header("Location: dashboard.php");
                exit();
            }else{
                $pesan = "Gagal memperbarui data: " . $koneksi->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4>Edit Kue</h4>

                <?php if($pesan != ""){ ?>
                    <div class="alert alert-danger"><?php echo $pesan; ?></div>
                <?php } ?>

                <form action="edit_kue.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Foto Lama:</label><br>
                        <img src="uploads/<?php echo $data['foto']; ?>" width="120" alt="Foto lama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kue</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?php echo $data['harga']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?php echo $data['stok']; ?>" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>