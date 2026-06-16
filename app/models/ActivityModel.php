<?php

require_once __DIR__ . '/../../config/database.php';

class ActivityModel
{
    public static function log($userId, $aksi, $role = null, $classId = null, $quizId = null)
    {
        try {
            $db = getDB();
            self::ensureSchema($db);
            $stmt = $db->prepare(
                'INSERT INTO activity_log (user_id, aksi, ip_address, role, class_id, quiz_id)
                 VALUES (:user_id, :aksi, :ip_address, :role, :class_id, :quiz_id)'
            );

            return $stmt->execute([
                'user_id' => $userId ?: null,
                'aksi' => $aksi,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'role' => $role ?: ($_SESSION['role'] ?? null),
                'class_id' => $classId ?: null,
                'quiz_id' => $quizId ?: null,
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function getLogs($limit = 50)
    {
        $db = getDB();
        self::ensureSchema($db);
        $stmt = $db->prepare(
            'SELECT
                al.id,
                al.aksi,
                al.ip_address,
                al.role AS log_role,
                al.class_id,
                al.quiz_id,
                al.created_at,
                u.nama,
                u.email,
                COALESCE(al.role, u.role) AS role,
                k.nama AS nama_kelas,
                q.judul AS nama_kuis
             FROM activity_log al
             LEFT JOIN users u ON u.id = al.user_id
             LEFT JOIN kelas k ON k.id = al.class_id
             LEFT JOIN kuis q ON q.id = al.quiz_id
             ORDER BY al.created_at DESC, al.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function ensureSchema(PDO $db)
    {
        foreach ([
            'role' => 'VARCHAR(30) NULL',
            'class_id' => 'INT NULL',
            'quiz_id' => 'INT NULL',
        ] as $column => $definition) {
            $stmt = $db->prepare('SHOW COLUMNS FROM activity_log LIKE :column_name');
            $stmt->execute(['column_name' => $column]);
            if (!$stmt->fetch()) {
                $db->exec('ALTER TABLE activity_log ADD COLUMN ' . $column . ' ' . $definition);
            }
        }
    }
}
