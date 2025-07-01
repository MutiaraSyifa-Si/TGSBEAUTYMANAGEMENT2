<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$db = require_once __DIR__ . '/../config/database.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fungsi hapus talent
if (isset($_POST['delete_talent'])) {
    $talent_id = $_POST['talent_id'];
    $stmt = $db->prepare("DELETE FROM talents WHERE id = ?");
    $stmt->execute([$talent_id]);
    header('Location: talents.php?msg=deleted');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Talent - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            background-color: var(--pink-sidebar);
            min-height: 100vh;
            color: var(--white-text);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            width: inherit;
            z-index: 100;
        }

        .sidebar .nav-link {
            color: var(--white-text);
            padding: 12px 20px;
            margin: 8px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--pink-hover);
            transform: translateX(5px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            background: linear-gradient(135deg, var(--pink-main) 0%, var(--pink-dark) 100%);
            min-height: 100vh;
            color: var(--white-text);
            padding: 25px;
            margin-left: 16.66667%;
        }

        .card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 25px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pink-main);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }

        .btn-primary {
            background-color: var(--pink-main);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--pink-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #444;
        }

        .table td {
            vertical-align: middle;
            color: #666;
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .btn-action {
            margin: 0 3px;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
                        <a href="talents.php" class="nav-link active">
                            <i class="bi bi-people"></i> Manajemen Talent
                        </a>
                        <a href="campaigns.php" class="nav-link">
                            <i class="bi bi-check-circle"></i> Verifikasi Campaign
                        </a>
                        <a href="documents.php" class="nav-link">
                            <i class="bi bi-file-earmark"></i> Kelola Dokumen
                        </a>
                        <a href="export.php" class="nav-link">
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
                    <h2>Manajemen Talent</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTalentModal">
                        <i class="bi bi-plus-circle"></i> Tambah Talent
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Cari talent...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Kategori</option>
                                    <option value="Nano">Kategori NANO</option>
                                    <option value="Mikro">Kategori MICRO</option>
                                    <option value="Makro">Kategori MACRO</option>
                                    <option value="Mega">Kategori MEGA</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Status</option>
                                    <option>Aktif</option>
                                    <option>Nonaktif</option>
                                    <option>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Talent Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Niche/Konten</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $query = "SELECT * FROM talents ORDER BY id DESC";
                                        $stmt = $db->query($query);
                                        $talents = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (empty($talents)) {
                                            echo '<tr><td colspan="6" class="text-center">Belum ada data talent</td></tr>';
                                        } else {
                                            foreach ($talents as $talent): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($talent['nama']) ?></td>
                                                    <td><?= htmlspecialchars($talent['kategori']) ?></td>
                                                    <td><?= htmlspecialchars($talent['niche']) ?></td>
                                                    <td><?= htmlspecialchars($talent['lokasi']) ?></td>
                                                    <td>
                                                        <span
                                                            class="badge bg-<?= $talent['status'] == 'Aktif' ? 'success' : 'warning' ?>">
                                                            <?= htmlspecialchars($talent['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info btn-action"
                                                            onclick="viewTalent(<?= $talent['id'] ?>)">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning btn-action"
                                                            onclick="editTalent(<?= $talent['id'] ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-action"
                                                            onclick="deleteTalent(<?= $talent['id'] ?>)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                    <?php endforeach;
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Talent Modal -->
    <div class="modal fade" id="addTalentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Talent Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                <option>Nano</option>
                                <option>Mikro</option>
                                <option>Makro</option>
                                <option>Mega</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Niche/Konten</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Talent -->
    <div class="modal fade" id="viewTalentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Talent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewTalentContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Talent -->
    <div class="modal fade" id="editTalentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Talent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editTalentForm">
                        <input type="hidden" id="edit_talent_id" name="talent_id">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="edit_kategori" name="kategori" required>
                                <option value="Nano">Nano</option>
                                <option value="Mikro">Mikro</option>
                                <option value="Makro">Makro</option>
                                <option value="Mega">Mega</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Niche/Konten</label>
                            <input type="text" class="form-control" id="edit_niche" name="niche" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="edit_lokasi" name="lokasi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk melihat detail talent
        function viewTalent(id) {
            fetch(`get_talent.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('viewTalentContent').innerHTML = `
                        <div class="mb-3">
                            <strong>Nama:</strong> ${data.nama}
                        </div>
                        <div class="mb-3">
                            <strong>Kategori:</strong> ${data.kategori}
                        </div>
                        <div class="mb-3">
                            <strong>Niche/Konten:</strong> ${data.niche}
                        </div>
                        <div class="mb-3">
                            <strong>Lokasi:</strong> ${data.lokasi}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> ${data.status}
                        </div>
                    `;
                    new bootstrap.Modal(document.getElementById('viewTalentModal')).show();
                });
        }

        // Fungsi untuk mengedit talent
        function editTalent(id) {
            fetch(`get_talent.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_talent_id').value = data.id;
                    document.getElementById('edit_nama').value = data.nama;
                    document.getElementById('edit_kategori').value = data.kategori;
                    document.getElementById('edit_niche').value = data.niche;
                    document.getElementById('edit_lokasi').value = data.lokasi;
                    document.getElementById('edit_status').value = data.status;
                    new bootstrap.Modal(document.getElementById('editTalentModal')).show();
                });
        }

        // Fungsi untuk menghapus talent
        function deleteTalent(id) {
            if (confirm('Apakah Anda yakin ingin menghapus talent ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="delete_talent" value="1">
                    <input type="hidden" name="talent_id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Handle form edit
        document.getElementById('editTalentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('update_talent.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengupdate data talent');
                    }
                });
        });
    </script>
</body>

</html>