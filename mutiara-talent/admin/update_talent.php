<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['talent_id'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $niche = $_POST['niche'];
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE talents SET nama = ?, kategori = ?, niche = ?, lokasi = ?, status = ? WHERE id = ?");
    $result = $stmt->execute([$nama, $kategori, $niche, $lokasi, $status, $id]);

    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
}
