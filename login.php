<?php
session_start();
include "koneksi.php";

if(isset($_POST["submit"])){
    $username = trim($_POST["username"]);
    $password_asli = $_POST["password"];

    $stmt = $koneksi->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    $stmt->close();

    if($password_hash && password_verify($password_asli, $password_hash)){
        $_SESSION["status_login"] = true;
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else{
        header("Location: login.php?pesan=salah");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css">
</head>
<body class = "bg-light">
    <div class = "container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm" style="width: 350px;">
            <div class="card-body">
                <h4 class="text-center mb-3">Login</h4>

                <?php if(isset($_GET["pesan"]) && $_GET["pesan"] == "salah"){ ?>
                    <div class="alert alert-danger">Username atau Password Salah!</div>
                <?php } ?>

                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3 mb-0">Belum punya akun?<a href="register.php"> Register</a></p>
            </div>
        </div>
    </div>
</body>
</html>
