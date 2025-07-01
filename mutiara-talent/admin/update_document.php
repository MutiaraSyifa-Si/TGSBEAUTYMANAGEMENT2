<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['admin_id']) || !isset($_POST['id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $db->beginTransaction();

    // Update informasi dokumen
    $stmt = $db->prepare("UPDATE documents SET judul = ?, deskripsi = ?, updated_at = NOW() WHERE id = ?")
    $stmt->execute([$_POST['judul'], $_POST['deskripsi'], $_POST['id']]);

    // Jika ada file baru
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $fileName = uniqid() . '_' . time() . '_' . $file['name'];
        $uploadPath = '../uploads/documents/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update nama file di database
            $stmt = $db->prepare("UPDATE documents SET file_name = ? WHERE id = ?")
            $stmt->execute([$fileName, $_POST['id']]);
        }
    }

    $db->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}