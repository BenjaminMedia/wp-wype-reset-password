<?php namespace Bonnier\WP\WypeResetpassword\Db;

class DbSetup
{

    public static function db_install() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wype_password_reset_tokens';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
        SET sql_notes = 1;
        CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            token tinytext NOT NULL,
            email tinytext NOT NULL,
            provider tinytext NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;
        SET sql_notes = 1;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

}