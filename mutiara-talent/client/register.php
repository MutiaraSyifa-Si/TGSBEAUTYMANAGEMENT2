<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    // Validasi input
    if (empty($name)) {
        $errors[] = "Nama harus diisi";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok";
    }

    // Cek apakah email sudah terdaftar
    try {
        $stmt = $db->prepare("SELECT COUNT(*) FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email sudah terdaftar";
        }
    } catch (PDOException $e) {
        $errors[] = "Terjadi kesalahan sistem";
    }

    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO clients (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
            header("Location: ../login.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Gagal menyimpan data";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Client - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-dark: #FF1493;
            --pink-light: #FFB6C1;
            --pink-hover: #F06292;
        }

        body {
            background: linear-gradient(135deg, var(--pink-primary) 0%, var(--pink-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .site-title {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .btn-pink {
            background-color: var(--pink-primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 500;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
            color: white;
        }

        .login-link {
            color: white;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: var(--pink-light);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h1 class="site-title">Mutiara Talent</h1>
        <div class="register-card">
            <h2 class="text-center mb-4" style="color: var(--pink-primary);">Registrasi Client</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password"
                        placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" class="btn btn-pink">Daftar</button>
            </form>
        </div>
        <a href="../login.php" class="login-link">Sudah punya akun? Login di sini</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>