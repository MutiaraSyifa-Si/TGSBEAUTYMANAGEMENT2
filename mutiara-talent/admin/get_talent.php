<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM talents WHERE id = ?");
    $stmt->execute([$id]);
    $talent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($talent) {
        header('Content-Type: application/json');
        echo json_encode($talent);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Talent tidak ditemukan']);
    }
}
