<?php

/**
 * Plugin Name: Test MB Matching
 */

/**
 * Création Table Personnalisée
 */


function my_plugin_activation_function()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = 'test';

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

function my_plugin_deactivation_function()
{
    // Code à exécuter lors de la désactivation du plugin
}

function my_plugin_uninstall_function()
{
    global $wpdb;
    $table_name = 'test';

    $sql = "DROP TABLE $table_name";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Hook d'activation
register_activation_hook(__FILE__, 'my_plugin_activation_function');
// Hook de désactivation
register_deactivation_hook(__FILE__, 'my_plugin_deactivation_function');
// Hook de suppression
register_uninstall_hook(__FILE__, 'my_plugin_uninstall_function');
