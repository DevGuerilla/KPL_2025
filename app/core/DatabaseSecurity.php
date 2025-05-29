<?php

class DatabaseSecurity
{
    public static function validateQuery(string $query, array $params = []): void
    {
        // logging for audit
        Logger::debug('Database query executed', [
            'query' => substr($query, 0, 100),
            'param_count' => count($params)
        ]);

        // Basic parameter validation
        self::validateQueryParameters($params);
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

                // Basic suspicious content check
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
            '/\'.*\sOR\s.*\'=/i',
            '/\'.*\sAND\s.*\'=/i',
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
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
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}