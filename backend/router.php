<?php
// Laravel Router Script for PHP Built-in Server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false; // Serve the requested resource as-is
}

// Forward to Laravel's index.php
require_once __DIR__.'/public/index.php';
