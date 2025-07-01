<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fungsi untuk menghasilkan data dummy (ganti dengan data dari database nanti)
function getDummyData($type)
{
    switch ($type) {
        case 'talents':
            return [
                ['id' => 1, 'nama' => 'Sarah Lee', 'kategori' => 'Nano', 'niche' => 'Beauty', 'lokasi' => 'Jakarta'],
                ['id' => 2, 'nama' => 'David Wong', 'kategori' => 'Mikro', 'niche' => 'Food', 'lokasi' => 'Bandung'],
                ['id' => 3, 'nama' => 'Emma Johnson', 'kategori' => 'Makro', 'niche' => 'Lifestyle', 'lokasi' => 'Surabaya']
            ];
        case 'campaigns':
            return [
                ['id' => 1, 'nama_campaign' => 'Beauty Product Launch', 'brand' => 'Glamora', 'status' => 'Active'],
                ['id' => 2, 'nama_campaign' => 'Food Review Series', 'brand' => 'FoodieHub', 'status' => 'Pending'],
                ['id' => 3, 'nama_campaign' => 'Tech Gadget Review', 'brand' => 'TechZone', 'status' => 'Completed']
            ];
        default:
            return [];
    }
}

// Handle export request
if (isset($_POST['export'])) {
    $type = $_POST['type'];
    $format = $_POST['format'];
    $data = getDummyData($type);

    if ($format === 'excel') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $type . '_export.xls"');

        echo "<table border='1'>";
        // Header
        echo "<tr>";
        foreach (array_keys($data[0]) as $key) {
            echo "<th>" . ucfirst($key) . "</th>";
        }
        echo "</tr>";

        // Data
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }

    if ($format === 'pdf') {
        // Tambahkan library PDF jika diperlukan
        header('Content-Type: application/pdf');
        // Implementasi export PDF
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data - Beauty Management</title>
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

        .export-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            padding: 25px;
            margin-bottom: 20px;
            color: #333;
            /* Menambahkan warna teks gelap untuk konten dalam card */
        }

        .export-card h4 {
            color: var(--pink-main);
            margin-bottom: 20px;
        }

        .form-label {
            color: #333;
            font-weight: 500;
        }

        .table {
            color: #333;
            /* Warna teks untuk konten tabel */
        }

        .table th {
            background-color: rgba(255, 182, 193, 0.3);
            color: var(--pink-dark);
            font-weight: 600;
        }

        .table td {
            color: #333;
        }

        /* Memperbaiki warna teks untuk elemen form */
        .form-select,
        .form-control {
            color: #333;
        }

        /* Memperbaiki warna teks untuk status */
        .status-badge {
            color: var(--white-text);
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-success {
            background-color: #28a745;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
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
                        <p class="mb-0">Admin Dashboard</p>
                    </div>
                    <div class="nav flex-column">
                        <a href="dashboard.php" class="nav-link">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="talents.php" class="nav-link">
                            <i class="bi bi-people"></i> Manajemen Talent
                        </a>
                        <a href="campaigns.php" class="nav-link">
                            <i class="bi bi-check-circle"></i> Verifikasi Campaign
                        </a>
                        <a href="documents.php" class="nav-link">
                            <i class="bi bi-file-earmark"></i> Kelola Dokumen
                        </a>
                        <a href="export.php" class="nav-link active">
                            <i class="bi bi-download"></i> Export Data
                        </a>
                        <a href="settings.php" class="nav-link">
                            <i class="bi bi-gear"></i> Pengaturan Akun
                        </a>
                        <a href="../logout.php" class="nav-link">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Export Data</h2>
                    <div class="user-info">
                        <span>Welcome,
                            <?php echo isset($_SESSION["user_email"]) ? htmlspecialchars($_SESSION["user_email"]) : 'Admin'; ?></span>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="export-card">
                            <h4 class="mb-4">Export Data Talent</h4>
                            <form method="POST">
                                <input type="hidden" name="type" value="talents">
                                <div class="mb-3">
                                    <label class="form-label">Format Export</label>
                                    <select name="format" class="form-select mb-3">
                                        <option value="excel">Excel (.xls)</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                                <button type="submit" name="export" class="btn btn-primary">
                                    <i class="bi bi-download"></i> Export Data Talent
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="export-card">
                            <h4 class="mb-4">Export Data Campaign</h4>
                            <form method="POST">
                                <input type="hidden" name="type" value="campaigns">
                                <div class="mb-3">
                                    <label class="form-label">Format Export</label>
                                    <select name="format" class="form-select mb-3">
                                        <option value="excel">Excel (.xls)</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                                <button type="submit" name="export" class="btn btn-primary">
                                    <i class="bi bi-download"></i> Export Data Campaign
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Export History -->
                <div class="export-card mt-4">
                    <h4 class="mb-4">Riwayat Export</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe Data</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-20 14:30</td>
                                    <td>Data Talent</td>
                                    <td>Excel</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-19 10:15</td>
                                    <td>Data Campaign</td>
                                    <td>PDF</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>
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