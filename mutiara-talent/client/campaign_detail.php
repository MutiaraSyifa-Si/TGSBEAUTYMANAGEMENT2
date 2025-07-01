<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: campaign_history.php');
    exit();
}

$db = require_once('../config/database.php');

try {
    $query = "SELECT * FROM campaigns WHERE id = ? AND client_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id'], $_SESSION['client_id']]);
    $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campaign) {
        header('Location: campaign_history.php');
        exit();
    }
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Campaign - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF69B4;
            --pink-secondary: #FFB6C1;
            --pink-hover: #FF1493;
        }

        body {
            background: linear-gradient(135deg, #FFF0F5 0%, #FFB6C1 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .main-content {
            padding: 2rem;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 105, 180, 0.15);
            padding: 2.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .btn-pink {
            background-color: var(--pink-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-pink:hover {
            background-color: var(--pink-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(255, 105, 180, 0.3);
        }

        .badge {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding: 1rem 0;
        }

        .page-header h2 {
            font-weight: 700;
            color: #333;
            font-size: 2.2rem;
            margin: 0;
        }

        .detail-row {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            font-size: 1.1rem;
        }

        .detail-value {
            font-size: 1.1rem;
            color: #333;
        }

        .bi {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .dashboard-card {
                padding: 1.5rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .detail-row {
                margin-bottom: 1.5rem;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-4 main-content">
                <div class="page-header">
                    <h2><i class="bi bi-clipboard-data"></i>Detail Campaign</h2>
                    <a href="campaign_history.php" class="btn btn-pink">
                        <i class="bi bi-arrow-left"></i>Kembali
                    </a>
                </div>

                <div class="dashboard-card">
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Nama Campaign</div>
                        <div class="col-md-9 detail-value"><?php echo htmlspecialchars($campaign['nama_campaign']); ?>
                        </div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Brand</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($campaign['brand']); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Kategori</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($campaign['kategori']); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Deskripsi</div>
                        <div class="col-md-9"><?php echo nl2br(htmlspecialchars($campaign['deskripsi'])); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Minimum Followers</div>
                        <div class="col-md-9"><?php echo number_format($campaign['min_followers']); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Engagement Rate</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($campaign['engagement_rate']); ?>%</div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Kategori Konten</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($campaign['kategori_konten']); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Lokasi</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($campaign['lokasi']); ?></div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Status</div>
                        <div class="col-md-9">
                            <span
                                class="badge bg-<?php echo $campaign['status'] == 'disetujui' ? 'success' : ($campaign['status'] == 'ditolak' ? 'danger' : 'warning'); ?>">
                                <?php echo htmlspecialchars($campaign['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Tanggal Pengajuan</div>
                        <div class="col-md-9"><?php echo date('d/m/Y', strtotime($campaign['tanggal_pengajuan'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>