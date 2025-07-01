<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header('Location: documents.php');
    exit();
}

try {
    $stmt = $db->prepare("SELECT file_name, judul FROM documents WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        $file_path = '../uploads/documents/' . $document['file_name'];

        if (file_exists($file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $document['file_name'] . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit();
        }
    }

    // Jika file tidak ditemukan
    header('Location: documents.php?error=file_not_found');
} catch (PDOException $e) {
    header('Location: documents.php?error=download_failed');
}
