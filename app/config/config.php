<?php
require __DIR__ . '/../../vendor/autoload.php';

// ENV
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

// APP
define("APPNAME", $_ENV['APP_NAME'] ?? 'UPTIME');
define("BASEURL", ($_ENV['APP_URL'] ?? '') . "/public");

// DATABASE
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? '');

// TIMEZONE DEFINE
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'Asia/Jakarta');
