<?php

class MbMatchingEventListener
{

    public static function register()
    {
        $rootFile = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "mb-matching.php";
        // Hook d'activation
        register_activation_hook($rootFile, [self::class, 'activate']);
        // Hook de désactivation
        register_deactivation_hook($rootFile, [self::class, 'desactivate']);
        // Hook de suppression
        register_uninstall_hook($rootFile, [self::class, 'uninstall']);
    }

    public static function active()
    {
        // die('test');
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'user_searches';

        $sql = "CREATE TABLE $table_name (
        id bigint NOT NULL AUTO_INCREMENT,
        latitude text NOT NULL,
        longitude text NOT NULL,
        rayon text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        price text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        surface text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        room text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        nbr_criteria int NOT NULL,
        user_id bigint,
        PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function desactivate()
    {
        return;
    }

    public static function uninstall()
    {
        global $wpdb;
        $table_name = 'test';
        $sql = "DROP TABLE $table_name";
        $wpdb->query($sql);
    }
}
