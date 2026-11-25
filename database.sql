CREATE DATABASE IF NOT EXISTS db_email_tracker;
USE db_email_tracker;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'karyawan') NOT NULL DEFAULT 'admin',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
('Admin', 'admin@gmail.com', '$2y$10$4kWwuenCpFtE2am.VsQzu.eoxK6q.fkThyxvOhh38ou17IXxqdfxq', 'admin')
ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role);

CREATE TABLE IF NOT EXISTS `follow_up` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_klien` VARCHAR(100) NOT NULL,
    `perusahaan` VARCHAR(100) NOT NULL,
    `email_klien` VARCHAR(100) NOT NULL,
    `subjek_email` VARCHAR(150) NOT NULL,
    `status` ENUM('Prioritas', 'Perlu Dikirim', 'Menunggu Balasan', 'Selesai') NOT NULL DEFAULT 'Perlu Dikirim',
    `tgl_follow_up` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO `follow_up` (`nama_klien`, `perusahaan`, `email_klien`, `subjek_email`, `status`, `tgl_follow_up`) VALUES
('Raka Pratama', 'Divisi Operasional', 'karyawan@gmail.com', 'Jadwal Briefing Mingguan Tim & Evaluasi Target', 'Perlu Dikirim', '2026-11-26'),
('Nadia Kusuma', 'Tim Marketing Internal', 'nadia.kusuma@perusahaan.co.id', 'Revisi Materi Presentasi Klien Q4', 'Perlu Dikirim', '2026-11-27'),
('Dimas Saputra', 'Divisi Pengembangan Produk', 'dimas.saputra@perusahaan.co.id', 'Konfirmasi Dokumen Onboarding Karyawan Baru', 'Menunggu Balasan', '2026-11-28'),
('Lestari Wulandari', 'HR & People Ops', 'lestari.w@perusahaan.co.id', 'Rekap Absensi & Jadwal Cuti Akhir Tahun', 'Menunggu Balasan', '2026-11-29'),
('Fajar Nugroho', 'PT Sinergi Digital', 'fajar@sinergidigital.id', 'Persetujuan Kontrak Kerja Sama Tahunan', 'Selesai', '2026-11-25'),
('Hendra Wijaya', 'PT Global Solusi', 'hendra@globalsolusi.co.id', 'Konfirmasi Revisi Kontrak & Batas Akhir Pembayaran', 'Prioritas', '2026-11-25');
