<?php
// Configuration settings for the project
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'php_learning');

// Other constants
define('SITE_NAME', 'PHP Learning Project');
define('BASE_URL', 'http://localhost/php-learning-project/public/');

// Session configuration - call BEFORE session_start()
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600);
    session_set_cookie_params(['lifetime' => 3600, 'path' => '/php-learning-project/public/']);
    session_start();
}
?>