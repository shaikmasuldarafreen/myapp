<?php
require_once '../src/config.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';

$db = new Database();
$auth = new Auth($db);

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth->logout();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #ddd;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .welcome-section h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            font-size: 18px;
            opacity: 0.9;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <h1><?php echo htmlspecialchars(SITE_NAME); ?></h1>
        </div>
        <ul>
            <li><span><?php echo htmlspecialchars($user['email']); ?></span></li>
            <li><a href="?action=logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($user['email']); ?>! 👋</h2>
            <p>You have successfully logged in to your account.</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Account Status</h3>
                <div class="number">✓</div>
                <p>Active</p>
            </div>
            <div class="stat-card">
                <h3>User ID</h3>
                <div class="number"><?php echo htmlspecialchars($user['id']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Security</h3>
                <div class="number">🔒</div>
                <p>Secure</p>
            </div>
        </div>

        <div class="card">
            <h3>Quick Actions</h3>
            <p style="margin-bottom: 20px;">Here are some things you can do:</p>
            <ul style="list-style-position: inside; line-height: 2;">
                <li>View and manage your profile</li>
                <li>Update your password</li>
                <li>Configure account settings</li>
                <li>View your activity logs</li>
            </ul>
        </div>

        <div class="card">
            <h3>About This App</h3>
            <p>This is a PHP learning project demonstrating:</p>
            <ul style="list-style-position: inside; margin-top: 10px; line-height: 1.8; color: #666;">
                <li>User authentication (login, signup, logout)</li>
                <li>Password reset functionality</li>
                <li>Session management</li>
                <li>Database operations with PDO</li>
                <li>Form validation and security</li>
                <li>HTML/CSS for responsive design</li>
            </ul>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 <?php echo htmlspecialchars(SITE_NAME); ?> - All rights reserved.</p>
    </footer>
</body>
</html>
