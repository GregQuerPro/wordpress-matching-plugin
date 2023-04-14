<?php

/**
 * Plugin Name: QRcode
 */


function qrcode_create_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'qrcode';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        numero varchar(50) UNIQUE NOT NULL,
        property_url varchar(50) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'qrcode_create_table');


/**
 * Vérifie pour chaque requête si il s'agit d'une url
 * http://nomdomaine.com/qrcode/3
 * Si c'est le cas il y a une redirection vers la propriété associée
 */

$url = home_url($_SERVER['REQUEST_URI']);

$path = parse_url($url, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));
$slug = $path_parts[0];
$numero = $path_parts[1];

if ($slug === "qrcode") {
    if (is_numeric($numero)) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM wp_qrcode WHERE numero = %d", $numero);
        $results = $wpdb->get_results($query);
        if (count($results) > 0) {
            $redirect_url = "http://localhost/properties/" . $results[0]->property_url;
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $redirect_url);
            exit();
        }
    }
}
