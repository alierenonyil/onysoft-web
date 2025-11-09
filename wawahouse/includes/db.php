<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Database Bağlantı Sınıfı (PDO)
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

class Database {
    private static ?self $instance = null;
    private PDO $connection;

    /**
     * Database Constructor (Singleton Pattern)
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database bağlantı hatası: " . $e->getMessage());
        }
    }

    /**
     * Singleton Instance
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDO Connection
     */
    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Prepare Query
     */
    public function prepare(string $query): PDOStatement {
        return $this->connection->prepare($query);
    }

    /**
     * Execute Query
     */
    public function query(string $query): PDOStatement {
        return $this->connection->query($query);
    }

    /**
     * Son eklenen ID
     */
    public function lastInsertId(): string {
        return $this->connection->lastInsertId();
    }

    /**
     * Transaction başlat
     */
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    /**
     * Transaction commit
     */
    public function commit(): bool {
        return $this->connection->commit();
    }

    /**
     * Transaction rollback
     */
    public function rollback(): bool {
        return $this->connection->rollBack();
    }

    /**
     * Tek satır getir
     */
    public function fetchOne(string $query, array $params = []): ?array {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Çoklu satır getir
     */
    public function fetchAll(string $query, array $params = []): array {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Insert işlemi
     */
    public function insert(string $table, array $data): bool|string {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $query = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->prepare($query);

        if ($stmt->execute($data)) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Update işlemi
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): bool {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        $fieldsStr = implode(', ', $fields);

        $query = "UPDATE {$table} SET {$fieldsStr} WHERE {$where}";
        $stmt = $this->prepare($query);

        $params = array_merge($data, $whereParams);
        return $stmt->execute($params);
    }

    /**
     * Delete işlemi
     */
    public function delete(string $table, string $where, array $params = []): bool {
        $query = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Sayım
     */
    public function count(string $table, string $where = '1=1', array $params = []): int {
        $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$where}";
        $result = $this->fetchOne($query, $params);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Clone ve serialize engellenmiş (Singleton için)
     */
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Global fonksiyonlar
function db(): Database {
    return Database::getInstance();
}

function pdo(): PDO {
    return Database::getInstance()->getConnection();
}
