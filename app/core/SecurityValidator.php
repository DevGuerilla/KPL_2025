<?php

class SecurityValidator
{
    private const MAX_REQUEST_SIZE = 5242880; // 5MB
    private const MAX_INPUT_LENGTH = 5000;
    private const RATE_LIMIT_REQUESTS = 50;
    private const RATE_LIMIT_WINDOW = 300; // 5 minutes

    private array $suspiciousPatterns = [
        '/script\s*>/i',
        '/<\s*script/i',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/\beval\s*\(/i',
        '/\bunion\s+select/i',
        '/\bselect\s+.*\bfrom/i',
        '/\binsert\s+into/i',
        '/\bdelete\s+from/i',
        '/\bdrop\s+table/i'
    ];

    public function validateRequest(array $postData, array $serverData): void
    {
        $this->checkRateLimit();
        $this->validateRequestSize($serverData);
        $this->validateInputData($postData);
    }

    private function checkRateLimit(): void
    {
        $ip = $this->getClientIP();
        $key = "rate_limit_{$ip}";

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'window_start' => time()];
        }

        $rateData = $_SESSION[$key];
        $currentTime = time();

        // Reset window if expired
        if ($currentTime - $rateData['window_start'] > self::RATE_LIMIT_WINDOW) {
            $_SESSION[$key] = ['count' => 1, 'window_start' => $currentTime];
            return;
        }

        $_SESSION[$key]['count']++;

        if ($_SESSION[$key]['count'] > self::RATE_LIMIT_REQUESTS) {
            Logger::security('Rate limit exceeded', [
                'ip' => $ip,
                'requests' => $_SESSION[$key]['count']
            ]);
            throw new SecurityException('Rate limit exceeded. Please try again later.');
        }
    }

    private function validateRequestSize(array $serverData): void
    {
        $contentLength = (int)($serverData['CONTENT_LENGTH'] ?? 0);
        if ($contentLength > self::MAX_REQUEST_SIZE) {
            Logger::security('Request size exceeded limit', [
                'content_length' => $contentLength,
                'max_allowed' => self::MAX_REQUEST_SIZE
            ]);
            throw new SecurityException('Request too large.');
        }
    }

    private function validateInputData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Check input length
                if (strlen($value) > self::MAX_INPUT_LENGTH) {
                    Logger::security('Input length exceeded', [
                        'field' => $key,
                        'length' => strlen($value)
                    ]);
                    throw new SecurityException('Input data too long.');
                }

                // Check for malicious patterns
                $this->checkMaliciousPatterns($key, $value);
            }
        }
    }

    private function checkMaliciousPatterns(string $field, string $value): void
    {
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                Logger::security('Malicious pattern detected', [
                    'field' => $field,
                    'pattern' => $pattern,
                    'value' => substr($value, 0, 100) // Log only first 100 chars
                ]);
                throw new SecurityException('Suspicious input detected.');
            }
        }
    }

    private function getClientIP(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return 'UNKNOWN';
    }
}