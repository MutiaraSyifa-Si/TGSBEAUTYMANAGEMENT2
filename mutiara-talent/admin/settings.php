<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Proses update profil jika ada POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // TODO: Validasi input
        $name = $_POST['name'];
        $email = $_POST['email'];

        // TODO: Update ke database
        $_SESSION['success_message'] = 'Profil berhasil diperbarui';
        header('Location: settings.php');
        exit();
    }

    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // TODO: Validasi password dan update ke database
        if ($new_password === $confirm_password) {
            $_SESSION['success_message'] = 'Password berhasil diubah';
        } else {
            $_SESSION['error_message'] = 'Password baru tidak cocok';
        }
        header('Location: settings.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun Admin - MBeauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-dark: #FF1493;
            --pink-light: #FFB6C1;
            --pink-sidebar: #D81B60;
            --pink-main: #E91E63;
            --pink-hover: #F06292;
            --white-text: #FFFFFF;
        }

        .sidebar {
            background-color: var(--pink-sidebar);
            min-height: 100vh;
            color: var(--white-text);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: var(--white-text);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--pink-hover);
            transform: translateX(5px);
        }

        .main-content {
            background: linear-gradient(135deg, var(--pink-main) 0%, var(--pink-dark) 100%);
            min-height: 100vh;
            color: var(--white-text);
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--pink-light);
            border-bottom: none;
            border-radius: 12px 12px 0 0;
        }

        .card-title {
            color: var(--pink-main);
            font-weight: 600;
        }

        .form-label {
            color: #666;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--pink-main);
            border-color: var(--pink-main);
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--pink-dark);
            border-color: var(--pink-dark);
            transform: translateY(-2px);
        }

        .navbar {
            background-color: var(--pink-sidebar) !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: var(--white-text);
            transition: all 0.3s ease;
        }

        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link.active {
            color: var(--pink-light);
        }
    </style>
</head>

<body>
    <!-- Navbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="talents.php">Manajemen Talent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="campaigns.php">Verifikasi Campaign</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="documents.php">Kelola Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="export.php">Ekspor Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="settings.php">Pengaturan</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="mb-4">Pengaturan Akun Admin</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Update Profil -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Update Profil</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="settings.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo $_SESSION['admin_name'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $_SESSION['admin_email'] ?? ''; ?>" required>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profil</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ganti Password -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ganti Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="settings.php">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password"
                                    name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-primary">Ganti Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>