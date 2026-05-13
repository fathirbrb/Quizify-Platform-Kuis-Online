CREATE DATABASE IF NOT EXISTS quizify_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quizify_db;

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin','dosen','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    nim_nip     VARCHAR(20),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mata_kuliah (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    kode        VARCHAR(20) NOT NULL UNIQUE,
    nama        VARCHAR(100) NOT NULL,
    sks         INT DEFAULT 2,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS kelas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(50) NOT NULL,
    tahun_ajaran VARCHAR(20),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS activity_log (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    aksi        VARCHAR(200) NOT NULL,
    ip_address  VARCHAR(50),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (nama, email, password, role, nim_nip) VALUES
('Administrator', 'admin@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ADMIN001'),
('Dr. Budi Santoso', 'budi@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'NIP001'),
('Dr. Siti Rahayu', 'siti@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'NIP002'),
('Andi Mahasiswa', 'andi@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052001'),
('Bela Mahasiswi', 'bela@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052002'),
('Candra Putra', 'candra@quizify.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', '2417052003');

INSERT INTO mata_kuliah (kode, nama, sks) VALUES
('MK001', 'Pemrograman Web', 3),
('MK002', 'Basis Data', 3),
('MK003', 'Sistem Operasi', 2),
('MK004', 'Jaringan Komputer', 2);

INSERT INTO kelas (nama, tahun_ajaran) VALUES
('SI-A 2024', '2024/2025'),
('SI-B 2024', '2024/2025'),
('SI-C 2024', '2024/2025');

INSERT INTO activity_log (user_id, aksi, ip_address) VALUES
(1, 'Login sebagai admin', '127.0.0.1'),
(2, 'Login sebagai dosen', '127.0.0.1'),
(4, 'Login sebagai mahasiswa', '127.0.0.1'),
(2, 'Membuat kuis baru: HTML Dasar', '127.0.0.1'),
(4, 'Mengerjakan kuis: HTML Dasar', '127.0.0.1');
