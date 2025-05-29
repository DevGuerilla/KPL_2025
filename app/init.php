<?php

// Core files
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

// Configuration
require_once 'config/config.php';

// Security and validation
require_once 'core/SecurityValidator.php';
require_once 'core/SecurityException.php';
require_once 'core/InputValidator.php';
require_once 'core/DatabaseSecurity.php';
require_once 'core/FileSanitizer.php';

// Services
require_once 'services/AuthService.php';
require_once 'core/SessionManager.php';

// Utilities
require_once 'core/Logger.php';
require_once 'core/LogManager.php';
require_once 'core/Flasher.php';
require_once 'core/UploadFile.php';
require_once 'core/Helper.php';
require_once 'core/Captcha.php';

// Initialize security components
try {
    // Initialize logger first
    Logger::init();

    // Initialize session management
    SessionManager::init();

    // Log application start
    Logger::info('Application initialized', [
        'php_version' => PHP_VERSION,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);

    // Clean old logs periodically (1% chance per request)
    if (mt_rand(1, 100) === 1) {
        LogManager::cleanOldLogs();
    }

} catch (Exception $e) {
    // Fallback error handling if logger fails
    error_log('Application initialization failed: ' . $e->getMessage());

    // Show user-friendly error page
    http_response_code(500);
    echo '<!DOCTYPE html>
    <html>
    <head><title>System Error</title></head>
    <body>
        <h1>System Temporarily Unavailable</h1>
        <p>We are experiencing technical difficulties. Please try again later.</p>
    </body>
    </html>';
    exit;
}