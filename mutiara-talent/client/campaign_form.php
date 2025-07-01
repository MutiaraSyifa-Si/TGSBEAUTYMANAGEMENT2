<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

$db = require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Di bagian PHP
    try {
        $query = "INSERT INTO campaigns (nama_campaign, brand, kategori, deskripsi, min_followers, 
                 engagement_rate, kategori_konten, lokasi, status, tanggal_pengajuan, client_id, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'menunggu', CURDATE(), ?, NOW())";
        $stmt = $db->prepare($query);

        // Tambahkan debug logging
        error_log('Data yang akan disimpan:');
        error_log('Deskripsi: ' . $_POST['deskripsi']);
        error_log('Client ID: ' . $_SESSION['client_id']);

        $stmt->execute([
            $_POST['title'],           // nama_campaign
            $_POST['brand'],           // brand
            $_POST['kategori'],        // kategori
            $_POST['deskripsi'],       // deskripsi
            $_POST['min_followers'],   // min_followers
            $_POST['engagement_rate'], // engagement_rate
            $_POST['kategori_konten'], // kategori_konten
            $_POST['lokasi'],         // lokasi
            $_SESSION['client_id']     // client_id
        ]);

        // Tambahkan debug
        error_log('Campaign berhasil dibuat untuk client_id: ' . $_SESSION['client_id']);

        $_SESSION['success_message'] = 'Campaign berhasil dibuat';
        header('Location: campaign_history.php');
        exit();
    } catch (PDOException $e) {
        error_log('Error saat membuat campaign: ' . $e->getMessage());
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Campaign - Beauty Management</title>
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
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-label {
            color: #333;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 8px 12px;
        }

        .form-control:focus {
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 105, 180, 0.25);
        }

        .btn-pink {
            background-color: var(--pink-primary);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column">
                    <div class="p-3 text-center">
                        <h4>Mutiara Talent</h4>
                        <p class="mb-0">Client Dashboard</p>
                    </div>
                    <div class="nav flex-column">
                        <a href="dashboard.php" class="nav-link">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                        <a href="campaign_form.php" class="nav-link active">
                            <i class="bi bi-plus-circle"></i> Buat Campaign
                        </a>
                        <a href="campaign_history.php" class="nav-link">
                            <i class="bi bi-clock-history"></i> Riwayat Campaign
                        </a>
                        <a href="talents.php" class="nav-link">
                            <i class="bi bi-people"></i> Lihat Talent
                        </a>
                        <a href="../logout.php" class="nav-link">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="text-white mb-4">Buat Campaign Baru</h2>

                <div class="card">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger mb-3">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="campaign_form.php">
                        <div class="mb-3">
                            <label for="title" class="form-label">Nama Campaign</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="NANO">Kategori NANO</option>
                                <option value="MICRO">Kategori MICRO</option>
                                <option value="MACRO">Kategori MACRO</option>
                                <option value="MEGA">Kategori MEGA</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="min_followers" class="form-label">Minimum Followers</label>
                            <input type="text" class="form-control" id="min_followers" name="min_followers" required>
                        </div>

                        <div class="mb-3">
                            <label for="engagement_rate" class="form-label">Engagement Rate</label>
                            <input type="text" class="form-control" id="engagement_rate" name="engagement_rate"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori_konten" class="form-label">Kategori Konten</label>
                            <input type="text" class="form-control" id="kategori_konten" name="kategori_konten"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-pink">Buat Campaign</button>
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>