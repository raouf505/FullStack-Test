<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Init
{
    // Initialize REST API routes
    public static function init()
    {
        // Register custom REST routes here
        add_action('rest_api_init', [self::class, 'register_routes']);
    }

    public static function register_routes()
    {
        // Register Connection routes
        register_rest_route('connections/v1', '/list', [
            'methods' => 'GET',
            'callback' => ['connectionController', 'get_connection_list'],
        ]);

        register_rest_route('connections/v1', '/users-list', [
            'methods' => 'GET',
            'callback' => ['connectionController', 'get_connections_users_list'],
        ]);

        register_rest_route('connections/v1', '/add', [
            'methods' => 'POST',
            'callback' => ['connectionController', 'add_connection'],
        ]);

        register_rest_route('connections/v1', '/accept', [
            'methods' => 'POST',
            'callback' => ['connectionController', 'accept_connection'],
        ]);
        
        register_rest_route('connections/v1', '/reject', [
            'methods' => 'POST',
            'callback' => ['connectionController', 'reject_connection'],
        ]);
    }
}

// Initialize the plugin
Init::init();
