<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class connectionModel
{
    //Create new table in the database with the name wp_connections
    public static function install()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "DROP TABLE IF EXISTS " . $table_name . ";
                CREATE TABLE " . $table_name . " (
                id int AUTO_INCREMENT PRIMARY KEY,
                user_id_1 varchar(50) NOT NULL,
                user_id_2 varchar(50) NOT NULL,
                status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending' NOT NULL,
                UNIQUE KEY unique_connection (user_id_1, user_id_2)
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    //Remove the table 'wp_connections' when deactivating the plugin
    public static function uninstall()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';
        $wpdb->query("DROP TABLE IF EXISTS " . $table_name);
    }

    // Fetch the list of connections from the database
    public static function get_connections($user_id_1)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';

        $query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id_1 = %d",
            $user_id_1
        );

        return $wpdb->get_results($query);
    }

    // Fetch the list of users from the database
    public static function get_connections_users()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'users';

        $query = $wpdb->prepare(
            "SELECT * FROM $table_name"
        );

        return $wpdb->get_results($query);
    }

    // Add a new connection request to the table 'wp_connections'
    public static function add_connection($user_id_1, $user_id_2)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';

        // Do a check to verify if connection already exists
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE (user_id_1 = %d AND user_id_2 = %d) OR (user_id_1 = %d AND user_id_2 = %d)",
                $user_id_1,
                $user_id_2,
                $user_id_2,
                $user_id_1
            )
        );

        if ($existing > 0) {
            return false; // If connection already exists we exit from this point
        }

        // It's ok the connection doesn't exist so we add a new one
        $result = $wpdb->insert(
            $table_name,
            [
                'user_id_1' => $user_id_1,
                'user_id_2' => $user_id_2,
            ]
        );

        return $result;
    }

    // Accept a connection request
    public static function accept_connection($connection_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';

        $result = $wpdb->update(
            $table_name,
            [
                'status' => 'accepted'
            ],
            [
                'id' => $connection_id
            ]
        );

        return $result;
    }

    // Reject a connection request
    public static function reject_connection($connection_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'connections';

        $result = $wpdb->update(
            $table_name,
            [
                'status' => 'rejected'
            ],
            [
                'id' => $connection_id
            ]
        );

        return $result;
    }
}
