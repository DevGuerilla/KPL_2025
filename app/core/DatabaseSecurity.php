<?php

class DatabaseSecurity
{
    private static array $suspiciousQueries = [
        'UNION', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER',
        'EXEC', 'EXECUTE', 'SCRIPT', 'DECLARE', 'CAST', 'CONVERT', 'LOAD_FILE',
        'INTO OUTFILE', 'INTO DUMPFILE', 'BENCHMARK', 'SLEEP', 'WAITFOR'
    ];

    public static function validateQuery(string $query, array $params = []): void
    {
        // Log all database queries for audit
        Logger::debug('Database query executed', [
            'query' => substr($query, 0, 100),
            'param_count' => count($params)
        ]);

        // Check for suspicious patterns in prepared statements
        self::detectSuspiciousPatterns($query);

        // Validate parameters
        self::validateQueryParameters($params);
    }

    private static function detectSuspiciousPatterns(string $query): void
    {
        $upperQuery = strtoupper($query);

        // Check for SQL injection patterns
        foreach (self::$suspiciousQueries as $pattern) {
            if (strpos($upperQuery, $pattern) !== false) {
                // This is expected for legitimate queries, but log for monitoring
                Logger::debug('SQL keyword detected', ['keyword' => $pattern]);
            }
        }

        // Check for comment-based injection attempts
        if (preg_match('/--|\*\/|\*|#/', $query)) {
            Logger::security('SQL comment detected in query', [
                'query_snippet' => substr($query, 0, 100)
            ]);
        }

        // Check for union-based injection
        if (preg_match('/UNION\s+SELECT/i', $query)) {
            Logger::security('UNION SELECT detected', [
                'query_snippet' => substr($query, 0, 100)
            ]);
        }
    }

    private static function validateQueryParameters(array $params): void
    {
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                // Check parameter length
                if (strlen($value) > 10000) {
                    Logger::security('Extremely long parameter detected', [
                        'param_key' => $key,
                        'length' => strlen($value)
                    ]);
                }

                // Check for suspicious content in parameters
                if (self::containsSuspiciousContent($value)) {
                    Logger::security('Suspicious parameter content', [
                        'param_key' => $key,
                        'value_snippet' => substr($value, 0, 100)
                    ]);
                }
            }
        }
    }

    private static function containsSuspiciousContent(string $value): bool
    {
        $suspiciousPatterns = [
            '/\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP)\b/i',
            '/\b(EXEC|EXECUTE|SCRIPT|DECLARE)\b/i',
            '/\b(LOAD_FILE|INTO\s+OUTFILE|INTO\s+DUMPFILE)\b/i',
            '/\b(BENCHMARK|SLEEP|WAITFOR)\b/i',
            '/\'.*\sOR\s.*\'=/i',
            '/\'.*\sAND\s.*\'=/i',
            '/--|\*\/|\*|#/',
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i',
            '/on\w+\s*=/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    public static function logQueryPerformance(string $query, float $executionTime): void
    {
        // Log slow queries
        if ($executionTime > 1.0) { // 1 second threshold
            Logger::warning('Slow query detected', [
                'query' => substr($query, 0, 200),
                'execution_time' => $executionTime
            ]);
        }

        // Log query performance for monitoring
        Logger::debug('Query performance', [
            'execution_time' => $executionTime,
            'query_length' => strlen($query)
        ]);
    }

    public static function sanitizeInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", "", $input);

        // Remove control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Trim whitespace
        $input = trim($input);

        return $input;
    }

    public static function validateTableName(string $tableName): bool
    {
        // Only allow alphanumeric characters and underscores
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName) === 1;
    }

    public static function validateColumnName(string $columnName): bool
    {
        // Only allow alphanumeric characters and underscores
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $columnName) === 1;
    }

    public static function escapeHtml(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function validateEmail(string $email): bool
    {
        $sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $sanitized === $email && filter_var($sanitized, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateUsername(string $username): bool
    {
        // Username: 3-30 characters, alphanumeric and underscore only
        return preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username) === 1;
    }

    public static function generateSecureToken(int $length = 32): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (Exception $e) {
            Logger::error('Failed to generate secure token', ['error' => $e->getMessage()]);
            // Fallback to less secure method
            return hash('sha256', uniqid(mt_rand(), true));
        }
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3,         // 3 threads
        ]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}