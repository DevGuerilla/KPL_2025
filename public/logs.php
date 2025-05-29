<?php
if (!session_id()) {
    session_start();
}

// Simple authentication for log viewer
if (!isset($_SESSION['isLoggedIn'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit('Unauthorized');
}

require_once '../app/core/Logger.php';

$date = $_GET['date'] ?? date('Y-m-d');
$level = $_GET['level'] ?? null;
$limit = (int)($_GET['limit'] ?? 50);

$logs = Logger::getLogs($date, $level, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Viewer</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .filters { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        .log-entry { margin: 10px 0; padding: 10px; border-left: 4px solid #ddd; background: #f9f9f9; }
        .log-info { border-left-color: #007bff; }
        .log-warning { border-left-color: #ffc107; }
        .log-error { border-left-color: #dc3545; }
        .log-activity { border-left-color: #28a745; }
        .log-header { font-weight: bold; margin-bottom: 5px; }
        .log-meta { color: #666; font-size: 0.9em; }
        input, select, button { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h1>Log Viewer</h1>

    <div class="filters">
        <form method="GET">
            <label>Date:
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
            </label>

            <label>Level:
                <select name="level">
                    <option value="">All Levels</option>
                    <option value="info" <?= $level === 'info' ? 'selected' : '' ?>>Info</option>
                    <option value="warning" <?= $level === 'warning' ? 'selected' : '' ?>>Warning</option>
                    <option value="error" <?= $level === 'error' ? 'selected' : '' ?>>Error</option>
                    <option value="activity" <?= $level === 'activity' ? 'selected' : '' ?>>Activity</option>
                </select>
            </label>

            <label>Limit:
                <input type="number" name="limit" value="<?= $limit ?>" min="10" max="500">
            </label>

            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="logs">
        <?php if (empty($logs)): ?>
            <p>No logs found for the selected criteria.</p>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <div class="log-entry log-<?= strtolower($log['level']) ?>">
                    <div class="log-header">
                        [<?= $log['timestamp'] ?>] <?= $log['level'] ?> - <?= htmlspecialchars($log['message']) ?>
                    </div>
                    <div class="log-meta">
                        User: <?= htmlspecialchars($log['username']) ?> (ID: <?= htmlspecialchars($log['user_id']) ?>) |
                        IP: <?= htmlspecialchars($log['ip_address']) ?>
                        <?php if (!empty($log['context'])): ?>
                            <br>Context: <?= htmlspecialchars(json_encode($log['context'])) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>