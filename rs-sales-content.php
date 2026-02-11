<?php
/**
 * Plugin Name: RS Sales Content
 * Description: REST API for sales content synchronization with PWA
 * Version: 1.0.0
 * Author: Jasen
 * Text Domain: rs-sales-content
 */

if (! defined('ABSPATH')) {
    exit;
}

define('RS_SALES_CONTENT_VERSION', '1.0.0');
define('RS_SALES_CONTENT_PATH', plugin_dir_path(__FILE__));

require_once RS_SALES_CONTENT_PATH . 'includes/class-post-type.php';
require_once RS_SALES_CONTENT_PATH . 'includes/class-rest-controller.php';
require_once RS_SALES_CONTENT_PATH . 'includes/class-manifest-builder.php';
require_once RS_SALES_CONTENT_PATH . 'includes/class-acf-fields.php';
require_once RS_SALES_CONTENT_PATH . 'includes/class-app-content-builder.php';

add_action('init', function () {
    RS_Sales_Post_Type::register();
});

RS_Sales_ACF_Fields::register();

add_action('rest_api_init', function () {
    $controller = new RS_Sales_REST_Controller();
    $controller->register_routes();
});

add_action('init', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        if (defined('RS_SALES_CORS_ORIGIN')) {
            $origin  = $_SERVER['HTTP_ORIGIN'] ?? '';
            $allowed = array_map('trim', explode(',', RS_SALES_CORS_ORIGIN));

            if (in_array($origin, $allowed, true)) {
                header('Access-Control-Allow-Origin: ' . $origin);
                header('Access-Control-Allow-Methods: GET, OPTIONS');
                header('Access-Control-Allow-Headers: X-RS-API-Key, Content-Type');
                header('Access-Control-Max-Age: 86400');
                header('Vary: Origin');
            }
        }
        status_header(204);
        exit;
    }
});
