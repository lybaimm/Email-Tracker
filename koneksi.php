<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "db_email_tracker";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = new mysqli($host, $user, $pass, $db);
    $koneksi->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Koneksi DB Gagal: " . $e->getMessage());
    exit("Maaf, sistem sedang mengalami gangguan koneksi basis data.");
}

function e($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}
?>