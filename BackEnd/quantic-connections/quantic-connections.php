<?php
/*
 * Plugin Name: Quantic Connections
 * Plugin URI: https://github.com/raouf505/FullStack-Test
 * Description: A plugin in order to complete the FullStack Test with Quantic
 * Version: 1.0
 * Author: AbdErraouf Eddef
 * Author URI: https://raouf333.tn
 * Text Domain: quantic-connections
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'app/core/Init.php';
require_once plugin_dir_path(__FILE__) . 'app/models/connectionModel.php';
require_once plugin_dir_path(__FILE__) . 'app/controllers/ConnectionController.php';

remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
add_filter('rest_pre_serve_request', function ($value) {
    header('Access-Control-Allow-Origin: http://localhost:3000/');
    header('Access-Control-Allow-Methods: POST,	GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    return $value;
});

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'connectionModel::install');
register_deactivation_hook(__FILE__, 'connectionModel::uninstall');

// Initialize the plugin
Init::init();
