<?php
// Simple health-check endpoint: visit /?health=1 to test JSON responses
if (isset($_GET['health'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status'      => 'ok',
        'php_version' => phpversion(),
        'hostname'    => gethostname() ?: 'unknown',
        'timestamp'   => gmdate('c'),
    ], JSON_PRETTY_PRINT);
    exit;
}

// Handle test form submission
$echoMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['test_input'])) {
    $echoMessage = htmlspecialchars(trim($_POST['test_input']), ENT_QUOTES, 'UTF-8');
}

$phpVersion     = phpversion();
$serverSoftware = htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'CLI / Built-in Server', ENT_QUOTES, 'UTF-8');
$hostname       = htmlspecialchars(gethostname() ?: 'localhost', ENT_QUOTES, 'UTF-8');
$osInfo         = htmlspecialchars(php_uname('s') . ' ' . php_uname('m'), ENT_QUOTES, 'UTF-8');
$serverTime     = gmdate('Y-m-d H:i:s') . ' UTC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PHP Deployment Verification</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
      color: #f8fafc;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
    .container {
      max-width: 780px;
      width: 100%;
      background: rgba(30, 41, 59, 0.85);
      border: 1px solid rgba(148, 163, 184, 0.2);
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid rgba(148, 163, 184, 0.15);
    }
    h1 {
      font-size: 1.75rem;
      background: linear-gradient(90deg, #38bdf8, #818cf8, #f472b6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .status-badge {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid #10b981;
      color: #34d399;
      padding: 0.35rem 0.9rem;
      border-radius: 999px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .stat-box {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(148, 163, 184, 0.12);
      border-radius: 12px;
      padding: 1rem 1.25rem;
    }
    .stat-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #94a3b8;
      margin-bottom: 0.35rem;
    }
    .stat-value {
      font-size: 1.05rem;
      font-weight: 600;
      color: #e2e8f0;
      word-break: break-all;
    }
    .test-panel {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(99, 102, 241, 0.3);
      border-radius: 12px;
      padding: 1.5rem;
    }
    .test-panel h2 {
      font-size: 1.1rem;
      margin-bottom: 0.75rem;
      color: #c7d2fe;
    }
    form {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }
    input[type="text"] {
      flex: 1;
      min-width: 200px;
      padding: 0.65rem 1rem;
      border-radius: 8px;
      border: 1px solid #475569;
      background: #0f172a;
      color: #f8fafc;
      font-size: 0.95rem;
    }
    button {
      padding: 0.65rem 1.25rem;
      border-radius: 8px;
      border: none;
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
      color: white;
      font-weight: 600;
      cursor: pointer;
    }
    button:hover { opacity: 0.92; }
    .result {
      margin-top: 1rem;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      background: rgba(56, 189, 248, 0.12);
      border-left: 4px solid #38bdf8;
      color: #bae6fd;
    }
    .footer {
      margin-top: 1.75rem;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.5rem;
      font-size: 0.85rem;
      color: #64748b;
    }
    .footer a { color: #38bdf8; text-decoration: none; }
    .footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>PHP Deployment Active</h1>
      <span class="status-badge">● Runtime Healthy</span>
    </div>

    <div class="grid">
      <div class="stat-box">
        <div class="stat-label">PHP Version</div>
        <div class="stat-value"><?= $phpVersion ?></div>
      </div>
      <div class="stat-box">
        <div class="stat-label">Web Server</div>
        <div class="stat-value"><?= $serverSoftware ?></div>
      </div>
      <div class="stat-box">
        <div class="stat-label">Container / Hostname</div>
        <div class="stat-value"><?= $hostname ?></div>
      </div>
      <div class="stat-box">
        <div class="stat-label">OS Architecture</div>
        <div class="stat-value"><?= $osInfo ?></div>
      </div>
    </div>

    <div class="test-panel">
      <h2>Test Dynamic POST Processing</h2>
      <form method="POST" action="">
        <input type="text" name="test_input" placeholder="Type a message to test PHP POST..." required />
        <button type="submit">Send Request</button>
      </form>
      <?php if ($echoMessage !== null): ?>
        <div class="result">
          <strong>Server Received:</strong> <?= $echoMessage ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="footer">
      <span>Rendered at <?= $serverTime ?></span>
      <a href="?health=1" target="_blank">Test JSON Health Endpoint (?health=1) →</a>
    </div>
  </div>
</body>
</html>
