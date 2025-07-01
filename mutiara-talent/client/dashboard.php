<?php
session_start();

// Cek apakah user sudah login sebagai client
if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

// Koneksi ke database
$db = require_once('../config/database.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - Beauty Management</title>
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

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            padding: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card h5 {
            color: var(--pink-main);
            font-weight: 600;
        }

        .dashboard-card .icon {
            font-size: 2rem;
            color: var(--pink-main);
            margin-bottom: 15px;
        }

        .btn-pink {
            background-color: var(--pink-main);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
            color: white;
        }

        <style>.btn-outline-pink {
            color: var(--pink-main);
            border-color: var(--pink-main);
        }

        .btn-outline-pink:hover {
            background-color: var(--pink-main);
            color: white;
        }
    </style>
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <a class="navbar-brand text-white p-3 text-center" href="dashboard.php">
                        <h4>Mutiara Talent</h4>
                    </a>
                    <div class="nav flex-column">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="campaign_form.php">
                            <i class="bi bi-file-earmark-text me-2"></i> Isi Form Campaign
                        </a>
                        <a class="nav-link" href="talents.php">
                            <i class="bi bi-people me-2"></i> Cari Talent
                        </a>
                        <a class="nav-link" href="campaign_history.php">
                            <i class="bi bi-clock-history me-2"></i> Riwayat Campaign
                        </a>
                        <a class="nav-link" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-md-4 main-content">
                <h2 class="text-white mb-4">Dashboard</h2>
                <!-- Card Section -->
                <div class="row">
                    <!-- Campaign Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="dashboard-card text-center">
                            <i class="bi bi-file-earmark-text icon"></i>
                            <h5>Campaign</h5>
                            <p>Buat dan kelola campaign Anda</p>
                            <a href="campaign_form.php" class="btn btn-pink">Buat Campaign</a>
                        </div>
                    </div>

                    <!-- Talent Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="dashboard-card text-center">
                            <i class="bi bi-people icon"></i>
                            <h5>Talent</h5>
                            <p>Cari dan lihat detail talent</p>
                            <div class="d-grid gap-2">
                                <a href="register_talent.php" class="btn btn-pink">Daftar Sebagai Talent</a>
                                <a href="talents.php" class="btn btn-outline-pink">Cari Talent</a>
                            </div>
                        </div>
                    </div>

                    <!-- History Card -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="dashboard-card text-center">
                            <i class="bi bi-clock-history icon"></i>
                            <h5>Riwayat</h5>
                            <p>Lihat riwayat campaign Anda</p>
                            <a href="campaign_history.php" class="btn btn-pink">Lihat Riwayat</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Campaigns -->
                <div class="dashboard-card mt-4">
                    <h5 class="mb-4">Campaign Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Judul Campaign</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $query = "SELECT * FROM campaigns WHERE client_id = ? ORDER BY created_at DESC LIMIT 5";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute([$_SESSION['client_id']]);

                                    while ($campaign = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$campaign['nama_campaign']}</td>";
                                        echo "<td>" . date('d/m/Y', strtotime($campaign['tanggal_pengajuan'])) . "</td>";
                                        echo "<td><span class='badge bg-" . ($campaign['status'] == 'disetujui' ? 'success' : 'warning') . "'>{$campaign['status']}</span></td>";
                                        echo "<td><a href='campaign_detail.php?id={$campaign['id']}' class='btn btn-sm btn-pink'>Detail</a></td>";
                                        echo "</tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='4' class='text-center'>Tidak ada data campaign</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>