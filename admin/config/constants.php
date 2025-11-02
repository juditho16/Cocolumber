<?php
/**
 * 🌍 Global Constants
 * Auto-detect base URL for localhost or production.
 */

// Absolute server path
define('ROOT_PATH', dirname(__DIR__) . '/');

// Detect protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// Detect host (e.g. localhost)
$host = $_SERVER['HTTP_HOST'];

// Detect project subfolder (e.g. /cocolumber/admin/)
$dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', ROOT_PATH));

// Final base URL
define('BASE_URL', $protocol . $host . $dir);

// URL constants for frontend fetch or assets
define('FUNCTIONS_URL', BASE_URL . 'functions/');
define('PARTIALS_URL', BASE_URL . 'partials/');
define('PAGES_URL', BASE_URL . 'pages/');
define('PICTURES_URL', BASE_URL . 'pictures/');

// Server-side include paths
define('FUNCTIONS_PATH', ROOT_PATH . 'functions/');
define('PARTIALS_PATH', ROOT_PATH . 'partials/');
define('PAGES_PATH', ROOT_PATH . 'pages/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
