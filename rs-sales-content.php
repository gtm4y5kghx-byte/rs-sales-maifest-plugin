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

add_action('init', function () {
    RS_Sales_Post_Type::register();
});

add_action('rest_api_init', function () {
    $controller = new RS_Sales_REST_Controller();
    $controller->register_routes();
});
