<?php
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]);

    try {
        // Cari admin berdasarkan email
        $stmt = $db->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Set session
            $_SESSION["admin_id"] = $admin['id'];
            $_SESSION["admin_name"] = $admin['name'];
            $_SESSION["admin_email"] = $admin['email'];

            if ($remember) {
                // Generate remember token
                $remember_token = bin2hex(random_bytes(32));

                // Simpan token ke database
                $stmt = $db->prepare("UPDATE admin SET remember_token = ? WHERE id = ?");
                $stmt->execute([$remember_token, $admin['id']]);

                // Set cookie
                setcookie("remember_token", $remember_token, time() + (86400 * 30), "/");
            }

            header("Location: admin/dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=2");
        exit();
    }
}

// Cek remember token jika user belum login
if (!isset($_SESSION['admin_id']) && isset($_COOKIE['remember_token'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM admin WHERE remember_token = ?");
        $stmt->execute([$_COOKIE['remember_token']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $_SESSION["admin_id"] = $admin['id'];
            $_SESSION["admin_name"] = $admin['name'];
            $_SESSION["admin_email"] = $admin['email'];
        }
    } catch (PDOException $e) {
        // Handle error silently
    }
}
