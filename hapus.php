<?php
require_once 'koneksi.php';
require_once 'auth.php';
require_login();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $koneksi->prepare("DELETE FROM follow_up WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
?>