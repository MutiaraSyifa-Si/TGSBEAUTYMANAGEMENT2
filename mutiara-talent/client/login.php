<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM clients WHERE email = ?");
            $stmt->execute([$email]);
            $client = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($client && password_verify($password, $client['password'])) {
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['client_name'] = $client['name'];
                $_SESSION['client_email'] = $client['email'];

                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $stmt = $db->prepare("UPDATE clients SET remember_token = ? WHERE id = ?");
                    $stmt->execute([$token, $client['id']]);
                    setcookie('remember_client', $token, time() + 30 * 24 * 60 * 60, '/');
                }

                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Email atau password salah';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-dark: #FF1493;
            --pink-light: #FFB6C1;
        }

        body {
            background: linear-gradient(135deg, var(--pink-light) 0%, var(--pink-dark) 100%);
            min-height: 100vh;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-pink {
            background-color: var(--pink-primary);
            border-color: var(--pink-primary);
            color: white;
        }

        .btn-pink:hover {
            background-color: var(--pink-dark);
            border-color: var(--pink-dark);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="text-white">Mutiara Talent</h2>
                    <h4 class="text-white-50">Client Portal</h4>
                </div>
                <div class="card login-card">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Login Client</h4>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-pink">Login</button>
                                <a href="../index.php" class="btn btn-light">Kembali ke Beranda</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>