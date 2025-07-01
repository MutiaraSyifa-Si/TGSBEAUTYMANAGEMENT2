<?php
session_start();

// Cek apakah user sudah login sebagai client
if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

// Koneksi ke database
$db = require_once('../config/database.php');

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $query = "INSERT INTO talents (client_id, nama, kategori, niche, lokasi, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $_SESSION['client_id'],
            $_POST['nama'],
            $_POST['kategori'],
            $_POST['niche'],
            $_POST['lokasi']
        ]);

        header('Location: dashboard.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Talent - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-dark: #FF1493;
            --pink-light: #FFB6C1;
            --pink-main: #E91E63;
            --pink-hover: #F06292;
            --white-text: #FFFFFF;
        }

        body {
            background: linear-gradient(135deg, var(--pink-main) 0%, var(--pink-dark) 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--pink-main);
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .card-header h4 {
            color: var(--white-text);
            font-weight: 600;
            font-size: 24px;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            color: #333;
            font-weight: 500;
            font-size: 16px;
        }

        .form-control,
        .form-select {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pink-main);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }

        .btn-pink {
            background-color: var(--pink-main);
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-pink text-white">
                        <h4 class="mb-0">Daftar Sebagai Talent</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    <option value="Nano">Kategori NANO</option>
                                    <option value="Mikro">Kategori MICRO</option>
                                    <option value="Makro">Kategori MACRO</option>
                                    <option value="Mega">Kategori MEGA</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="niche" class="form-label">Niche/Konten</label>
                                <input type="text" class="form-control" id="niche" name="niche" required>
                            </div>

                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-pink">Daftar Sebagai Talent</button>
                                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>