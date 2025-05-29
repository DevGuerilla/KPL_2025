<?php
class Database
{
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $db_name = DB_NAME;

    private ?PDO $dbh = null;
    private ?PDOStatement $stmt = null;
    private array $queryLog = [];

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', ['error' => $e->getMessage()]);
            throw new Exception('Database connection failed');
        }
    }

    public function query(string $query): void
    {
        $startTime = microtime(true);

        try {
            // Validate query for security
            DatabaseSecurity::validateQuery($query);

            $this->stmt = $this->dbh->prepare($query);

            $executionTime = microtime(true) - $startTime;
            DatabaseSecurity::logQueryPerformance($query, $executionTime);

            // Log query for debugging
            $this->queryLog[] = [
                'query' => $query,
                'time' => $executionTime,
                'timestamp' => time()
            ];

        } catch (PDOException $e) {
            Logger::error('Database query preparation failed', [
                'query' => substr($query, 0, 100),
                'error' => $e->getMessage()
            ]);
            throw new Exception('Query preparation failed');
        }
    }

    public function bind(string $param, $value, ?int $type = null): void
    {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
        }

        // Sanitize string values
        if ($type === PDO::PARAM_STR && is_string($value)) {
            $value = DatabaseSecurity::sanitizeInput($value);
        }

        try {
            $this->stmt->bindValue($param, $value, $type);
        } catch (PDOException $e) {
            Logger::error('Parameter binding failed', [
                'param' => $param,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Parameter binding failed');
        }
    }

    public function execute(): bool
    {
        try {
            $result = $this->stmt->execute();

            // Log successful execution
            Logger::debug('Query executed successfully');

            return $result;
        } catch (PDOException $e) {
            Logger::error('Query execution failed', [
                'error' => $e->getMessage(),
                'query' => $this->getLastQuery()
            ]);
            throw new Exception('Query execution failed');
        }
    }

    public function resultSet(): array
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single(): mixed
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        try {
            Logger::debug('Beginning database transaction');
            return $this->dbh->beginTransaction();
        } catch (PDOException $e) {
            Logger::error('Failed to begin transaction', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function commit(): bool
    {
        try {
            Logger::debug('Committing database transaction');
            return $this->dbh->commit();
        } catch (PDOException $e) {
            Logger::error('Failed to commit transaction', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function rollBack(): bool
    {
        try {
            Logger::warning('Rolling back database transaction');
            return $this->dbh->rollBack();
        } catch (PDOException $e) {
            Logger::error('Failed to rollback transaction', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getQueryLog(): array
    {
        return $this->queryLog;
    }

    private function getLastQuery(): string
    {
        $lastQuery = end($this->queryLog);
        return $lastQuery ? $lastQuery['query'] : 'Unknown query';
    }

    public function isConnected(): bool
    {
        try {
            return $this->dbh && $this->dbh->query('SELECT 1')->fetchColumn() === '1';
        } catch (PDOException $e) {
            return false;
        }
    }

    public function __destruct()
    {
        $this->stmt = null;
        $this->dbh = null;
    }
}