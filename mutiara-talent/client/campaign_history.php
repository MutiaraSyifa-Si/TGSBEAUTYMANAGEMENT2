<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

$db = require_once('../config/database.php');

try {
    // Debug session
    error_log('Debug - Client ID in session: ' . (isset($_SESSION['client_id']) ? $_SESSION['client_id'] : 'not set'));

    // Ubah query untuk hanya mengambil kolom yang ditampilkan di tabel
    $query = "SELECT id, nama_campaign, brand, kategori, status, tanggal_pengajuan 
             FROM campaigns 
             WHERE client_id = ? 
             ORDER BY tanggal_pengajuan DESC";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['client_id']]);
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug hasil query
    error_log('Debug - Number of campaigns found: ' . count($campaigns));
    error_log('Debug - Query result: ' . print_r($campaigns, true));

    if (empty($campaigns)) {
        $error_message = "Belum ada campaign yang dibuat";
    }
} catch (PDOException $e) {
    error_log('Error in campaign_history.php: ' . $e->getMessage());
    $error_message = "Error: " . $e->getMessage();
    $campaigns = [];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Campaign - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-secondary: #FFB6C1;
            --pink-hover: #FF1493;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #f8d7da 100%);
            min-height: 100vh;
        }

        .main-content {
            padding: 2rem;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--pink-primary);
            color: white;
            border: none;
            padding: 1rem;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 182, 193, 0.1);
            transition: all 0.3s ease;
        }

        .btn-pink {
            background-color: var(--pink-primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--pink-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 105, 180, 0.3);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar sama dengan dashboard.php -->

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-md-4 main-content">
                <div class="page-header">
                    <h2 class="text-dark">Riwayat Campaign</h2>
                    <a href="dashboard.php" class="btn btn-pink">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="dashboard-card">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Campaign</th>
                                    <th>Brand</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($campaigns)): ?>
                                    <?php foreach ($campaigns as $campaign): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($campaign['nama_campaign']); ?></td>
                                            <td><?php echo htmlspecialchars($campaign['brand']); ?></td>
                                            <td><?php echo htmlspecialchars($campaign['kategori']); ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo $campaign['status'] == 'disetujui' ? 'success' : ($campaign['status'] == 'ditolak' ? 'danger' : 'warning'); ?>">
                                                    <?php echo htmlspecialchars($campaign['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($campaign['tanggal_pengajuan'])); ?></td>
                                            <td>
                                                <a href="campaign_detail.php?id=<?php echo $campaign['id']; ?>"
                                                    class="btn btn-sm btn-pink">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada campaign yang dibuat</td>
                                    </tr>
                                <?php endif; ?>
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