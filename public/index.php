<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Define the base path for the application, so that we can use it in the future (e.g. for deployment)
$BASE_PATH = __DIR__.'/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $BASE_PATH.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Define the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Check if environment file is missing
if (!file_exists($BASE_PATH.'/.env') && strpos($requestUri, 'pre-setup') === false) {
    header('Location: /pre-setup.html');
    exit;
}

// Set default php configuration
ini_set('memory_limit', '512M');
ini_set('post_max_size', '64M');
ini_set('max_execution_time', '300');
ini_set('upload_max_filesize', '64M');

// Register the Composer autoloader
require $BASE_PATH.'/vendor/autoload.php';

// Check if the application requires installation
if (!file_exists($BASE_PATH.'/storage/.installed') && strpos($requestUri, 'install') === false) {
    header('Location: /install');
    exit;
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $BASE_PATH.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
