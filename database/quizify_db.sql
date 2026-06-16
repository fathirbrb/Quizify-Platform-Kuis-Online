-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2026 at 11:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizify_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `aksi` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `quiz_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `aksi`, `ip_address`, `role`, `class_id`, `quiz_id`, `created_at`) VALUES
(1, 1, 'Login sebagai admin', '127.0.0.1', 'admin', NULL, NULL, '2026-05-19 09:44:50'),
(2, 2, 'Login sebagai dosen', '127.0.0.1', 'dosen', NULL, NULL, '2026-05-19 09:44:50'),
(3, 4, 'Login sebagai mahasiswa', '127.0.0.1', 'mahasiswa', NULL, NULL, '2026-05-19 09:44:50'),
(4, 2, 'Membuat kuis baru: HTML Dasar', '127.0.0.1', 'dosen', NULL, NULL, '2026-05-19 09:44:50'),
(5, 4, 'Mengerjakan kuis: HTML Dasar', '127.0.0.1', 'mahasiswa', NULL, NULL, '2026-05-19 09:44:50'),
(6, 1, 'Admin Login', '127.0.0.1', 'admin', NULL, NULL, '2026-05-19 09:57:55'),
(7, 2, 'Dosen Membuat Kuis', '127.0.0.1', 'dosen', NULL, NULL, '2026-05-19 09:57:55'),
(8, 3, 'Mahasiswa Mengerjakan Kuis', '127.0.0.1', 'mahasiswa', NULL, NULL, '2026-05-19 09:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_kelas`
--

CREATE TABLE `dosen_kelas` (
  `id` int NOT NULL,
  `dosen_id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `matkul_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_kuis`
--

CREATE TABLE `hasil_kuis` (
  `id` int NOT NULL,
  `kuis_id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_mahasiswa`
--

CREATE TABLE `jawaban_mahasiswa` (
  `id` int NOT NULL,
  `pengerjaan_id` int NOT NULL,
  `soal_id` int NOT NULL,
  `jawaban` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama`, `created_at`) VALUES
