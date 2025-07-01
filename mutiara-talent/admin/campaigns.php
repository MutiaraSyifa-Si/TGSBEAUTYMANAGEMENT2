<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Tambahkan setelah session_start()
require_once '../config/database.php';

// Query untuk mengambil data campaign
$stmt = $db->query("SELECT * FROM campaigns ORDER BY tanggal_pengajuan DESC");
$campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Campaign - Mutiara Talent</title>
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
                        <a href="campaigns.php" class="nav-link active">
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
                    <h2>Verifikasi Campaign</h2>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Cari campaign...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Status</option>
                                    <option>Menunggu Verifikasi</option>
                                    <option>Disetujui</option>
                                    <option>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campaign List -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Campaign</th>
                                        <th>Brand</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($campaigns as $campaign): ?>
                                        <tr>
                                            <td><?php echo $campaign['tanggal_pengajuan']; ?></td>
                                            <td><?php echo $campaign['nama_campaign']; ?></td>
                                            <td><?php echo $campaign['brand']; ?></td>
                                            <td><?php echo $campaign['kategori']; ?></td>
                                            <td>
                                                <span class="badge bg-warning status-badge"
                                                    data-campaign-id="<?php echo $campaign['id']; ?>">
                                                    <?php
                                                    $status = $campaign['status'] ?? 'menunggu';
                                                    $badge_class = $status === 'disetujui' ? 'bg-success' : ($status === 'ditolak' ? 'bg-danger' : 'bg-warning');
                                                    echo "<span class='badge $badge_class status-badge'>$status</span>";
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#viewCampaignModal"
                                                    onclick="viewCampaign(<?php echo $campaign['id']; ?>)">
                                                    <i class="bi bi-check-circle"></i> Verifikasi
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Campaign Modal -->
    <div class="modal fade" id="viewCampaignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient"
                    style="background: linear-gradient(135deg, var(--pink-main) 0%, var(--pink-dark) 100%); color: white;">
                    <h5 class="modal-title">Detail Campaign</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Campaign Info Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Nama Campaign -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color: rgba(233, 30, 99, 0.1);">
                                        <h6 class="text-pink mb-2" style="color: var(--pink-main);"><i
                                                class="bi bi-megaphone me-2"></i>Nama Campaign</h6>
                                        <p class="h5 mb-0">Beauty Product Launch</p>
                                    </div>
                                </div>
                                <!-- Brand -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color: rgba(233, 30, 99, 0.1);">
                                        <h6 class="text-pink mb-2" style="color: var(--pink-main);"><i
                                                class="bi bi-briefcase me-2"></i>Brand</h6>
                                        <p class="h5 mb-0">GlamGlow</p>
                                    </div>
                                </div>
                                <!-- Kategori -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color: rgba(233, 30, 99, 0.1);">
                                        <h6 class="text-pink mb-2" style="color: var(--pink-main);"><i
                                                class="bi bi-tag me-2"></i>Kategori</h6>
                                        <p class="h5 mb-0">Beauty</p>
                                    </div>
                                </div>
                                <!-- Tanggal Pengajuan -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color: rgba(233, 30, 99, 0.1);">
                                        <h6 class="text-pink mb-2" style="color: var(--pink-main);"><i
                                                class="bi bi-calendar-event me-2"></i>Tanggal Pengajuan</h6>
                                        <p class="h5 mb-0">2024-01-20</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Campaign -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-pink mb-3" style="color: var(--pink-main);"><i
                                    class="bi bi-file-text me-2"></i>Deskripsi Campaign</h6>
                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                                tempor incididunt ut
                                labore et dolore magna aliqua.</p>
                        </div>
                    </div>

                    <!-- Persyaratan -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-pink mb-3" style="color: var(--pink-main);"><i
                                    class="bi bi-list-check me-2"></i>Persyaratan</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-people-fill me-2 text-pink" style="color: var(--pink-main);"></i>
                                    <span>Minimal followers: 10.000</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-graph-up me-2 text-pink" style="color: var(--pink-main);"></i>
                                    <span>Engagement rate: >2%</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-camera me-2 text-pink" style="color: var(--pink-main);"></i>
                                    <span>Kategori konten: Beauty, Lifestyle</span>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-2 text-pink" style="color: var(--pink-main);"></i>
                                    <span>Lokasi: Jakarta, Bandung</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success"
                        onclick="updateCampaignStatus('disetujui', campaignId)"><i
                            class="bi bi-check-lg me-2"></i>Setujui</button>
                    <button type="button" class="btn btn-danger"
                        onclick="updateCampaignStatus('ditolak', campaignId)"><i
                            class="bi bi-x-lg me-2"></i>Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Campaign Modal -->
    <div class="modal fade" id="editCampaignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient"
                    style="background: linear-gradient(135deg, var(--pink-main) 0%, var(--pink-dark) 100%); color: white;">
                    <h5 class="modal-title">Edit Campaign</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editCampaignForm">
                        <div class="mb-3">
                            <label class="form-label">Nama Campaign</label>
                            <input type="text" class="form-control" name="nama_campaign" value="Beauty Product Launch">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" value="GlamGlow">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" value="Beauty">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Campaign</label>
                            <textarea class="form-control" name="deskripsi"
                                rows="4">GlamGlow menghadirkan rangkaian produk skincare terbaru yang mengkombinasikan bahan-bahan alami dan teknologi modern. Campaign ini bertujuan untuk memperkenalkan manfaat dan keunggulan produk kepada target market melalui konten kreatif dan edukatif di media sosial. Kami mencari kreator konten yang dapat membagikan pengalaman dan hasil nyata penggunaan produk dengan cara yang autentik dan menarik.</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Persyaratan</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="min_followers" value="10.000"
                                        placeholder="Minimal followers">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="engagement_rate" value="2%"
                                        placeholder="Engagement rate">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="kategori_konten"
                                        value="Beauty, Lifestyle" placeholder="Kategori konten">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="lokasi" value="Jakarta, Bandung"
                                        placeholder="Lokasi">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateCampaign()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan script JavaScript di bagian bawah body -->
    <script>
        function updateCampaign() {
            // Mengambil data form
            const formData = new FormData(document.getElementById('editCampaignForm'));

            // Kirim data ke server menggunakan fetch
            fetch('update_campaign.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Campaign berhasil diperbarui!');
                        location.reload(); // Muat ulang halaman
                    } else {
                        alert('Gagal memperbarui campaign: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui campaign');
                });
        }

        function deleteCampaign(id) {
            if (confirm('Apakah Anda yakin ingin menghapus campaign ini?')) {
                fetch('delete_campaign.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Campaign berhasil dihapus!');
                            location.reload(); // Muat ulang halaman
                        } else {
                            alert('Gagal menghapus campaign: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus campaign');
                    });
            }
        }
    </script>
    <!-- Tambahkan di bagian head untuk memastikan Bootstrap JS terpasang -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Perbarui bagian tombol aksi di tabel -->
    <!-- Tambahkan CSS berikut di dalam tag <style> yang sudah ada -->
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            padding: 4px 8px;
            border-radius: 4px;
        }

        .action-buttons .btn-info {
            background-color: var(--pink-main);
            border-color: var(--pink-main);
        }

        .action-buttons .btn-info:hover {
            background-color: var(--pink-dark);
            border-color: var(--pink-dark);
        }
    </style>

    <!-- Ganti bagian tombol aksi dengan kode berikut -->
    <td>
        <div class="action-buttons">
            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCampaignModal"
                onclick="viewCampaign(1)" title="Lihat Detail">
                <i class="bi bi-eye"></i> Detail
            </button>
        </div>
    </td>

    <!-- Perbarui modal footer untuk menampilkan tombol verifikasi -->
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="updateCampaignStatus('disetujui', campaignId)"><i
                    class="bi bi-check-lg me-1"></i>Setujui</button>
            <button type="button" class="btn btn-danger" onclick="updateCampaignStatus('ditolak', campaignId)"><i
                    class="bi bi-x-lg me-1"></i>Tolak</button>
        </div>
    </div>
    <!-- Tambahkan script di bagian bawah body -->
    <script>
        function viewCampaign(id) {
            // Set campaign ID untuk digunakan di modal
            window.campaignId = id;

            // Tambahkan logging
            console.log('Membuka detail campaign:', id);

            // Ambil data campaign dari database
            fetch(`get_campaign.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Update modal dengan data campaign
                    const modal = document.getElementById('viewCampaignModal');

                    // Update nama campaign
                    modal.querySelector('.card:nth-child(1) .row .col-md-6:nth-child(1) .h5').textContent =
                        data.nama_campaign || '-';

                    // Update brand
                    modal.querySelector('.card:nth-child(1) .row .col-md-6:nth-child(2) .h5').textContent =
                        data.brand || '-';

                    // Update kategori
                    modal.querySelector('.card:nth-child(1) .row .col-md-6:nth-child(3) .h5').textContent =
                        data.kategori || '-';

                    // Update tanggal pengajuan
                    modal.querySelector('.card:nth-child(1) .row .col-md-6:nth-child(4) .h5').textContent =
                        data.tanggal_pengajuan || '-';

                    // Update deskripsi
                    modal.querySelector('.card:nth-child(2) .card-body p').textContent =
                        data.deskripsi || 'Tidak ada deskripsi';

                    // Update persyaratan
                    const listItems = modal.querySelectorAll('.card:nth-child(3) .list-group-item span');
                    listItems[0].textContent = `Minimal followers: ${data.min_followers || '-'}`;
                    listItems[1].textContent = `Engagement rate: ${data.engagement_rate || '-'}`;
                    listItems[2].textContent = `Kategori konten: ${data.kategori_konten || '-'}`;
                    listItems[3].textContent = `Lokasi: ${data.lokasi || '-'}`;

                    // Debug log
                    console.log('Data campaign yang diterima:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengambil detail campaign: ' + error.message);
                });

            // Perbarui tombol verifikasi dengan ID yang benar
            document.querySelector('#viewCampaignModal .btn-success')
                .setAttribute('onclick', `updateCampaignStatus('disetujui', ${id})`);
            document.querySelector('#viewCampaignModal .btn-danger')
                .setAttribute('onclick', `updateCampaignStatus('ditolak', ${id})`);
        }

        function updateCampaignStatus(status, id) {
            if (!id) {
                console.error('ID campaign tidak valid');
                alert('ID campaign tidak valid');
                return;
            }

            // Validasi status sesuai dengan enum di database
            if (!['disetujui', 'ditolak'].includes(status)) {
                console.error('Status tidak valid');
                alert('Status tidak valid');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin ${status === 'disetujui' ? 'menyetujui' : 'menolak'} campaign ini?`)) {
                console.log('Mengirim permintaan update status:', {
                    id,
                    status
                });

                fetch('update_campaign_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: id,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Perbarui badge status
                            const statusBadge = document.querySelector(`[data-campaign-id="${id}"]`);
                            if (statusBadge) {
                                statusBadge.textContent = status;
                                statusBadge.className = `badge ${
                                status === 'disetujui' ? 'bg-success' : 
                                status === 'ditolak' ? 'bg-danger' : 'bg-warning'
                            } status-badge`;
                            }

                            alert(`Campaign berhasil ${status}!`);

                            // Tutup modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('viewCampaignModal'));
                            if (modal) modal.hide();

                            // Opsional: Reload halaman untuk memastikan data terupdate
                            location.reload();
                        } else {
                            alert('Gagal memperbarui status: ' + (data.message || 'Terjadi kesalahan'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui status');
                    });
            }
        }
    </script>
</body>

</html>

<!-- Perbarui style untuk tombol verifikasi -->
<style>
    .btn-primary {
        background-color: var(--pink-main);
        border-color: var(--pink-main);
    }

    .btn-primary:hover {
        background-color: var(--pink-dark);
        border-color: var(--pink-dark);
    }
</style>