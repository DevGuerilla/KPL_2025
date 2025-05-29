<?php
if (!session_id()) {
    session_start();
}

require_once '../app/init.php';

// COMMENT AUTH CHECK FOR DEVELOPMENT
// Simple authentication for log viewer
// if (!isset($_SESSION['isLoggedIn'])) {
//     header('HTTP/1.1 401 Unauthorized');
//     exit('Unauthorized');
// }

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
    <title>Log Viewer - Development Mode</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .filters {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        .log-entry {
            margin: 15px 0;
            padding: 15px;
            border-left: 4px solid #ddd;
            background: #f9f9f9;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .log-entry:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .log-info { border-left-color: #17a2b8; background: #d1ecf1; }
        .log-warning { border-left-color: #ffc107; background: #fff3cd; }
        .log-error { border-left-color: #dc3545; background: #f8d7da; }
        .log-activity { border-left-color: #28a745; background: #d4edda; }
        .log-security { border-left-color: #6f42c1; background: #e2d9f3; }
        .log-debug { border-left-color: #6c757d; background: #f8f9fa; }
        .log-header {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .log-meta {
            color: #666;
            font-size: 12px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        input, select, button {
            padding: 10px 15px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .no-logs {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        .dev-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📊 Log Viewer</h1>
        <p>Check Logs Here</p>
    </div>

    <div class="dev-notice">
        <strong>⚠️ Development Mode:</strong> Authentication disabled for development purposes.
        Enable authentication in production!
    </div>

    <div class="filters">
        <h3>🔍 Filter Logs</h3>
        <form method="GET" style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
            <label>
                📅 Date:
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
            </label>

            <label>
                📋 Level:
                <select name="level">
                    <option value="">All Levels</option>
                    <option value="info" <?= $level === 'info' ? 'selected' : '' ?>>Info</option>
                    <option value="warning" <?= $level === 'warning' ? 'selected' : '' ?>>Warning</option>
                    <option value="error" <?= $level === 'error' ? 'selected' : '' ?>>Error</option>
                    <option value="activity" <?= $level === 'activity' ? 'selected' : '' ?>>Activity</option>
                    <option value="security" <?= $level === 'security' ? 'selected' : '' ?>>Security</option>
                    <option value="debug" <?= $level === 'debug' ? 'selected' : '' ?>>Debug</option>
                </select>
            </label>

            <label>
                📊 Limit:
                <input type="number" name="limit" value="<?= $limit ?>" min="10" max="500" style="width: 80px;">
            </label>

            <button type="submit">Apply Filters</button>
            <button type="button" onclick="location.reload()">🔄 Refresh</button>
        </form>
    </div>

    <?php if (!empty($logs)): ?>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= count($logs) ?></div>
                <div>Total Entries</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($logs, fn($log) => strtolower($log['level']) === 'error')) ?></div>
                <div>Errors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($logs, fn($log) => strtolower($log['level']) === 'security')) ?></div>
                <div>Security Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count(array_filter($logs, fn($log) => strtolower($log['level']) === 'activity')) ?></div>
                <div>Activities</div>
            </div>
        </div>

        <div class="logs">
            <h3>📝 Log Entries (<?= $date ?>)</h3>
            <?php foreach ($logs as $log): ?>
                <div class="log-entry log-<?= strtolower($log['level'] ?? 'info') ?>">
                    <div class="log-header">
                        <span style="font-family: monospace; background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 4px;">
                            <?= htmlspecialchars($log['level'] ?? 'INFO') ?>
                        </span>
                        [<?= htmlspecialchars($log['timestamp'] ?? 'Unknown time') ?>]
                        <?= htmlspecialchars($log['message'] ?? 'No message') ?>
                    </div>
                    <div class="log-meta">
                        <strong>👤 User:</strong> <?= htmlspecialchars($log['username'] ?? 'Unknown') ?>
                        (ID: <?= htmlspecialchars($log['user_id'] ?? 'N/A') ?>) |
                        <strong>🌐 IP:</strong> <?= htmlspecialchars($log['ip_address'] ?? 'Unknown') ?>
                        <?php if (!empty($log['context']) && is_array($log['context'])): ?>
                            <br><strong>📄 Context:</strong>
                            <code style="background: rgba(0,0,0,0.05); padding: 2px 4px; border-radius: 3px; font-size: 11px;">
                                <?= htmlspecialchars(json_encode($log['context'], JSON_PRETTY_PRINT)) ?>
                            </code>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-logs">
            <h3>📭 No logs found</h3>
            <p>No log entries found for the selected criteria.</p>
            <p><strong>Tip:</strong> Try changing the date or removing filters.</p>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 12px;">
        <p>🚀 Log Viewer | Last updated: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>

<script>
    // Auto refresh every 30 seconds if no filters applied
    <?php if (empty($_GET['date']) && empty($_GET['level'])): ?>
    setTimeout(() => {
        location.reload();
    }, 30000);
    <?php endif; ?>

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            location.reload();
        }
    });
</script>
</body>
</html>