(1, 'Sistem Informasi', '2026-05-19 09:57:54'),
(2, 'Teknik Informatika', '2026-05-19 09:57:54'),
(3, 'Ilmu Komputer', '2026-05-19 09:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan_id` int DEFAULT NULL,
  `kode_kelas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `invite_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama`, `jurusan`, `jurusan_id`, `kode_kelas`, `tahun_ajaran`, `created_at`, `updated_at`, `invite_code`, `deskripsi`) VALUES
(1, 'SI-A 2024', 'Sistem Informasi', 1, 'SI-A-2024', '2024/2025', '2026-05-19 09:44:50', NULL, 'SIA2024', NULL),
(2, 'SI-B 2024', 'Sistem Informasi', 1, 'SI-B-2024', '2024/2025', '2026-05-19 09:44:50', NULL, 'SIB2024', NULL),
(3, 'SI-C 2024', 'Sistem Informasi', 1, 'SI-C-2024', '2024/2025', '2026-05-19 09:44:50', NULL, 'SIC2024', NULL),
(4, 'SI 4A', 'Sistem Informasi', 1, 'SI-4A', '2025/2026', '2026-05-19 09:57:54', NULL, 'SI4A25', NULL),
(5, 'SI 4B', 'Sistem Informasi', 1, 'SI-4B', '2025/2026', '2026-05-19 09:57:54', NULL, 'SI4B25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kuis`
--

CREATE TABLE `kuis` (
  `id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `matkul_id` int NOT NULL,
  `dosen_id` int NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `durasi` int DEFAULT '30',
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `status` enum('draft','terjadwal','aktif','selesai') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuis_pengerjaan`
--

CREATE TABLE `kuis_pengerjaan` (
  `id` int NOT NULL,
  `kuis_id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `status` enum('available','in_progress','completed','expired','sedang','selesai') COLLATE utf8mb4_unicode_ci DEFAULT 'in_progress',
  `progress` int DEFAULT '0',
  `submitted_at` datetime DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `auto_submitted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_kelas`
--

CREATE TABLE `mahasiswa_kelas` (
  `id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `joined_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int DEFAULT '2',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode`, `nama`, `sks`, `created_at`) VALUES
(1, 'MK001', 'Pemrograman Web', 3, '2026-05-19 09:44:50'),
(2, 'MK002', 'Basis Data', 3, '2026-05-19 09:44:50'),
(3, 'MK003', 'Sistem Operasi', 2, '2026-05-19 09:44:50'),
(4, 'MK004', 'Jaringan Komputer', 2, '2026-05-19 09:44:50'),
(5, 'IF401', 'Basis Data', 3, '2026-05-19 09:57:55'),
(6, 'IF402', 'Pemrograman Web', 3, '2026-05-19 09:57:55'),
(7, 'IF403', 'Sistem Informasi', 2, '2026-05-19 09:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `soal`
--

CREATE TABLE `soal` (
  `id` int NOT NULL,
  `kuis_id` int NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci,
  `opsi_a` text COLLATE utf8mb4_unicode_ci,
  `opsi_b` text COLLATE utf8mb4_unicode_ci,
  `opsi_c` text COLLATE utf8mb4_unicode_ci,
  `opsi_d` text COLLATE utf8mb4_unicode_ci,
  `jawaban_benar` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `nim_nip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `nim_nip`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ADMIN001', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(2, 'Dr. Budi Santoso', 'budi@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'NIP001', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(3, 'Dr. Siti Rahayu', 'siti@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'NIP002', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(4, 'Andi Mahasiswa', 'andi@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052001', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(5, 'Bela Mahasiswi', 'bela@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052002', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(6, 'Candra Putra', 'candra@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052003', '2026-05-19 09:44:50', '2026-05-19 09:44:50'),
(7, 'Admin Unila', 'admin@unila.ac.id', '123', 'admin', 'ADM001', '2026-05-19 09:57:54', '2026-05-19 09:57:54'),
(8, 'Dila Alya', 'dila.alya@unila.ac.id', '123', 'dosen', 'DOS001', '2026-05-19 09:57:54', '2026-05-19 09:57:54'),
(9, 'Chilli Rahmawati', '2317051001@students.unila.ac.id', '123', 'mahasiswa', '2317051001', '2026-05-19 09:57:54', '2026-05-19 09:57:54'),
(10, 'M Fatir', '2317051002@students.unila.ac.id', '123', 'mahasiswa', '2317051002', '2026-05-19 09:57:54', '2026-05-19 09:57:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `dosen_kelas`
--
ALTER TABLE `dosen_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `matkul_id` (`matkul_id`);

--
-- Indexes for table `hasil_kuis`
--
ALTER TABLE `hasil_kuis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kuis_id` (`kuis_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `jawaban_mahasiswa`
--
ALTER TABLE `jawaban_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengerjaan_id` (`pengerjaan_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_jurusan_nama` (`nama`);

--
-- Indexes for table `kuis`
--
ALTER TABLE `kuis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `matkul_id` (`matkul_id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `kuis_pengerjaan`
--
ALTER TABLE `kuis_pengerjaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kuis_id` (`kuis_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kuis_id` (`kuis_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dosen_kelas`
--
ALTER TABLE `dosen_kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_kuis`
--
ALTER TABLE `hasil_kuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_mahasiswa`
--
ALTER TABLE `jawaban_mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kuis`
--
ALTER TABLE `kuis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuis_pengerjaan`
--
ALTER TABLE `kuis_pengerjaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activity_log_ibfk_3` FOREIGN KEY (`quiz_id`) REFERENCES `kuis` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dosen_kelas`
--
ALTER TABLE `dosen_kelas`
  ADD CONSTRAINT `dosen_kelas_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dosen_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `dosen_kelas_ibfk_3` FOREIGN KEY (`matkul_id`) REFERENCES `mata_kuliah` (`id`);

--
-- Constraints for table `hasil_kuis`
--
ALTER TABLE `hasil_kuis`
  ADD CONSTRAINT `hasil_kuis_ibfk_1` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`),
  ADD CONSTRAINT `hasil_kuis_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jawaban_mahasiswa`
--
ALTER TABLE `jawaban_mahasiswa`
  ADD CONSTRAINT `jawaban_mahasiswa_ibfk_1` FOREIGN KEY (`pengerjaan_id`) REFERENCES `kuis_pengerjaan` (`id`),
  ADD CONSTRAINT `jawaban_mahasiswa_ibfk_2` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`);

--
-- Constraints for table `kuis`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kuis`
--
ALTER TABLE `kuis`
  ADD CONSTRAINT `kuis_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `kuis_ibfk_2` FOREIGN KEY (`matkul_id`) REFERENCES `mata_kuliah` (`id`),
  ADD CONSTRAINT `kuis_ibfk_3` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kuis_pengerjaan`
--
ALTER TABLE `kuis_pengerjaan`
  ADD CONSTRAINT `kuis_pengerjaan_ibfk_1` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`),
  ADD CONSTRAINT `kuis_pengerjaan_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Constraints for table `soal`
--
ALTER TABLE `soal`
  ADD CONSTRAINT `soal_ibfk_1` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
