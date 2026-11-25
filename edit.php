<?php
require_once 'koneksi.php';
require_once 'auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = $_POST['id'];
    $nama_klien    = $_POST['nama_klien'];
    $perusahaan    = $_POST['perusahaan'];
    $email_klien   = $_POST['email_klien'];
    $subjek_email  = $_POST['subjek_email'];
    $status        = $_POST['status'];
    $tgl_follow_up = $_POST['tgl_follow_up'];

    $stmt = $koneksi->prepare("UPDATE follow_up SET nama_klien=?, perusahaan=?, email_klien=?, subjek_email=?, status=?, tgl_follow_up=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nama_klien, $perusahaan, $email_klien, $subjek_email, $status, $tgl_follow_up, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $koneksi->prepare("SELECT * FROM follow_up WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - Email Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-page-wrap">
    <div class="form-page-header">
        <div class="brand">
            <span class="brand-logo" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2.5" y="4.5" width="19" height="15" rx="3" fill="#ffffff"/>
                    <path d="M3.5 6.5L12 13L20.5 6.5" stroke="#1d78f2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>Edit Follow-up Klien</span>
        </div>
        <a href="index.php" class="btn-subtle">Kembali</a>
    </div>
    <div class="form-page-body">
        <form method="POST" action="edit.php">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            <div class="form-group">
                <label class="form-label">Nama Klien</label>
                <input type="text" name="nama_klien" class="form-control" value="<?= e($data['nama_klien']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Perusahaan</label>
                <input type="text" name="perusahaan" class="form-control" value="<?= e($data['perusahaan']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email_klien" class="form-control" value="<?= e($data['email_klien']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjek Email</label>
                <input type="text" name="subjek_email" class="form-control" value="<?= e($data['subjek_email']); ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Prioritas" <?= $data['status'] == 'Prioritas' ? 'selected' : ''; ?>>Prioritas</option>
                        <option value="Perlu Dikirim" <?= $data['status'] == 'Perlu Dikirim' ? 'selected' : ''; ?>>Perlu Dikirim</option>
                        <option value="Menunggu Balasan" <?= $data['status'] == 'Menunggu Balasan' ? 'selected' : ''; ?>>Menunggu Balasan</option>
                        <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Follow-up</label>
                    <input type="date" name="tgl_follow_up" class="form-control" value="<?= e($data['tgl_follow_up']); ?>" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="index.php" class="btn-subtle">Batal</a>
                <button type="submit" class="btn-primary-solid">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
