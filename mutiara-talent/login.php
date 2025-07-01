<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Beauty Management</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .site-title {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }

        .form-label {
            color: #666;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 105, 180, 0.25);
        }

        .btn-primary {
            background-color: var(--pink-primary);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
        }

        .form-check-input:checked {
            background-color: var(--pink-primary);
            border-color: var(--pink-primary);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1 class="site-title">Beauty Management</h1>
        <div class="login-card">
            <h2 class="text-center mb-4" style="color: var(--pink-primary);">Login</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    if ($_GET['error'] == 1) {
                        echo "Email atau password salah";
                    } else {
                        echo "Terjadi kesalahan sistem";
                    }
                    ?>
                </div>
            <?php endif; ?>
            <form action="auth.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check text-start">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log in</button>
            </form>
            <!-- Tambahkan di bawah form login -->
            <div class="text-center mt-3">
                <a href="client/register.php" class="text-decoration-none" style="color: var(--pink-primary);">Belum
                    punya akun? Daftar sebagai client</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>