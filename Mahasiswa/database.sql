CREATE DATABASE IF NOT EXISTS quizify
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE quizify;

CREATE TABLE mata_kuliah (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama_matkul VARCHAR(100) NOT NULL
);

CREATE TABLE kuis (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    matkul_id    INT NOT NULL,
    nama_kuis    VARCHAR(150) NOT NULL,
    durasi       INT NOT NULL COMMENT 'dalam menit',
    jumlah_soal  INT NOT NULL DEFAULT 0,
    status       ENUM('dibuka','terjadwal','selesai') DEFAULT 'terjadwal',
    batas_waktu  VARCHAR(10) NULL COMMENT 'contoh: 23.59',
    warna        VARCHAR(20) DEFAULT 'orange' COMMENT 'orange|blue|green|indigo',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (matkul_id) REFERENCES mata_kuliah(id)
);

CREATE TABLE soal (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kuis_id    INT NOT NULL,
    nomor      INT NOT NULL,
    pertanyaan TEXT NOT NULL,
    opsi_a     VARCHAR(255) NOT NULL,
    opsi_b     VARCHAR(255) NOT NULL,
    opsi_c     VARCHAR(255) NOT NULL,
    opsi_d     VARCHAR(255) NOT NULL,
    jawaban    CHAR(1) NOT NULL COMMENT 'A/B/C/D',
    FOREIGN KEY (kuis_id) REFERENCES kuis(id)
);

CREATE TABLE hasil_kuis (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    kuis_id        INT NOT NULL,
    nilai          INT NULL,
    durasi_aktual  INT NULL COMMENT 'menit yang digunakan',
    status         ENUM('selesai','belum','dibuka') DEFAULT 'belum',
    tanggal_selesai DATETIME NULL,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kuis_id) REFERENCES kuis(id)
);


INSERT INTO mata_kuliah (nama_matkul) VALUES
    ('Pemrograman Web'),
    ('Basis Data'),
    ('Algoritma'),
    ('Komdat');

INSERT INTO kuis (matkul_id, nama_kuis, durasi, jumlah_soal, status, batas_waktu, warna) VALUES
    (1, 'Kuis HTML Dasar',      30, 10, 'dibuka',    '23.59', 'orange'),
    (1, 'Kuis CSS Layout',      35, 15, 'terjadwal',  NULL,   'indigo'),
    (2, 'Kuis Basis Data',      40, 12, 'selesai',    NULL,   'green'),
    (3, 'Kuis Sorting Algorithm',25, 8, 'dibuka',    '20.00', 'blue');

INSERT INTO soal (kuis_id, nomor, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban) VALUES
    (1, 1, 'Tag HTML untuk membuat tautan adalah?', '<link>', '<a>', '<href>', '<url>', 'B'),
    (1, 2, 'Apa kepanjangan dari HTML?', 'Hyper Text Markup Language', 'High Text Makeup Language', 'Hyper Transfer Markup Language', 'Home Text Markup Language', 'A'),
    (1, 3, 'Fungsi tag <section> dalam HTML5?', 'Mengelompokkan konten tematik', 'Membuat navigasi', 'Menampilkan sidebar', 'Mendefinisikan header', 'A');

INSERT INTO hasil_kuis (kuis_id, nilai, durasi_aktual, status, tanggal_selesai) VALUES
    (3, 92, 38, 'selesai', '2026-05-10 10:30:00');
