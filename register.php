<?php
include "koneksi.php";

$pesan = "";

if(isset($_POST["submit"])){
    $username = trim($_POST["username"]);
    $password_asli = $_POST["password"];

    $stmt = $koneksi->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($cek);
    $stmt->fetch();
    $stmt->close();

    if($username == ""){
        $pesan = "Username Tidak Boleh Kosong!";
    }else if($cek){
        $pesan = "Username Sudah Dipakai!";
    }else{
        $password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

        $stmt = $koneksi->prepare("INSERT INTO users(username, password) VALUES(?,?)");
        $stmt->bind_param("ss", $username, $password_hash);

        if($stmt->execute()){
            header("Location: login.php");
            exit();
        }else{
            $pesan = "Pendaftaran Gagal : " . $koneksi->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
</head>
<body class = "bg-light">
    <div class = "container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm" style="width: 350px;">
            <div class="card-body">
                <h4 class="text-center mb-3">Register</h4>

                <?php if($pesan != ""){ ?>
                    <div class="alert alert-danger"><?php echo $pesan; ?></div>
                <?php } ?>

                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success w-100">Register</button>
                </form>

                <p class="text-center mt-3 mb-0">Sudah punya akun?<a href="login.php"> Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
