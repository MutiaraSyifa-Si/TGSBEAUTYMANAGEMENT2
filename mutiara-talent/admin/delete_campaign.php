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
    // Ambil data JSON yang dikirim
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    // Hapus campaign
    $stmt = $db->prepare("DELETE FROM campaigns WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
