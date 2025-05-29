<?php

class LogManager
{
    private static ?string $logDirectory = null;
    private static int $maxLogFiles = 30; // Keep 30 days of daily logs

    private static function getLogDirectory(): string
    {
        if (self::$logDirectory === null) {
            $rootDir = dirname(__DIR__, 2);
            self::$logDirectory = $rootDir . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        }
        return self::$logDirectory;
    }

    public static function getLogs(string $date = null, string $levelFilter = null, int $limit = 100): array
    {
        $logDir = self::getLogDirectory();
        // Jika level filter ada, dan bukan 'all', coba baca file spesifik level.
        // Namun, ini akan rumit jika log level tidak disimpan per file terpisah.
        // Untuk implementasi saat ini yang menggabung ke log harian, kita filter setelah baca.
        $filename = $logDir . ($date ?? date('Y-m-d')) . '.log';

        if (!file_exists($filename) || !is_readable($filename)) {
            return [];
        }

        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) return [];

        $logs = [];
        // Baca dari akhir file untuk log terbaru
        foreach (array_reverse($lines) as $line) {
            if (count($logs) >= $limit) break;
            $log = json_decode($line, true);
            if ($log && (empty($levelFilter) || strtolower($log['level']) === strtolower($levelFilter))) {
                // Sanitasi untuk tampilan HTML di sini jika perlu, atau di viewer.
                // Lebih baik di viewer (logs.php) menggunakan htmlspecialchars.
                $logs[] = $log;
            }
        }
        return $logs; // Tidak perlu reverse lagi karena sudah dari array_reverse($lines)
    }

    public static function getLogStats(int $days = 7): array
    {
        $stats = [
            'total' => 0,
            'by_level' => [],
            'by_day' => [],
            'top_ips' => [],
            'recent_errors' => [] // Menyimpan 10 error terbaru
        ];
        $logDir = self::getLogDirectory();

        for ($i = 0; $i < $days; $i++) {
            $currentDate = date('Y-m-d', strtotime("-{$i} days"));
            $filename = $logDir . $currentDate . '.log';
            $stats['by_day'][$currentDate] = 0; // Inisialisasi

            if (file_exists($filename) && is_readable($filename)) {
                $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if ($lines === false) continue;

                foreach ($lines as $line) {
                    $log = json_decode($line, true);
                    if (!$log) continue;

                    $stats['total']++;
                    $stats['by_day'][$currentDate]++;
                    $level = $log['level'] ?? 'UNKNOWN';
                    $stats['by_level'][$level] = ($stats['by_level'][$level] ?? 0) + 1;

                    $ip = $log['ip_address'] ?? 'UNKNOWN';
                    $stats['top_ips'][$ip] = ($stats['top_ips'][$ip] ?? 0) + 1;

                    if (strtoupper($level) === 'ERROR' && count($stats['recent_errors']) < 10) {
                        $stats['recent_errors'][] = $log; // Simpan seluruh entri log error
                    }
                }
            }
        }
        arsort($stats['top_ips']); // Urutkan IP berdasarkan frekuensi
        $stats['top_ips'] = array_slice($stats['top_ips'], 0, 10, true); // Ambil top 10
        return $stats;
    }

    public static function cleanOldLogs(int $daysToKeep = null): void
    {
        $daysToKeep = $daysToKeep ?? self::$maxLogFiles;
        $logDir = self::getLogDirectory();
        // Target file harian .log dan file rotasi .log.timestamp atau .log.gz
        $files = glob($logDir . '*.log*');
        if ($files === false) return;

        $cutoffTime = time() - ($daysToKeep * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    // Logger::info("Deleted old log file: " . basename($file)); // Hindari rekursi jika Logger memanggil cleanOldLogs
                    error_log("SUCCESS: Deleted old log file by LogManager: " . basename($file));
                } else {
                    error_log("ERROR: Failed to delete old log file by LogManager: " . basename($file));
                }
            }
        }
    }
}