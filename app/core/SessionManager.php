<?php

class SessionManager
{
    private const SESSION_TIMEOUT = 3600; // 1 hour
    private const REGENERATE_INTERVAL = 300; // 5 minutes

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.gc_maxlifetime', self::SESSION_TIMEOUT);

            session_start();
            self::validateSession();
        }
    }

    public static function validateSession(): void
    {
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
            $_SESSION['session_start'] = time();
            $_SESSION['regenerate_time'] = time();
            return;
        }

        // Check session timeout
        if (time() - $_SESSION['last_activity'] > self::SESSION_TIMEOUT) {
            Logger::activity('Session expired', [
                'session_id' => session_id(),
                'last_activity' => $_SESSION['last_activity']
            ]);
            self::destroy();
            return;
        }

        // Update last activity
        $_SESSION['last_activity'] = time();

        // Regenerate session ID periodically
        if (time() - $_SESSION['regenerate_time'] > self::REGENERATE_INTERVAL) {
            self::regenerate();
        }
    }

    public static function regenerate(): void
    {
        $oldSessionId = session_id();
        session_regenerate_id(true);
        $_SESSION['regenerate_time'] = time();

        Logger::activity('Session regenerated', [
            'old_session_id' => substr($oldSessionId, 0, 8),
            'new_session_id' => substr(session_id(), 0, 8)
        ]);
    }

    public static function destroy(): void
    {
        $sessionId = session_id();
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        Logger::activity('Session destroyed', [
            'session_id' => substr($sessionId, 0, 8)
        ]);
    }

    public static function login(array $user): void
    {
        self::regenerate();

        $_SESSION['isLoggedIn'] = true;
        $_SESSION['myProfile'] = $user;
        $_SESSION['login_time'] = time();
        $_SESSION['csrf_token'] = Helper::generateCSRFToken();

        Logger::info('User session established', [
            'user_id' => $user['id_user'],
            'username' => $user['username']
        ]);
    }

    public static function logout(): void
    {
        if (isset($_SESSION['myProfile'])) {
            Logger::info('User logged out', [
                'user_id' => $_SESSION['myProfile']['id_user'],
                'username' => $_SESSION['myProfile']['username']
            ]);
        }

        self::destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;
    }

    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            Logger::warning('Unauthorized access attempt', [
                'requested_url' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ]);
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }

    public static function getUser(): ?array
    {
        return $_SESSION['myProfile'] ?? null;
    }

    public static function getUserId(): ?int
    {
        return $_SESSION['myProfile']['id_user'] ?? null;
    }
}