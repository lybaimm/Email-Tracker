<?php
require_once 'koneksi.php';
require_once 'auth.php';
require_login();

$cari   = $_GET['cari'] ?? '';
$status = $_GET['status'] ?? '';

$sql    = "SELECT * FROM follow_up WHERE (nama_klien LIKE ? OR perusahaan LIKE ? OR subjek_email LIKE ?)";
$params = ["%$cari%", "%$cari%", "%$cari%"];
$types  = "sss";

if ($status !== '') {
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

$sql .= " ORDER BY tgl_follow_up ASC";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$hasil = $stmt->get_result();

$stat = $koneksi->query("SELECT 
    COUNT(*) as total,
    SUM(status = 'Prioritas') as urgent,
    SUM(status = 'Perlu Dikirim') as perlu_dikirim,
    SUM(status = 'Menunggu Balasan') as menunggu,
    SUM(status = 'Selesai') as selesai
    FROM follow_up")->fetch_assoc();

$pageTitle = $status !== '' ? $status : 'Semua Email';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-shell">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="brand">
                <span class="brand-logo" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2.5" y="4.5" width="19" height="15" rx="3" fill="#ffffff"/>
                        <path d="M3.5 6.5L12 13L20.5 6.5" stroke="#1d78f2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="brand-text">Email Tracker</span>
            </a>
            <button type="button" class="btn-toggle-sidebar" id="toggleSidebar" title="Perkecil Sidebar" aria-label="Perkecil Sidebar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            </button>
        </div>

        <!-- User Profile Card -->
        <div class="user-card">
            <span class="workspace-avatar"><?= strtoupper(substr($_SESSION['user_nama'] ?? 'U', 0, 1)); ?></span>
            <div class="workspace-info sidebar-footer-text">
                <div class="workspace-name"><?= e($_SESSION['user_nama'] ?? ''); ?> <span class="role-pill"><?= e($_SESSION['user_role'] ?? ''); ?></span></div>
                <div class="workspace-email"><?= e($_SESSION['user_email'] ?? ''); ?></div>
            </div>
        </div>

        <!-- Main Nav -->
        <nav class="nav-list">
            <a href="index.php" class="nav-item <?= $status === '' ? 'active' : ''; ?>" title="Semua Email">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    <span class="nav-text">Semua Email</span>
                </span>
                <span class="nav-count"><?= $stat['total'] ?? 0; ?></span>
            </a>

            <a href="index.php?status=Prioritas" class="nav-item <?= $status === 'Prioritas' ? 'active' : ''; ?>" title="Prioritas">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span class="nav-text">Prioritas</span>
                </span>
                <span class="nav-count"><?= $stat['urgent'] ?? 0; ?></span>
            </a>

            <a href="index.php?status=Perlu+Dikirim" class="nav-item <?= $status === 'Perlu Dikirim' ? 'active' : ''; ?>" title="Perlu Dikirim">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <span class="nav-text">Perlu Dikirim</span>
                </span>
                <span class="nav-count"><?= $stat['perlu_dikirim'] ?? 0; ?></span>
            </a>

            <a href="index.php?status=Menunggu+Balasan" class="nav-item <?= $status === 'Menunggu Balasan' ? 'active' : ''; ?>" title="Menunggu Balasan">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="nav-text">Menunggu</span>
                </span>
                <span class="nav-count"><?= $stat['menunggu'] ?? 0; ?></span>
            </a>

            <a href="index.php?status=Selesai" class="nav-item <?= $status === 'Selesai' ? 'active' : ''; ?>" title="Selesai">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span class="nav-text">Selesai</span>
                </span>
                <span class="nav-count"><?= $stat['selesai'] ?? 0; ?></span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Action Bar (+ New Email & Search) -->
        <div class="topbar">
            <a href="tambah.php" class="btn-new-inline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>New Email</span>
            </a>

            <form method="GET" action="index.php" class="search-form">
                <?php if ($status !== ''): ?>
                    <input type="hidden" name="status" value="<?= e($status); ?>">
                <?php endif; ?>
                <div class="search-input-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="cari" class="search-input" placeholder="Search nama, perusahaan, atau subjek..." value="<?= e($cari); ?>">
                </div>
                <?php if ($cari !== '' || $status !== ''): ?>
                    <a href="index.php" class="btn-subtle">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Title & Segmented Pill -->
        <header class="main-header">
            <div class="header-title-row">
                <h1 class="main-title"><?= e($pageTitle); ?></h1>
            </div>
            <div class="summary-pills">
                <span class="pill-item"><?= $stat['total'] ?? 0; ?> emails</span>
                <span class="pill-divider"></span>
                <span class="pill-item"><?= $stat['perlu_dikirim'] ?? 0; ?> perlu dikirim</span>
            </div>
        </header>

        <!-- Floating Card List -->
        <section class="email-list-wrap">
            <?php if ($hasil->num_rows > 0): ?>
                <?php while ($row = $hasil->fetch_assoc()): ?>
                    <?php
                    $badgeClass = match($row['status']) {
                        'Prioritas'        => 'badge-urgent',
                        'Perlu Dikirim'    => 'badge-perlu',
                        'Menunggu Balasan' => 'badge-menunggu',
                        'Selesai'          => 'badge-deal',
                        default            => ''
                    };
                    $avatarColor = match($row['status']) {
                        'Prioritas'        => 'avatar-red',
                        'Perlu Dikirim'    => 'avatar-yellow',
                        'Menunggu Balasan' => 'avatar-blue',
                        'Selesai'          => 'avatar-green',
                        default            => 'avatar-blue'
                    };
                    $initial = strtoupper(substr($row['nama_klien'], 0, 1));
                    ?>
                    <article class="email-card-row">
                        <div class="email-avatar <?= $avatarColor; ?>"><?= e($initial); ?></div>
                        <div class="email-row-main">
                            <div class="email-row-top">
                                <span class="email-sender"><?= e($row['nama_klien']); ?></span>
                                <span class="email-company">&bull; <?= e($row['perusahaan']); ?></span>
                                <span class="badge-status <?= $badgeClass; ?>"><?= e($row['status']); ?></span>
                            </div>
                            <div class="email-subject"><?= e($row['subjek_email']); ?></div>
                            <div class="email-preview"><?= e($row['email_klien']); ?></div>
                        </div>
                        <div class="email-row-right">
                            <span class="email-date"><?= date('d M Y', strtotime($row['tgl_follow_up'])); ?></span>
                            <div class="row-actions">
                                <a href="mailto:<?= e($row['email_klien']); ?>?subject=<?= rawurlencode($row['subjek_email']); ?>" class="btn-action btn-action-primary" title="Kirim Email">Kirim</a>
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action" title="Edit">Edit</a>
                                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-action btn-action-danger" title="Hapus" onclick="return confirm('Hapus data ini?');">Hapus</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-title">Belum ada data follow-up</div>
                    <div class="empty-state-desc">Tambahkan klien baru untuk mulai melacak jadwal follow-up email Anda.</div>
                    <a href="tambah.php" class="btn-primary-solid">+ New Email</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
</script>

</body>
</html>
