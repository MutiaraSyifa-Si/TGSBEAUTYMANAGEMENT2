<?php
require_once __DIR__ . '/../config/database.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}

// Handle POST request untuk update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity_id']) && isset($_POST['new_status'])) {
    $activity_id = $_POST['activity_id'];
    $new_status = $_POST['new_status'];

    try {
        $stmt = $db->prepare("UPDATE activities SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $activity_id]);

        header('Location: dashboard.php?status=updated');
        exit();
    } catch (PDOException $e) {
        $error = 'Gagal memperbarui status';
    }
}
