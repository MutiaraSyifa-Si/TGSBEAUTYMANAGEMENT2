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
    // Validasi input
    if (!isset($_POST['judul']) || empty($_POST['judul'])) {
        throw new Exception('Judul dokumen harus diisi');
    }

    if (!isset($_FILES['dokumen']) || $_FILES['dokumen']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File dokumen harus dipilih');
    }

    // Validasi tipe file
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $file_type = $_FILES['dokumen']['type'];
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception('Tipe file tidak didukung. Harap upload file PDF atau DOC/DOCX');
    }

    // Buat direktori upload jika belum ada
    $upload_dir = '../uploads/documents/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate nama file unik
    $file_ext = pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid() . '_' . time() . '.' . $file_ext;
    $file_path = $upload_dir . $file_name;

    // Upload file
    if (!move_uploaded_file($_FILES['dokumen']['tmp_name'], $file_path)) {
        throw new Exception('Gagal mengupload file');
    }

    // Simpan informasi dokumen ke database
    $stmt = $db->prepare("INSERT INTO documents (judul, deskripsi, file_name, file_path, uploaded_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $result = $stmt->execute([
        $_POST['judul'],
        $_POST['deskripsi'] ?? '',
        $_FILES['dokumen']['name'],
        $file_name,
        $_SESSION['admin_id']
    ]);

    if (!$result) {
        // Hapus file jika gagal menyimpan ke database
        unlink($file_path);
        throw new Exception('Gagal menyimpan informasi dokumen');
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
