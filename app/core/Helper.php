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
        $string = strip_tags($string);
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

    public static function generateCSRFToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function sanitizeInput(string $input): string
    {
        // Remove null bytes and control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Remove potential XSS
        $input = strip_tags($input);

        return trim($input);
    }

    public static function escapeOutput(string $output): string
    {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    public static function generateSlug(string $text): string
    {
        // Convert to lowercase
        $text = strtolower($text);

        // Replace non-alphanumeric characters with dashes
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        // Remove leading/trailing dashes
        $text = trim($text, '-');

        return $text;
    }

    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function truncateText(string $text, int $limit = 100, string $suffix = '...'): string
    {
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

        return $protocol . '://' . $host . $uri;
    }

    public static function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: $url", true, $statusCode);
        exit;
    }

    public static function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
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
}