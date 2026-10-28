<?php
session_start();

include "koneksi.php";

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users
        WHERE username='$username'
        AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit;

    } else {

        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Gudang Material</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #fff8fb, #ffeaf3);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-box {
            background: #ffffff;
            border: 1px solid #f0d7e2;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(217, 75, 145, 0.10);
        }

        /* LOGO / JUDUL */

        .logo {
            width: 65px;
            height: 65px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #fff0f6;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            color: #d94b91;
        }

        .login-box h1 {
            text-align: center;
            color: #4f434b;
            font-size: 23px;
            margin-bottom: 6px;
        }

        .login-box .subtitle {
            text-align: center;
            color: #9b8992;
            font-size: 13px;
            margin-bottom: 28px;
        }

        /* ERROR */

        .error {
            background: #fff0f0;
            border: 1px solid #f0c8cf;
            color: #c34f5f;
            padding: 11px 13px;
            border-radius: 8px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 13px;
        }

        /* FORM */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #62545d;
            font-size: 13px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #e8cbd8;
            border-radius: 9px;
            background: #fffdfd;
            color: #5f5360;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: #d94b91;
            box-shadow: 0 0 0 3px rgba(217, 75, 145, 0.08);
        }

        .form-group input::placeholder {
            color: #b6a5ad;
        }

        /* BUTTON */

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 9px;
            background: #d94b91;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 5px;
        }

        .btn-login:hover {
            background: #c63d82;
        }

        /* FOOTER */

        .footer {
            text-align: center;
            margin-top: 23px;
            padding-top: 18px;
            border-top: 1px solid #f3e1e8;
            color: #a08e97;
            font-size: 11px;
        }

        .footer strong {
            color: #d94b91;
        }

        /* RESPONSIVE */

        @media (max-width: 500px) {

            .login-box {
                padding: 28px 22px;
            }

            .login-box h1 {
                font-size: 21px;
            }
        }
    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-box">

        <div class="logo">
            🏭
        </div>

        <h1>Gudang Material</h1>

        <p class="subtitle">
            Sistem Informasi Pengelolaan Barang
        </p>

        <?php if ($error != "") { ?>

            <div class="error">
                <?= $error; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    required
                    autocomplete="username"
                >

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                >

            </div>

            <button
                type="submit"
                name="login"
                class="btn-login"
            >
                Login
            </button>

        </form>

        <div class="footer">
            Sistem Informasi Gudang Material<br>
            <strong>PT Sinergi Gula Nusantara</strong>
        </div>

    </div>

</div>

</body>

</html>