<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Management - Platform Talent Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-pink {
            background-color: #FF69B4;
            color: white;
        }

        .btn-pink:hover {
            background-color: #FF1493;
            color: white;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            color: white;
            padding: 100px 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Beauty Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#documents">Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="login.php" class="btn btn-pink me-2">Login</a>
                    <a href="#register" class="btn btn-outline-pink">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Temukan Talent Terbaik untuk Bisnis Anda</h1>
            <p class="lead mb-4">Platform manajemen talent yang menghubungkan perusahaan dengan talent berkualitas</p>
            <a href="#register" class="btn btn-pink btn-lg">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Documents Section -->
    <section id="documents" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Dokumen Penting</h2>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Insight</h5>
                            <p class="card-text">Dapatkan insight terkini tentang tren talent management</p>
                            <a href="#" class="btn btn-pink">Download</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Ratecard</h5>
                            <p class="card-text">Informasi lengkap tentang biaya layanan kami</p>
                            <a href="#" class="btn btn-pink">Download</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Company Profile</h5>
                            <p class="card-text">Pelajari lebih lanjut tentang Mutiara Talent</p>
                            <a href="#" class="btn btn-pink">Download</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screening Form -->
    <section id="screening" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Screening & Subscribe Client</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="process_screening.php" method="POST" class="card p-4">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Bisnis</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Kebutuhan Talent</label>
                            <textarea class="form-control" id="requirements" name="requirements" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="subscribe" name="subscribe">
                            <label class="form-check-label" for="subscribe">Subscribe untuk mendapatkan update
                                terbaru</label>
                        </div>
                        <button type="submit" class="btn btn-pink">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Talent Matching Section -->
    <section id="talent-matching" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Talent Matching</h2>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label for="skill" class="form-label">Keahlian</label>
                                    <input type="text" class="form-control" id="skill">
                                </div>
                                <div class="col-md-4">
                                    <label for="experience" class="form-label">Pengalaman</label>
                                    <select class="form-select" id="experience">
                                        <option value="">Pilih...</option>
                                        <option>0-2 tahun</option>
                                        <option>2-5 tahun</option>
                                        <option>5+ tahun</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="location" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="location">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-pink">Cari Talent</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Talent</th>
                            <th>Keahlian</th>
                            <th>Pengalaman</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>Web Developer</td>
                            <td>5 tahun</td>
                            <td>Jakarta</td>
                            <td><span class="badge bg-success">Available</span></td>
                            <td><button class="btn btn-pink btn-sm">Detail</button></td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>UI/UX Designer</td>
                            <td>3 tahun</td>
                            <td>Bandung</td>
                            <td><span class="badge bg-warning">Interview</span></td>
                            <td><button class="btn btn-pink btn-sm">Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Mutiara Talent</h5>
                    <p>Platform manajemen talent terpercaya untuk bisnis Anda</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#about" class="text-white">Tentang Kami</a></li>
                        <li><a href="#services" class="text-white">Layanan</a></li>
                        <li><a href="#documents" class="text-white">Dokumen</a></li>
                        <li><a href="#contact" class="text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li>Email: info@mutiaratalent.com</li>
                        <li>Phone: (021) 1234-5678</li>
                        <li>Alamat: Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Beauty Management. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Beauty Management</a>
        <div class="d-flex">
            <span class="navbar-text me-3">
                Welcome, <?php echo htmlspecialchars($_SESSION["user_email"]); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>
<!-- Sisa kode HTML tetap sama -->