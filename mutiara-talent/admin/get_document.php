<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $db->prepare("SELECT * FROM documents WHERE id = ?")
    $stmt->execute([$_GET['id']]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        echo json_encode($document);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Document not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}