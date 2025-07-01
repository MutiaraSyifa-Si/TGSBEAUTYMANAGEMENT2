<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../config/database.php';

// Ambil ID campaign dari parameter URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid campaign ID']);
    exit();
}

try {
    // Query untuk mengambil detail campaign
    $stmt = $db->prepare("SELECT * FROM campaigns WHERE id = ?");
    $stmt->execute([$id]);
    $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($campaign) {
        header('Content-Type: application/json');
        echo json_encode($campaign);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Campaign not found']);
    }
} catch (PDOException $e) {
    error_log('Error saat mengambil detail campaign: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error']);
}
