<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Koneksi ke database
$db = require_once('../config/database.php');

// Inisialisasi variabel result
$result = null;
$error_message = '';

// Ambil data dokumen dari database dengan penanganan error
try {
    $query = "SELECT d.* 
              FROM documents d 
              ORDER BY d.updated_at DESC";

    $result = $db->query($query);
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokumen - MBeauty Management</title>
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

        .document-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            padding: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        .document-card h5 {
            color: var(--pink-main);
            font-weight: 600;
        }

        .document-card p {
            color: #666;
            font-size: 14px;
        }

        .document-card .text-muted {
            color: #888 !important;
            font-size: 12px;
        }

        .document-card .dropdown-menu {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .document-card .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .document-card .dropdown-item:hover {
            background-color: var(--pink-light);
            color: var(--pink-dark);
        }

        .document-card .bi-file-pdf {
            font-size: 20px;
        }

        .document-card span {
            font-size: 14px;
            color: #444;
        }

        .document-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column p-3">
                    <h4 class="mb-4">Menu</h4>
                    <div class="nav flex-column">
                        <a href="dashboard.php" class="nav-link">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="talents.php" class="nav-link">
                            <i class="bi bi-people"></i> Kelola Talent
                        </a>
                        <a href="campaigns.php" class="nav-link">
                            <i class="bi bi-megaphone"></i> Kelola Campaign
                        </a>
                        <a href="documents.php" class="nav-link active">
                            <i class="bi bi-file-earmark-text"></i> Kelola Dokumen
                        </a>
                        <a href="export.php" class="nav-link">
                            <i class="bi bi-download"></i> Export Data
                        </a>
                        <a href="settings.php" class="nav-link">
                            <i class="bi bi-gear"></i> Pengaturan
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
                    <h2>Kelola Dokumen</h2>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="bi bi-upload"></i> Upload Dokumen
                    </button>
                </div>

                <div class="row">
                    <?php if ($error_message): ?>
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        </div>
                    <?php elseif ($result && $result->rowCount() > 0): ?>
                        <?php while ($doc = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-md-4 mb-4">
                                <div class="document-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($doc['judul']); ?></h5>
                                            <small class="text-muted">Updated:
                                                <?php echo date('Y-m-d', strtotime($doc['updated_at'])); ?></small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="download_document.php?id=<?php echo $doc['id']; ?>">Download</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="editDocument(<?php echo $doc['id']; ?>)">Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="#"
                                                        onclick="deleteDocument(<?php echo $doc['id']; ?>)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-2"><?php echo htmlspecialchars($doc['deskripsi']); ?></p>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-pdf text-danger me-2"></i>
                                        <span><?php echo htmlspecialchars($doc['file_name']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">Belum ada dokumen yang diunggah.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS dan script lainnya -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDocument(id) {
            // Ambil data dokumen menggunakan AJAX
            fetch(`get_document.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    // Isi form edit dengan data yang ada
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_judul').value = data.judul;
                    document.getElementById('edit_deskripsi').value = data.deskripsi;

                    // Tampilkan modal edit
                    new bootstrap.Modal(document.getElementById('editDocumentModal')).show();
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        }

        // Fungsi untuk menangani form edit
        document.getElementById('editDocumentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('update_document.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Dokumen berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        });
    </script>
</body>

</html>

<!-- Modal Edit Document -->
<div class="modal fade" id="editDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDocumentForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Baru (Opsional)</label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>