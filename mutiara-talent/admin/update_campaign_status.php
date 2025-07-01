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
    // Tambahkan logging
    error_log('Menerima permintaan update status campaign');

    // Ambil data JSON yang dikirim
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['status'])) {
        throw new Exception('Data tidak lengkap');
    }

    $id = $data['id'];
    $status = $data['status'];

    error_log("Data diterima - ID: $id, Status: $status");

    // Validasi status
    if (!in_array($status, ['disetujui', 'ditolak'])) {
        throw new Exception('Status tidak valid');
    }

    // Update status campaign
    $stmt = $db->prepare("UPDATE campaigns SET status = ?, updated_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$status, $id]);

    // Verifikasi update berhasil
    if ($result && $stmt->rowCount() > 0) {
        error_log("Berhasil update status campaign ID: $id ke status: $status");
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Gagal memperbarui status campaign');
    }
} catch (Exception $e) {
    error_log('Error saat update status: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
