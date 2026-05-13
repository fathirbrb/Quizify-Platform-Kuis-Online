<?php
<<<<<<< HEAD
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'quizify');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die('Koneksi database gagal: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8');
    return $conn;
=======


define('DB_HOST', 'localhost');
define('DB_NAME', 'quizify_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');


function getDB() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:2rem;color:#dc2626;">
                <strong>Koneksi Database Gagal</strong><br>
                Pastikan MySQL berjalan dan konfigurasi di config/database.php sudah benar.<br>
                Error: ' . $e->getMessage() . '
            </div>');
        }
    }

    return $pdo;
>>>>>>> 35a41c870a522e72542a736bd10557eb44ccaa08
}
