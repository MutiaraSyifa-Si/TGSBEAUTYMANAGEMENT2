<?php
$host = 'localhost';
$dbname = 'mutiara_talent';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die();
}
?>
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
echo "Koneksi gagal: " . $e->getMessage();
die();
}