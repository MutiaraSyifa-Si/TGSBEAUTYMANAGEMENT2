<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: var(--pink-dark);
            margin: 10px 0;
        }

        .stat-label {
            color: #6c757d;
            font-weight: 500;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.95);
            border-bottom: 2px solid var(--pink-light);
        }

        .activity-table th {
            background-color: var(--pink-light);
            color: #333;
            border: none;
        }

        .activity-table td {
            background-color: rgba(255, 255, 255, 0.95);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #FFC107;
            color: #000;
        }

        .status-completed {
            background-color: #4CAF50;
            color: white;
        }

        .status-processing {
            background-color: var(--pink-primary);
            color: white;
        }

        /* Tambahan untuk header welcome */
        .welcome-text {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">Beauty Management</h4>
                    <h6 class="text-center mb-4">Admin Dashboard</h6>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link" href="talents.php">
                        <i class="fas fa-users"></i> Manajemen Talent
                    </a>
                    <a class="nav-link" href="campaigns.php">
                        <i class="fas fa-bullhorn"></i> Verifikasi Campaign
                    </a>
                    <a class="nav-link" href="documents.php">
                        <i class="fas fa-file-alt"></i> Kelola Dokumen
                    </a>
                    <a class="nav-link" href="export.php">
                        <i class="fas fa-download"></i> Export Data
                    </a>
                    <a class="nav-link" href="settings.php">
                        <i class="fas fa-cog"></i> Pengaturan Akun
                    </a>
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <div class="text-end">
                        Welcome, <?php echo htmlspecialchars($_SESSION['admin_email']); ?>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-label">Total Talent</div>
                                <div class="stat-value">150</div>
                                <div class="stat-change positive-change">+12% dari bulan lalu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-label">Campaign Aktif</div>
                                <div class="stat-value">24</div>
                                <div class="stat-change">8 menunggu verifikasi</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-label">Total Client</div>
                                <div class="stat-value">45</div>
                                <div class="stat-change positive-change">+5% dari bulan lalu</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-label">Pendapatan</div>
                                <div class="stat-value">Rp 125M</div>
                                <div class="stat-change positive-change">+18% dari bulan lalu</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table activity-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Ambil data aktivitas dari database
                                    $stmt = $db->query("SELECT * FROM activities ORDER BY tanggal DESC LIMIT 10");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['aktivitas']) . "</td>";
                                        echo "<td>";
                                        $statusClass = '';
                                        switch ($row['status']) {
                                            case 'Menunggu Verifikasi':
                                                $statusClass = 'status-pending';
                                                break;
                                            case 'Selesai':
                                                $statusClass = 'status-completed';
                                                break;
                                            case 'Diproses':
                                                $statusClass = 'status-processing';
                                                break;
                                        }
                                        echo "<span class='status-badge $statusClass'>" . htmlspecialchars($row['status']) . "</span>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<form method='POST' action='update_status.php' class='d-inline'>";
                                        echo "<input type='hidden' name='activity_id' value='" . $row['id'] . "'>";
                                        echo "<select name='new_status' class='form-select form-select-sm d-inline-block w-auto me-2'>";
                                        echo "<option value='Menunggu Verifikasi'" . ($row['status'] == 'Menunggu Verifikasi' ? ' selected' : '') . ">Menunggu Verifikasi</option>";
                                        echo "<option value='Selesai'" . ($row['status'] == 'Selesai' ? ' selected' : '') . ">Selesai</option>";
                                        echo "<option value='Diproses'" . ($row['status'] == 'Diproses' ? ' selected' : '') . ">Diproses</option>";
                                        echo "</select>";
                                        echo "<button type='submit' class='btn btn-sm btn-pink'>Update</button>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <style>
                    /* Tambahkan style untuk tombol update */
                    .btn-pink {
                        background-color: var(--pink-primary);
                        color: white;
                        border: none;
                    }

                    .btn-pink:hover {
                        background-color: var(--pink-dark);
                        color: white;
                    }

                    .form-select-sm {
                        font-size: 14px;
                        padding: 0.25rem 2rem 0.25rem 0.5rem;
                    }
                </style>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>