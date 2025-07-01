<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    // Ambil data dari form
    $id = $_POST['id'];
    $nama_campaign = $_POST['nama_campaign'];
    $brand = $_POST['brand'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $min_followers = $_POST['min_followers'];
    $engagement_rate = $_POST['engagement_rate'];
    $kategori_konten = $_POST['kategori_konten'];
    $lokasi = $_POST['lokasi'];

    // Update data campaign
    $stmt = $db->prepare("UPDATE campaigns SET 
        nama_campaign = ?, 
        brand = ?, 
        kategori = ?, 
        deskripsi = ?, 
        min_followers = ?, 
        engagement_rate = ?, 
        kategori_konten = ?, 
        lokasi = ?, 
        updated_at = NOW() 
        WHERE id = ?");

    $stmt->execute([
        $nama_campaign,
        $brand,
        $kategori,
        $deskripsi,
        $min_followers,
        $engagement_rate,
        $kategori_konten,
        $lokasi,
        $id
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
