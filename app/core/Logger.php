<?php

class Logger
{
    private static ?string $logDirectory = null;
    private static array $logLevels = ['DEBUG', 'INFO', 'ACTIVITY', 'WARNING', 'ERROR', 'SECURITY'];

    private static function getLogDirectory(): string
    {
        if (self::$logDirectory === null) {
            $rootDir = dirname(__DIR__, 2);
            self::$logDirectory = $rootDir . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        }
        return self::$logDirectory;
    }

    public static function init(): void
    {
        $logDir = self::getLogDirectory();
        if (!is_dir($logDir) && !mkdir($logDir, 0750, true) && !is_dir($logDir)) {
            error_log("Failed to create log directory: {$logDir}");
            throw new Exception("Cannot create log directory: {$logDir}");
        }
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        try {
            self::init();
            $level = strtoupper($level);
            if (!in_array($level, self::$logLevels)) {
                $level = 'INFO';
            }

            $logEntry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'level' => $level,
                'message' => self::sanitizeInput($message),
                'user_id' => $_SESSION['myProfile']['id_user'] ?? 'guest',
                'username' => self::sanitizeInput($_SESSION['myProfile']['username'] ?? 'guest'),
                'ip_address' => self::getClientIP(),
                'context' => $context
            ];

            $logLine = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
            $filename = self::getLogDirectory() . date('Y-m-d') . '.log';

            if (!file_put_contents($filename, $logLine, FILE_APPEND | LOCK_EX)) {
                error_log("Failed to write to log file: {$filename}");
            }

        } catch (Exception $e) {
            error_log("Logger error: " . $e->getMessage() . " | Original message: " . $message);
        }
    }

    public static function getLogs(string $date = null, string $levelFilter = null, int $limit = 100): array
    {
        $logDir = self::getLogDirectory();
        $filename = $logDir . ($date ?? date('Y-m-d')) . '.log';

        if (!file_exists($filename) || !is_readable($filename)) {
            return [];
        }

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return [];
        }

        $logs = [];
        foreach (array_reverse($lines) as $line) {
            if (count($logs) >= $limit) break;

            $log = json_decode($line, true);
            if ($log && (empty($levelFilter) || strtolower($log['level']) === strtolower($levelFilter))) {
                $logs[] = $log;
            }
        }
        return $logs;
    }

    private static function sanitizeInput(string $input): string
    {
        $sanitized = str_replace(["\r", "\n", "\t"], ' ', $input);
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $sanitized);
        return mb_substr($sanitized, 0, 500, 'UTF-8');
    }

    private static function getClientIP(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return 'UNKNOWN';
    }

    // Static methods for different log levels
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function activity(string $message, array $context = []): void
    {
        self::log('ACTIVITY', $message, $context);
    }

    public static function security(string $message, array $context = []): void
    {
        self::log('SECURITY', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            self::log('DEBUG', $message, $context);
        }
    }
}