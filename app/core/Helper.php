<?php
class Helper
{
    public static function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }

    public static function excerpt(string $string, int $length = 150): string
    {
        // Sanitize first to prevent XSS in excerpts
        $string = self::sanitizeOutput(strip_tags($string));
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length);
            $string = substr($string, 0, strrpos($string, ' '));
            $string = $string . '...';
        }
        return $string;
    }

    public static function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $current = time();
        $diff = $current - $time;

        $intervals = [
            31536000 => 't', // year
            2592000 => 'b',  // month
            604800 => 'm',   // week
            86400 => 'h',    // day
            3600 => 'j',     // hour
            60 => 'm'        // minute
        ];

        if ($diff <= 60) {
            return 'baru saja';
        }

        foreach ($intervals as $seconds => $suffix) {
            $interval = intval($diff / $seconds);
            if ($interval >= 1) {
                return $interval . $suffix . ' yang lalu';
            }
        }

        return 'baru saja';
    }

    public static function date(string $datetime): string
    {
        $date = date_create($datetime);
        return date_format($date, 'd M Y H:i') . ' WIB';
    }

    // CSRF Protection Methods
    public static function generateCSRFToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        // Regenerate token if it's older than 1 hour
        if (isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > 3600) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken(?string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            Logger::warning('CSRF validation failed - missing token', [
                'session_token_exists' => isset($_SESSION['csrf_token']),
                'provided_token_empty' => empty($token)
            ]);
            return false;
        }

        $isValid = hash_equals($_SESSION['csrf_token'], $token);

        if (!$isValid) {
            Logger::security('CSRF token mismatch detected', [
                'expected_token_length' => strlen($_SESSION['csrf_token']),
                'provided_token_length' => strlen($token),
                'ip_address' => self::getClientIP()
            ]);
        }

        return $isValid;
    }

    public static function renderCSRFField(): string
    {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    // XSS Protection Methods
    public static function sanitizeInput(string $input): string
    {
        // Remove null bytes and control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Remove potential XSS patterns
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/si',
            '/<iframe[^>]*>.*?<\/iframe>/si',
            '/<object[^>]*>.*?<\/object>/si',
            '/<embed[^>]*>/si',
            '/<applet[^>]*>.*?<\/applet>/si',
            '/<meta[^>]*>/si',
            '/<link[^>]*>/si',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i', // Event handlers like onclick, onload, etc.
            '/expression\s*\(/i',
            '/data:text\/html/i',
            '/data:image\/svg\+xml/i'
        ];

        foreach ($xssPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return trim($input);
    }

    public static function sanitizeOutput(string $output): string
    {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function sanitizeAttribute(string $attribute): string
    {
        // Extra strict sanitization for HTML attributes
        return htmlspecialchars($attribute, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function sanitizeURL(string $url): string
    {
        // Only allow http/https URLs
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '#';
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'])) {
            return '#';
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeHTML(string $html): string
    {


        $allowedTags = '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><a><img>';
        $html = strip_tags($html, $allowedTags);

        // Remove dangerous attributes
        $html = preg_replace('/\s*on\w+\s*=\s*["\']?[^"\']*["\']?/i', '', $html);
        $html = preg_replace('/\s*javascript\s*:/i', '', $html);
        $html = preg_replace('/\s*vbscript\s*:/i', '', $html);
        $html = preg_replace('/\s*data\s*:/i', '', $html);

        return $html;
    }

    // Form Validation Helpers
    public static function validateEmail(string $email): bool
    {
        $email = self::sanitizeInput($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && strlen($email) <= 255;
    }

    public static function validateUsername(string $username): bool
    {
        $username = self::sanitizeInput($username);
        return preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username) === 1;
    }

    public static function validateLength(string $input, int $min = 0, int $max = 255): bool
    {
        $length = strlen($input);
        return $length >= $min && $length <= $max;
    }

    // Utility Methods
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    public static function generateSlug(string $text): string
    {
        $text = self::sanitizeInput($text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }

    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function truncateText(string $text, int $limit = 100, string $suffix = '...'): string
    {
        $text = self::sanitizeOutput($text);
        if (strlen($text) <= $limit) {
            return $text;
        }
        return substr($text, 0, $limit) . $suffix;
    }

    public static function getCurrentUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        return self::sanitizeURL($protocol . '://' . $host . $uri);
    }

    public static function redirect(string $url, int $statusCode = 302): void
    {
        // Validate redirect URL to prevent open redirects
        if (!self::isValidRedirectURL($url)) {
            Logger::security('Invalid redirect URL attempted', ['url' => $url]);
            $url = BASEURL . '/';
        }

        header("Location: $url", true, $statusCode);
        exit;
    }

    private static function isValidRedirectURL(string $url): bool
    {
        // Allow relative URLs that start with /
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }

        // Allow URLs that start with BASEURL
        if (strpos($url, BASEURL) === 0) {
            return true;
        }

        return false;
    }

    public static function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        // Sanitize data before JSON encoding
        $sanitizedData = self::sanitizeArrayForJSON($data);

        echo json_encode($sanitizedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit;
    }

    private static function sanitizeArrayForJSON(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArrayForJSON($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = self::sanitizeOutput($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    public static function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function getClientIP(): string
    {
        $headers = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    // Security Headers
    public static function setSecurityHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' unpkg.com cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' unpkg.com fonts.googleapis.com; font-src \'self\' fonts.gstatic.com; img-src \'self\' data: ui-avatars.com');
    }
}