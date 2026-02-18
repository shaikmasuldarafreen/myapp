<?php
// Load Composer autoload for PHPMailer and other dependencies (optional)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Configuration settings for the project
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'php_learning');

// Other constants
define('SITE_NAME', 'PHP Learning Project');
define('BASE_URL', 'http://localhost/php-learning-project/public/');

// SMTP Configuration (for password reset emails)
// Get these values from your email provider (Gmail, Mailtrap, SendGrid, etc.)
define('SMTP_HOST', 'sandbox.smtp.mailtrap.io');     // Change to your SMTP server
define('SMTP_PORT', 2525);                           // Common ports: 587 (TLS), 465 (SSL), 2525 (Mailtrap)
define('SMTP_USER', '39190b5513c3f1');               // Your SMTP username
define('SMTP_PASS', '');                             // Your SMTP password - ADD HERE
define('SMTP_FROM_EMAIL', 'no-reply@myapp.test');    // From email
define('SMTP_FROM_NAME', 'PHP Learning Project');    // From name

// Session configuration - call BEFORE session_start()
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(['lifetime' => 3600, 'path' => '/php-learning-project/public/']);
    session_start();
}
?>