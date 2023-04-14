<?php

/**
 * Enregistre dans la BDD les associations entre les numéro de QRcode 
 * et les urls des biens immobiliers
 */


$xml = simplexml_load_file(__DIR__  . '/output-test.xml');

$biens = array();

foreach ($xml->BIEN as $bien) {
    $titre = (string) $bien->INFOS->TITLE;
    $num_qrcode = (string) $bien->CUSTOM->NUM_QRCODE;

    $biens[] = array(
        'titre' => $titre,
        'num_qrcode' => $num_qrcode
    );
}

foreach ($biens as $bien) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qrcode';

    $num_qrcode = $bien['num_qrcode'];
    $property_url = sanitize_title($bien["titre"]);

    // Utilisation de la fonction prepare pour éviter les attaques par injection SQL
    $query = $wpdb->prepare("
     INSERT INTO $table_name (numero, property_url)
     VALUES (%s, %s)
     ON DUPLICATE KEY UPDATE property_url = %s
 ", $num_qrcode, $property_url, $property_url);

    $wpdb->query($query);
}
