<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class connectionController
{
    // Get the list of connections
    public static function get_connection_list(WP_REST_Request $request)
    {
        // Get current user's id
        // $user_id_1 = get_current_user_id();
        $user_id_1 = wp_validate_auth_cookie($_COOKIE['wordpress_logged_in_' . md5(site_url())], 'logged_in');

        if ($user_id_1 === 0) {
            return new WP_REST_Response('User not logged in ', 401);
        }

        // Fetch connections from the model
        $connections = connectionModel::get_connections($user_id_1);

        // Return the list of connections
        return new WP_REST_Response($connections, 200);
    }

    // Get the list of users
    public static function get_connections_users_list(WP_REST_Request $request)
    {
        // Fetch users from the model
        $users = connectionModel::get_connections_users();

        // Return the list of users
        return new WP_REST_Response($users, 200);
    }

    // Add a new connection request
    public static function add_connection(WP_REST_Request $request)
    {
        // Get current user's id
        // $user_id_1 = get_current_user_id();
        $user_id_1 = wp_validate_auth_cookie($_COOKIE['wordpress_logged_in_' . md5(site_url())], 'logged_in');

        // Check if user is logged in
        if ($user_id_1 === 0) {
            return new WP_REST_Response('User not logged in', 401);
        }

        $user_id_2 = $request->get_param('user_id_2');

        // Check if user id gotten from the request is valid
        if (!is_numeric($user_id_2)) {
            return new WP_REST_Response('The connection id is invalid', 400);
        }

        // Add the connection request
        $result = connectionModel::add_connection($user_id_1, $user_id_2);

        if ($result) {
            return new WP_REST_Response('Connection added successfully', 200);
        } else {
            return new WP_REST_Response('Failed to add connection or it\'s already exists', 500);
        }
    }

    // Accept a connection request
    public static function accept_connection(WP_REST_Request $request)
    {
        $connection_id = $request->get_param('request_id');

        // Check if connection id gotten from the request is valid
        if (!is_numeric($connection_id)) {
            return new WP_REST_Response('The connection id is invalid', 400);
        }

        // Accept the connection request
        $result = connectionModel::accept_connection($connection_id);

        if ($result) {
            return new WP_REST_Response('Connection accepted successfully', 200);
        } else {
            return new WP_REST_Response('Failed to accept connection', 500);
        }
    }

    // Reject a connection request
    public static function reject_connection(WP_REST_Request $request)
    {
        $connection_id = $request->get_param('request_id');

        // Check if connection id gotten from the request is valid
        if (!is_numeric($connection_id)) {
            return new WP_REST_Response('The connection id is invalid', 400);
        }

        // Reject the connection request
        $result = connectionModel::reject_connection($connection_id);

        if ($result) {
            return new WP_REST_Response('Connection rejected successfully', 200);
        } else {
            return new WP_REST_Response('Failed to reject connection', 500);
        }
    }
}
