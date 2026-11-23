<?php

/**
 * STORPIFY — Digital Commerce Marketplace
 * Hostinger Web Hosting Root Forwarder
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'
);

// If the requested resource is an actual static file in /public, let the server handle it
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
